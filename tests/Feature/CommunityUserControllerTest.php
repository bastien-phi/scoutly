<?php

declare(strict_types=1);

use App\Actions\GetCommunityUsers;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

describe('index', function (): void {
    it('returns users', function (): void {
        $user = User::factory()->createOne();

        $this->mockAction(GetCommunityUsers::class)
            ->with(null)
            ->returns(fn () => Collection::make([$user]));

        $this->actingAs(User::factory()->createOne())
            ->getJson(route('api.community-users.index'))
            ->assertOk()
            ->assertData([
                ['uuid' => $user->uuid, 'username' => $user->username],
            ]);
    });

    it('filters users', function (): void {
        $this->mockAction(GetCommunityUsers::class)
            ->with('foo')
            ->returns(fn () => Collection::make([]));

        $this->actingAs(User::factory()->createOne())
            ->getJson(route('api.community-users.index', ['search' => 'foo']))
            ->assertOk()
            ->assertJsonPath('data', []);
    });
});
