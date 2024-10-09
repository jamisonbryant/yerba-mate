<?php
declare(strict_types=1);

namespace CakeAttributes;

use Cake\Cache\Cache;
use Cake\Cache\Engine\FileEngine;
use Cake\Core\BasePlugin;
use Cake\Core\PluginApplicationInterface;
use Cake\Routing\RouteBuilder;
use CakeAttributes\Routing\RouteProvider;

/**
 * Plugin for Attributes
 */
class CakeAttributesPlugin extends BasePlugin
{
    /**
     * @param \Cake\Core\PluginApplicationInterface $app
     * @return void
     */
    public function bootstrap(PluginApplicationInterface $app): void
    {
        // Set a default config if it does not exist
        if (Cache::getConfig('cake_attributes') === null) {
            Cache::setConfig('cake_attributes', [
                'engine' => FileEngine::class,
                'path' => CACHE,
            ]);
        }
    }

    /**
     * Add routes for the plugin.
     *
     * If your plugin has many routes and you would like to isolate them into a separate file,
     * you can create `$plugin/config/routes.php` and delete this method.
     *
     * @param \Cake\Routing\RouteBuilder $routes The route builder to update.
     * @return void
     */
    public function routes(RouteBuilder $routes): void
    {
        $provider = new RouteProvider();
        $provider->autoRegister($routes);

        parent::routes($routes);
    }
}
