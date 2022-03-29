<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class repostory extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(
            'App\Http\Interfaces\AuthInterface',
            'App\Http\Repositories\AuthRepository'
        );
        $this->app->bind(
            'App\Http\Interfaces\UserInterface',
            'App\Http\Repositories\UserRepository'
        );
        $this->app->bind(
            'App\Http\Interfaces\DepartmentInterface',
            'App\Http\Repositories\DepartmentRepository'
        );
            $this->app->bind(
            'App\Http\Interfaces\CategoryInterface',
            'App\Http\Repositories\CategoryRepository'
        );
        $this->app->bind(
            'App\Http\Interfaces\ProductInterface',
            'App\Http\Repositories\ProductRepository'
        );
        $this->app->bind(
            'App\Http\Interfaces\CartInterface',
            'App\Http\Repositories\CartRepository'
        );
        $this->app->bind(
            'App\Http\Interfaces\OrderInterface',
            'App\Http\Repositories\OrderRepository'
        );
        $this->app->bind(
            'App\Http\Interfaces\CommentInterface',
            'App\Http\Repositories\CommentRepository'
        );
        $this->app->bind(
            'App\Http\Interfaces\ComplaintInterface',
            'App\Http\Repositories\ComplaintRepository'
        );
        $this->app->bind(
            'App\Http\Interfaces\Answer_complaintInterface',
            'App\Http\Repositories\Answer_complaintRepository'
        );

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
