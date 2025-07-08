<?php

declare(strict_types=1);

use App\Models\Tag;
use App\Models\User;

describe('update', function (): void {
    it('allows user to update owns tag', function (): void {
        $user = User::factory()->createOne();
        $tag = Tag::factory()->for($user)->createOne();

        expect($user->can('update', $tag))->toBeTrue();
    });

    it("denies user to update other's tag", function (): void {
        $user = User::factory()->createOne();
        $tag = Tag::factory()->createOne();

        expect($user->can('update', $tag))->toBeFalse();
    });
});

describe('delete', function (): void {
    it('allows user to delete owns tag', function (): void {
        $user = User::factory()->createOne();
        $tag = Tag::factory()->for($user)->createOne();

        expect($user->can('delete', $tag))->toBeTrue();
    });

    it("denies user to delete other's tag", function (): void {
        $user = User::factory()->createOne();
        $tag = Tag::factory()->createOne();

        expect($user->can('delete', $tag))->toBeFalse();
    });
});
