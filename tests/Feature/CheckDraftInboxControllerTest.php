<?php

declare(strict_types=1);

use App\Actions\CheckDraftInbox;
use App\Data\ToastData;
use App\Models\User;

it('checks draft inbox', function (): void {
    $user = User::factory()->createOne();

    $this->mockAction(CheckDraftInbox::class)
        ->with($user)
        ->returns(fn () => 3);

    $this->actingAs($user)
        ->post(route('drafts.check-inbox'))
        ->assertRedirectBack()
        ->assertSessionHas(
            'toast',
            ToastData::success('Successfully imported 3 new drafts.')
        );
});

it('is possible not to have new drafts', function (): void {
    $user = User::factory()->createOne();

    $this->mockAction(CheckDraftInbox::class)
        ->with($user)
        ->returns(fn () => 0);

    $this->actingAs($user)
        ->post(route('drafts.check-inbox'))
        ->assertRedirectBack()
        ->assertSessionHas(
            'toast',
            ToastData::info('No new drafts found.')
        );
});
