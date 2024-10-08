<?php
declare(strict_types=1);

namespace CakeAttributes;

use Cake\Core\BasePlugin;
use Cake\Core\Configure;
use Cake\Core\PluginApplicationInterface;
use Cake\Routing\RouteBuilder;
use CakeAttributes\Router\RouteProvider;

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
        if (!Configure::check('Cache._cake_attributes_')) {
            // do something?
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
        $routes->plugin(
            'Attributes',
            function (RouteBuilder $builder): void {
                $provider = new RouteProvider();
                $provider->autoRegister($builder);
            }
        );

        parent::routes($routes);
    }
}
