<?php

declare(strict_types=1);

use App\Actions\UpdateTag;
use App\Data\Requests\UpdateTagRequest;
use App\Models\Link;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;

describe('index', function (): void {
    it('returns tags', function (): void {
        $user = User::factory()->createOne();

        [$php, $laravel] = Tag::factory()
            ->for($user)
            ->createMany([
                ['label' => 'PHP'],
                ['label' => 'Laravel'],
            ]);

        Link::factory(3)->for($user)->hasAttached($php)->create();

        Tag::factory()->createOne();

        $this->actingAs($user)
            ->get(route('settings.tags.index'))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableJson => $page
                    ->component('settings/tags')
                    ->has('tags', 2)
                    ->where('tags.0', [
                        'uuid' => $laravel->uuid,
                        'label' => 'Laravel',
                        'links_count' => 0,
                    ])
                    ->where('tags.1', [
                        'uuid' => $php->uuid,
                        'label' => 'PHP',
                        'links_count' => 3,
                    ])
            );
    });
});

describe('update', function (): void {
    it('updates the tag', function (): void {
        $user = User::factory()->createOne();
        $tag = Tag::factory()->for($user)->createOne();

        $this->mockAction(UpdateTag::class)
            ->with(
                $tag,
                new UpdateTagRequest(
                    label: 'PHP',
                )
            );

        $this->actingAs($user)
            ->put(route('settings.tags.update', $tag), [
                'label' => 'PHP',
            ])
            ->assertRedirectBack();
    });
});

describe('destroy', function (): void {
    it('destroys the tag and redirects back', function (): void {
        $user = User::factory()->createOne();
        $tag = Tag::factory()->for($user)->createOne();

        $this->actingAs($user)
            ->delete(route('settings.tags.destroy', $tag))
            ->assertRedirectBack();

        $this->assertModelMissing($tag);
    });
});
