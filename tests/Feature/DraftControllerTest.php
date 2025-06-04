<?php

declare(strict_types=1);

use App\Actions\StoreDraft;
use App\Data\DraftFormData;
use App\Models\Link;
use App\Models\User;

describe('store', function (): void {
    it('stores the draft', function (): void {
        $user = User::factory()->createOne();

        $this->mockAction(StoreDraft::class)
            ->with(
                $user,
                new DraftFormData(
                    url: 'https://example.com',
                    title: null,
                    description: null,
                    author: null,
                )
            )
            ->returns(fn () => Link::factory()->for($user)->draft()->createOne())
            ->in($draft);

        $this->actingAs($user)
            ->post(route('drafts.store'), [
                'url' => 'https://example.com',
                'title' => null,
                'description' => null,
                'author' => null,
            ])
            ->assertRedirect('/');
    });
});
