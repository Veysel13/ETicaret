<?php


namespace App\Providers;

use App\Repositories\Announcement\AnnouncementInterface;
use App\Repositories\Announcement\AnnouncementRepository;
use App\Repositories\Basket\BasketInterface;
use App\Repositories\Basket\BasketRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->app->bind(
            \App\Repositories\User\UserInterface::class,
            \App\Repositories\User\UserRepository::class
        );

        $this->app->bind(
            \App\Repositories\Restaurant\RestaurantInterface::class,
            \App\Repositories\Restaurant\RestaurantRepository::class
        );

        $this->app->bind(
            \App\Repositories\Category\CategoryInterface::class,
            \App\Repositories\Category\CategoryRepository::class
        );

        $this->app->bind(
            \App\Repositories\Company\CompanyInterface::class,
            \App\Repositories\Company\CompanyRepository::class
        );

        $this->app->bind(
            \App\Repositories\Product\ProductInterface::class,
            \App\Repositories\Product\ProductRepository::class
        );

        $this->app->bind(
            AnnouncementInterface::class,
            AnnouncementRepository::class
        );

        $this->app->bind(
            BasketInterface::class,
            BasketRepository::class
        );
    }
}
