<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {}

    public function boot()
    {
        ini_set('upload_max_filesize', '50M');
        ini_set('post_max_size', '50M');
        ini_set('memory_limit', '128M');

    }
}
