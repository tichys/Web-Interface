<?php

namespace App\Providers;

use Blade;
use IPBProvider;
use Illuminate\Support\ServiceProvider;
use Laravel\Telescope\TelescopeServiceProvider;

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
        if ($this->app->isLocal()) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    private function bootIPBSocialite(){
        $socialite = $this->app->make('Laravel\Socialite\Contracts\Factory');
        $socialite->extend(
            'ipb',
            function ($app) use ($socialite) {
                $config = $app['config']['services.ipb'];
                return $socialite->buildProvider(IPBProvider::class,$config);
            }
        );
    }
}
