<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configureModels();
    }

    protected function configureModels(): void
    {
        Model::unguard();
        Model::shouldBeStrict(!app()->isProduction());

        Relation::enforceMorphMap([]);
    }
}
