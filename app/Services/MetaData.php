<?php

declare(strict_types=1);

namespace App\Services;

use DOMDocument;
use GuzzleHttp\Exception\TransferException;
use GuzzleHttp\Psr7\Exception\MalformedUriException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\HttpClientException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Uri;

/**
 * @author Pinkary project (https://github.com/pinkary-project/pinkary.com)
 *
 * @see https://github.com/pinkary-project/pinkary.com/blob/main/app/Services/MetaData.php
 */
class MetaData
{
    public const int CARD_WIDTH = 512;

    public const int CARD_HEIGHT = 288;

    public function __construct(
        private readonly string $url
    ) {}

    /**
     * @return Collection<string, non-empty-string>
     */
    public function getData(): Collection
    {
        // If it’s a YouTube link, go straight to oEmbed
        // return early to bypass bot detection issues.
        if (in_array(
            needle: Uri::of($this->url)->host(),
            haystack: ['youtube.com', 'www.youtube.com', 'youtu.be', 'www.youtu.be'],
            strict: true
        )) {
            $oembed = $this->fetchOEmbed(
                service: 'https://www.youtube.com/oembed',
                options: [
                    'maxwidth' => self::CARD_WIDTH,
                    'maxheight' => self::CARD_HEIGHT,
                ]
            );
            if ($oembed->isNotEmpty()) {
                return $oembed;
            }
        }

        $data = collect();

        try {
            $response = Http::get($this->url);

            if ($response->ok() && $response->body() !== '') {
                $data = $this->parse(
                    $response->body()
                );
            }
        } catch (HttpClientException|MalformedUriException|TransferException) {
            // Catch but not capture all base exceptions for:
            // Laravel Http Client, Guzzle, and PSR-7
        }

        return $data->filter(fn (mixed $value): bool => is_string($value) && $value !== '');
    }

    public function ensureCorrectSize(string $value): string
    {
        if (! Str::contains($value, 'iframe')) {
            return $value;
        }

        $doc = new DOMDocument;
        @$doc->loadHTML($value);
        $iframe = $doc->getElementsByTagName('iframe')->item(0);

        if ($iframe !== null) {
            $iframe->setAttribute('width', (string) self::CARD_WIDTH);
            $iframe->setAttribute('height', (string) self::CARD_HEIGHT);

            return (string) $doc->saveHTML($iframe);
        }

        return $value;
    }

    public function checkExistsAndSize(string $image): bool
    {
        if (! (Http::head($image)->ok())) {
            return false;
        }

        $dimensions = @getimagesize($image);
        $min_width = self::CARD_WIDTH / 0.66;
        $min_height = self::CARD_HEIGHT / 0.66;

        return ! (is_array($dimensions) && ($dimensions[0] < $min_width || $dimensions[1] < $min_height));
    }

    /**
     * @param  array<string, string|int>  $options
     * @return Collection<string, non-empty-string>
     */
    private function fetchOEmbed(string $service, array $options): Collection
    {
        $data = collect();

        try {
            $response = Http::get(
                url: $service.'?url='.urlencode($this->url).'&'.http_build_query($options)
            );

            if ($response->ok()) {
                $data = $response->collect();
            }
        } catch (ConnectionException) {
            // Catch but not capture the exception
        }

        return $data->filter(fn (mixed $value): bool => is_string($value) && $value !== '');
    }

    /**
     * @return Collection<string, non-empty-string>
     */
    private function parseContent(string $content): Collection
    {
        $doc = new DOMDocument;
        @$doc->loadHTML($content);

        $interested_in = ['og', 'twitter'];
        $allowed = ['title', 'description', 'keywords', 'image', 'site_name', 'url', 'type'];
        /** @var Collection<string, string> $data */
        $data = new Collection;
        $metas = $doc->getElementsByTagName('meta');

        if ($metas->count() > 0) {
            foreach ($metas as $meta) {
                if (mb_strtolower($meta->getAttribute('name')) === 'title') {
                    $data->put('title', $meta->getAttribute('content'));
                }

                if (mb_strtolower($meta->getAttribute('name')) === 'description') {
                    $data->put('description', $meta->getAttribute('content'));
                }

                if (mb_strtolower($meta->getAttribute('name')) === 'keywords') {
                    $data->put('keywords', $meta->getAttribute('content'));
                }

                collect(['name', 'property'])
                    ->map(fn (string $name): string => $meta->getAttribute($name))
                    ->filter(fn (string $attribute): bool => in_array(explode(':', $attribute)[0], $interested_in, true))
                    ->each(function (string $attribute) use ($data, $allowed, $meta): void {
                        $key = explode(':', $attribute)[1];
                        if (! $data->has($key) && in_array($key, $allowed, true)) {
                            $data->put($key, $meta->getAttribute('content'));
                        }
                    });
            }
        }

        return $data->filter(fn (string $value): bool => $value !== '');
    }

    /**
     * Parse the response body for MetaData.
     *
     * @return Collection<string, non-empty-string>
     *
     * @throws ConnectionException
     */
    private function parse(string $html): Collection
    {
        $data = $this->parseContent($html);

        $isSuitable = true;

        if ($data->has('image')) {
            $isSuitable = $this->checkExistsAndSize((string) $data->get('image'));
        }

        if ($data->has('site_name') && $data->get('site_name') === 'X (formerly Twitter)') {
            $response = Http::withHeader('User-Agent', 'Twitterbot')->get($this->url);

            if ($response->ok() && $response->body() !== '') {
                $data = $this->parseContent($response->body());
                if ($data->has('image')) {
                    $isSuitable = $this->checkExistsAndSize((string) $data->get('image'));
                }
            }
        }

        if (! $isSuitable) {
            $data->forget('image');
        }

        if ($data->has('site_name') && $data->get('site_name') === 'Vimeo') {
            $vimeo = $this->fetchOEmbed(
                service: 'https://vimeo.com/api/oembed.json',
                options: [
                    'maxwidth' => self::CARD_WIDTH,
                    'maxheight' => self::CARD_HEIGHT,
                ]
            );
            if ($vimeo->isNotEmpty()) {
                foreach ($vimeo as $key => $value) {
                    $value = $key === 'html'
                        ? $this->ensureCorrectSize($value)
                        : $value;

                    if ($value !== '') {
                        $data->put($key, $value);
                    }
                }
            }
        }

        return $data->unique();
    }
}
