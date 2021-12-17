<?php

namespace App\Providers;

use App\Models\ContentCategory;
use App\Models\Option;
use App\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $setting = [];

        ### Get Category
        $setting['category'] = ContentCategory::with('childs')->where('parent_id','0')->get();
        ### Get Site Setting
        $setting['site'] = arrayToList(Option::all(),'option','value');
        Paginator::useBootstrap();

        ### share setting variable to all views
        view()->share('setting',$setting);

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
