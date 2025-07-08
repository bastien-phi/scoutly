<?php

declare(strict_types=1);

use App\Models\Author;
use App\Models\User;

describe('update', function (): void {
    it('allows user to update owns author', function (): void {
        $user = User::factory()->createOne();
        $author = Author::factory()->for($user)->createOne();

        expect($user->can('update', $author))->toBeTrue();
    });

    it("denies user to update other's author", function (): void {
        $user = User::factory()->createOne();
        $author = Author::factory()->createOne();

        expect($user->can('update', $author))->toBeFalse();
    });
});

describe('delete', function (): void {
    it('allows user to delete owns author', function (): void {
        $user = User::factory()->createOne();
        $author = Author::factory()->for($user)->createOne();

        expect($user->can('delete', $author))->toBeTrue();
    });

    it("denies user to delete other's author", function (): void {
        $user = User::factory()->createOne();
        $author = Author::factory()->createOne();

        expect($user->can('delete', $author))->toBeFalse();
    });
});
