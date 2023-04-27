<?php


namespace App\Providers;

use App\Repositories\Announcement\AnnouncementInterface;
use App\Repositories\Announcement\AnnouncementRepository;
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
            \App\Repositories\Product\AmazonProductInterface::class,
            \App\Repositories\Product\AmazonProductRepository::class
        );

        $this->app->bind(
            \App\Repositories\Order\SaleOrderInterface::class,
            \App\Repositories\Order\SaleOrderRepository::class
        );

        $this->app->bind(
            AnnouncementInterface::class,
            AnnouncementRepository::class
        );
    }
}
