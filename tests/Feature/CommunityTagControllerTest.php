<?php

declare(strict_types=1);

use App\Actions\GetCommunityTags;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

describe('index', function (): void {
    it('returns tags', function (): void {
        $tag = Tag::factory()->createOne();

        $this->mockAction(GetCommunityTags::class)
            ->with(null)
            ->returns(fn () => Collection::make([$tag]));

        $this->actingAs(User::factory()->createOne())
            ->getJson(route('community.tags.index'))
            ->assertOk()
            ->assertJsonPath('data', [
                ['id' => $tag->id, 'label' => $tag->label],
            ]);

    });

    it('filters tags', function (): void {
        $this->mockAction(GetCommunityTags::class)
            ->with('foo')
            ->returns(fn () => Collection::make([]));

        $this->actingAs(User::factory()->createOne())
            ->getJson(route('community.tags.index', ['search' => 'foo']))
            ->assertOk()
            ->assertJsonPath('data', []);
    });
});
