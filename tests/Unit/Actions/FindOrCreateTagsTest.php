<?php

declare(strict_types=1);

use App\Actions\FindOrCreateTags;
use App\Models\Tag;
use Illuminate\Support\Collection;

it('create new tags if not exists', function (): void {
    $labels = new Collection(['PHP', 'Laravel']);

    app(FindOrCreateTags::class)->execute($labels);

    foreach ($labels as $label) {
        $this->assertDatabaseHas(Tag::class, [
            'label' => $label,
        ]);
    }
});

it('find existing tags', function (): void {
    $php = Tag::factory()->createOne(['label' => 'PHP']);
    $laravel = Tag::factory()->createOne(['label' => 'Laravel']);

    $labels = new Collection(['Php', 'larAvEl']);

    $found = app(FindOrCreateTags::class)->execute($labels);

    expect($found)->toBeCollectionCanonicalizing([$php, $laravel]);
});

it('filters duplicated tags', function (): void {
    Tag::factory()->createOne(['label' => 'PHP']);

    $labels = new Collection(['PHP', 'Php', 'Laravel', 'larAvEl']);

    $found = app(FindOrCreateTags::class)->execute($labels);

    expect($found)
        ->toHaveCount(2)
        ->pluck('label')->toBeCollectionCanonicalizing(['PHP', 'Laravel']);
});
