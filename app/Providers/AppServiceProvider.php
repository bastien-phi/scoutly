<?php

declare(strict_types=1);

namespace App\Providers;

use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->configureDate();
        $this->configureModels();
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

    private function registerMacros(): void
    {
        Builder::macro(
            'random',
            function () {
                $total = $this->toBase()->getCountForPagination();

                if ($total === 0) {
                    return null;
                }

                return $this->offset(random_int(0, $total - 1))->first();
            }
        );
    }
}
