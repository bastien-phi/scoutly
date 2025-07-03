<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void {}

    public function boot(): void
    {
        $this->configureDate();
        $this->configureModels();
        $this->configureVite();
        $this->registerMacros();
    }

    private function configureDate(): void
    {
        Date::useClass(CarbonImmutable::class);
    }

    protected function configureModels(): void
    {
        Model::unguard();
        Model::shouldBeStrict(! app()->isProduction());

        Relation::enforceMorphMap([]);
    }

    private function configureVite(): void
    {
        Vite::useAggressivePrefetching();
    }

    private function registerMacros(): void
    {
        Builder::macro(
            'random',
            function (): ?Model {
                $total = $this->toBase()->getCountForPagination();

                if ($total === 0) {
                    return null;
                }

                return $this->offset(random_int(0, $total - 1))->first();
            }
        );

        Builder::macro(
            'randomOrFail',
            fn (): Model => $this->random()
                ?? throw tap(new ModelNotFoundException)->setModel($this->getModel()::class)
        );
    }
}
