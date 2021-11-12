<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Managers\FileManager;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //Paginator::useBootstrapThree();
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);
	// if(env('APP_ENV')!=='local')
        //     \URL::forceScheme('https');
        if ($this->app->isLocal()) {
        //if local register your services you require for development
            // $this->app->register('Barryvdh\Debugbar\ServiceProvider');
        } else {
        //else register your services you require for production
            $this->app['request']->server->set('HTTPS', true);
        }
        config(['app.locale' => 'id']);
        Carbon::setLocale('id');
        date_default_timezone_set('Asia/Jakarta');
        // DatabaseNotificationCollection::macro('addModels', function () {
        //     return $this->each(function ($notification) {
        //         if(Arr::exists($notification->data, 'models')) {
        //             foreach($notification->data['models'] as $key => $id) {
        //                 $model = "\App\Models\\$key";
        //                 $models[$key] = $model::find((int)$id); // find() and findOrFail() need an integer to return one element.
        //             }
        //             $notification->models = $models;
        //         }
        //     });
        // });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->app->bind(FileManager::class, function($app) {
            return new FileManager(env('APP_URL'));
        });
    }
}
