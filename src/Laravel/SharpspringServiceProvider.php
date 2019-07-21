<?php

/**
 * This file is part of the dllobell/sharpspring-api package.
 *
 * (c) David Llobell <dllobellmoya@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dllobell\SharpspringApi\Laravel;

use Illuminate\Support\ServiceProvider;
use Dllobell\SharpspringApi\SharpspringClient;

/**
 * Class SharpspringServiceProvider
 *
 * @package dllobell\sharpspring
 * @author  David Llobell  <dllobellmoya@gmail.com>
 */
class SharpspringServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/config/sharpspring.php' => config_path('sharpspring.php'),
        ], 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(SharpspringClient::class, function ($app) {
            return new SharpspringClient(
                $app['config']->get('sharpspring.account_id'),
                $app['config']->get('sharpspring.secret_key')
            );
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [SharpspringClient::class];
    }
}
