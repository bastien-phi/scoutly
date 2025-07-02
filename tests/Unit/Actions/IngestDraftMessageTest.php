<?php

declare(strict_types=1);

use App\Actions\IngestDraftMessage;
use App\Actions\StoreDraft;
use App\Data\Requests\StoreDraftRequest;
use App\Models\Link;
use App\Models\User;
use DirectoryTree\ImapEngine\Message;

it('ingests a draft message from text', function (): void {
    $user = User::factory()->createOne();

    $message = mock(Message::class);
    $message->shouldReceive('text')
        ->andReturn('I found a great app ! Check https://github.com/bastien-phi/scoutly !')
        ->once();
    $message->shouldReceive('subject')
        ->andReturn('Test draft')
        ->once();
    $message->shouldReceive('markSeen')
        ->once();
    $message->shouldReceive('delete')
        ->with(true)
        ->once();

    $this->mockAction(StoreDraft::class)
        ->with(
            $user,
            new StoreDraftRequest(
                url: 'https://github.com/bastien-phi/scoutly',
                title: 'Test draft',
                description: null,
                is_public: false,
                author: null,
                tags: null,
            )
        )
        ->returns(fn () => Link::factory()->createOne())
        ->in($createdLink);

    $link = app(IngestDraftMessage::class)->execute($user, $message);

    expect($link)->toBeModel($createdLink);
});

it('ingests a draft message from html', function (): void {
    $user = User::factory()->createOne();

    $message = mock(Message::class);
    $message->shouldReceive('text')
        ->andReturn(null)
        ->once();
    $message->shouldReceive('html')
        ->andReturn(
            <<<'HTML'
            <html>
                <head>
                    <link rel="stylesheet" href="https://example.com/style.css">
                </head>
                <body>
                    <p>I found a great app ! Check <a href="https://github.com/bastien-phi/scoutly"> https://github.com/bastien-phi/scoutly </a> </p>
                </body>
            </html>
            HTML
        )
        ->once();
    $message->shouldReceive('subject')
        ->andReturn('Test draft')
        ->once();
    $message->shouldReceive('markSeen')
        ->once();
    $message->shouldReceive('delete')
        ->with(true)
        ->once();

    $this->mockAction(StoreDraft::class)
        ->with(
            $user,
            new StoreDraftRequest(
                url: 'https://github.com/bastien-phi/scoutly',
                title: 'Test draft',
                description: null,
                is_public: false,
                author: null,
                tags: null,
            )
        )
        ->returns(fn () => Link::factory()->createOne())
        ->in($createdLink);

    $link = app(IngestDraftMessage::class)->execute($user, $message);

    expect($link)->toBeModel($createdLink);
});

it('does not ingest messages without link', function (): void {
    $user = User::factory()->createOne();

    $message = mock(Message::class);
    $message->shouldReceive('text')
        ->andReturn('I found a great app but I cannot remember where...')
        ->once();
    $message->shouldReceive('subject')
        ->andReturn('Test draft')
        ->once();
    $message->shouldReceive('markSeen')
        ->once();
    $message->shouldReceive('delete')
        ->with(true)
        ->once();

    $this->mockAction(StoreDraft::class)
        ->neverCalled();

    $link = app(IngestDraftMessage::class)->execute($user, $message);

    expect($link)->toBeNull();
});

it('does not ingest with empty text and html', function (): void {
    $user = User::factory()->createOne();

    $message = mock(Message::class);
    $message->shouldReceive('text')
        ->andReturn(null)
        ->once();
    $message->shouldReceive('html')
        ->andReturn(null)
        ->once();
    $message->shouldReceive('subject')
        ->andReturn('Test draft')
        ->once();
    $message->shouldReceive('markSeen')
        ->once();
    $message->shouldReceive('delete')
        ->with(true)
        ->once();

    $this->mockAction(StoreDraft::class)
        ->neverCalled();

    $link = app(IngestDraftMessage::class)->execute($user, $message);

    expect($link)->toBeNull();
});

it('ingests a draft message with long subject', function (): void {
    $user = User::factory()->createOne();

    $message = mock(Message::class);
    $message->shouldReceive('text')
        ->andReturn('I found a great app ! Check https://github.com/bastien-phi/scoutly !')
        ->once();
    $message->shouldReceive('subject')
        ->andReturn(str_repeat('A', 300))
        ->once();
    $message->shouldReceive('markSeen')
        ->once();
    $message->shouldReceive('delete')
        ->with(true)
        ->once();

    $this->mockAction(StoreDraft::class)
        ->with(
            $user,
            new StoreDraftRequest(
                url: 'https://github.com/bastien-phi/scoutly',
                title: str_repeat('A', 252).'...',
                description: null,
                is_public: false,
                author: null,
                tags: null,
            )
        )
        ->returns(fn () => Link::factory()->createOne())
        ->in($createdLink);

    app(IngestDraftMessage::class)->execute($user, $message);
});
