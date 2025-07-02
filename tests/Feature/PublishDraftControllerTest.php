<?php

declare(strict_types=1);

use App\Actions\UpdateAndPublishDraft;
use App\Data\Requests\StoreLinkRequest;
use App\Models\Link;
use App\Models\User;

it('updates and publishes a draft link', function (): void {
    $user = User::factory()->createOne();
    $link = Link::factory()
        ->for($user)
        ->draft()
        ->createOne();

    $this->mockAction(UpdateAndPublishDraft::class)
        ->with(
            $link,
            new StoreLinkRequest(
                url: 'https://example.com',
                title: 'Example Title',
                description: 'Example Description',
                is_public: false,
                author: 'John Doe',
                tags: ['PHP'],
            )
        );

    $this->actingAs($user)
        ->put(route('drafts.publish', $link), [
            'url' => 'https://example.com',
            'title' => 'Example Title',
            'description' => 'Example Description',
            'is_public' => false,
            'author' => 'John Doe',
            'tags' => ['PHP'],
        ])
        ->assertRedirectToRoute('links.show', $link);
});
