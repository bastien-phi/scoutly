<?php

declare(strict_types=1);

use App\Actions\GetCommunityAuthors;
use App\Models\Author;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

describe('index', function (): void {
    it('returns authors', function (): void {
        $author = Author::factory()->createOne();

        $this->mockAction(GetCommunityAuthors::class)
            ->with(null)
            ->returns(fn () => Collection::make([$author]));

        $this->actingAs(User::factory()->createOne())
            ->getJson(route('api.community-authors.index'))
            ->assertOk()
            ->assertData([
                ['uuid' => $author->uuid, 'name' => $author->name],
            ]);
    });

    it('filters authors', function (): void {
        $this->mockAction(GetCommunityAuthors::class)
            ->with('foo')
            ->returns(fn () => Collection::make([]));

        $this->actingAs(User::factory()->createOne())
            ->getJson(route('api.community-authors.index', ['search' => 'foo']))
            ->assertOk()
            ->assertJsonPath('data', []);
    });
});
