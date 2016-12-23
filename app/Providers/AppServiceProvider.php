<?php

namespace App\Providers;

use Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::directive('parsedown', function ($expression) {
            return "<?php \$Parsedown = new \Parsedown(); echo \$Parsedown->text(strip_tags(($expression))) ?>";
        });

        Blade::directive('striptags', function ($expression) {
            return "<?php echo nl2br(strip_tags($expression)) ?>";
        });
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
