<?php

declare(strict_types=1);

use App\Actions\UpdateAuthor;
use App\Data\Requests\UpdateAuthorRequest;
use App\Models\Author;
use App\Models\Link;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Inertia\Testing\AssertableInertia;

describe('index', function (): void {
    it('returns authors', function (): void {
        $user = User::factory()->createOne();

        [$john, $jane] = Author::factory()
            ->for($user)
            ->createMany([
                ['name' => 'John Doe'],
                ['name' => 'Jane Smith'],
            ]);

        Link::factory(3)->for($user)->for($john)->create();

        Author::factory()->createOne();

        $this->actingAs($user)
            ->get(route('settings.authors.index'))
            ->assertOk()
            ->assertInertia(
                fn (AssertableInertia $page): AssertableJson => $page
                    ->component('settings/authors')
                    ->has('authors', 2)
                    ->where('authors.0', [
                        'uuid' => $jane->uuid,
                        'name' => 'Jane Smith',
                        'links_count' => 0,
                    ])
                    ->where('authors.1', [
                        'uuid' => $john->uuid,
                        'name' => 'John Doe',
                        'links_count' => 3,
                    ])
            );
    });
});

describe('update', function (): void {
    it('updates the author', function (): void {
        $user = User::factory()->createOne();
        $author = Author::factory()->for($user)->createOne();

        $this->mockAction(UpdateAuthor::class)
            ->with(
                $author,
                new UpdateAuthorRequest(
                    name: 'John Doe',
                )
            );

        $this->actingAs($user)
            ->put(route('settings.authors.update', $author), [
                'name' => 'John Doe',
            ])
            ->assertRedirectBack();
    });
});

describe('destroy', function (): void {
    it('destroy the author and redirects back', function (): void {
        $user = User::factory()->createOne();
        $author = Author::factory()->for($user)->createOne();

        $this->actingAs($user)
            ->delete(route('settings.authors.destroy', $author))
            ->assertRedirectBack();

        $this->assertModelMissing($author);
    });
});
