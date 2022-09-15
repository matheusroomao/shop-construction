<?php

namespace App\Providers;

use App\Repository\Business\BrandRepository;
use App\Repository\Business\CartPurchaseRepository;
use App\Repository\Business\ProductRepository;
use App\Repository\Business\PurchaseRepository;
use App\Repository\Business\UserProductRepository;
use App\Repository\Business\UserRepository;
use App\Repository\Contracts\BrandInterface;
use App\Repository\Contracts\CartPurchaseInterface;
use App\Repository\Contracts\ProductInterface;
use App\Repository\Contracts\PurchaseInterface;
use App\Repository\Contracts\UserInterface;
use App\Repository\Contracts\UserProductInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->bind(UserInterface::class,UserRepository::class);
        $this->app->bind(BrandInterface::class,BrandRepository::class);
        $this->app->bind(ProductInterface::class,ProductRepository::class);
        $this->app->bind(UserProductInterface::class,UserProductRepository::class);
        $this->app->bind(PurchaseInterface::class,PurchaseRepository::class);
        $this->app->bind(CartPurchaseInterface::class,CartPurchaseRepository::class);

    }
}
