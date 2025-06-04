<?php

declare(strict_types=1);

use App\Actions\UpdateAndPublishDraft;
use App\Data\LinkFormData;
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
            new LinkFormData(
                url: 'https://example.com',
                title: 'Example Title',
                description: 'Example Description',
                author: 'John Doe',
            )
        );

    $this->actingAs($user)
        ->put(route('drafts.publish', $link), [
            'url' => 'https://example.com',
            'title' => 'Example Title',
            'description' => 'Example Description',
            'author' => 'John Doe',
        ])
        ->assertRedirectToRoute('links.show', $link);
});
