<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Zip\Domain\Repositories\PostalCodeRepositoryInterface;
use Zip\Infrastructure\Repositories\EloquentPostalCodeRepository;

class ZipServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $repositories = $this->repositories();
        foreach ($repositories as $interface => $repository) {
            $this->app->bind($interface, $repository);
        }
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }


    public function repositories(): array
    {
        return [
            PostalCodeRepositoryInterface::class => EloquentPostalCodeRepository::class,
        ];
    }
}
