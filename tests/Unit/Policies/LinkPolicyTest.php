<?php

declare(strict_types=1);

use App\Models\Link;
use App\Models\User;

describe('view', function (): void {
    it('allows user to view owns link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)->published()->createOne();

        expect($user->can('view', $link))->toBeTrue();
    });

    it('denies user to view draft link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)->draft()->createOne();

        expect($user->can('view', $link))->toBeFalse();
    });

    it("denies user to view other's link", function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->published()->createOne();

        expect($user->can('view', $link))->toBeFalse();
    });
});

describe('update', function (): void {
    it('allows user to update owns link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)->published()->createOne();

        expect($user->can('update', $link))->toBeTrue();
    });

    it('denies user to update draft link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)->draft()->createOne();

        expect($user->can('update', $link))->toBeFalse();
    });

    it("denies user to update other's link", function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->published()->createOne();

        expect($user->can('update', $link))->toBeFalse();
    });
});

describe('updateDraft', function (): void {
    it('allows user to update owns draft link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)->draft()->createOne();

        expect($user->can('update-draft', $link))->toBeTrue();
    });

    it('denies user to update published link', function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->for($user)->published()->createOne();

        expect($user->can('update-draft', $link))->toBeFalse();
    });

    it("denies user to update other's draft", function (): void {
        $user = User::factory()->createOne();
        $link = Link::factory()->draft()->createOne();

        expect($user->can('update-draft', $link))->toBeFalse();
    });
});
