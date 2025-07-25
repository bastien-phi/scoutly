<?php

declare(strict_types=1);

use App\Actions\FindOrCreateTags;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Support\Collection;

it('create new tags if not exists', function (): void {
    $user = User::factory()->createOne();
    $labels = ['PHP', 'Laravel'];

    app(FindOrCreateTags::class)->execute($user, $labels);

    foreach ($labels as $label) {
        $this->assertDatabaseHas(Tag::class, [
            'user_id' => $user->id,
            'label' => $label,
        ]);
    }
});

it('handle null tags', function (): void {
    $user = User::factory()->createOne();

    $tags = app(FindOrCreateTags::class)->execute($user, null);

    expect($tags)->toBeCollection(new Collection);
});

it('find existing tags', function (): void {
    $user = User::factory()->createOne();
    $php = Tag::factory()->for($user)->createOne(['label' => 'PHP']);
    $laravel = Tag::factory()->for($user)->createOne(['label' => 'Laravel']);

    $labels = ['Php', 'larAvEl'];

    $tags = app(FindOrCreateTags::class)->execute($user, $labels);

    expect($tags)->toBeCollectionCanonicalizing([$php, $laravel]);
});

it('filters duplicated tags', function (): void {
    $user = User::factory()->createOne();
    Tag::factory()->for($user)->createOne(['label' => 'PHP']);

    $labels = ['PHP', 'Php', 'Laravel', 'larAvEl'];

    $tags = app(FindOrCreateTags::class)->execute($user, $labels);

    expect($tags)
        ->toHaveCount(2)
        ->pluck('label')->toBeCollectionCanonicalizing(['PHP', 'Laravel']);
});
