<?php
declare(strict_types=1);

use Cake\Cache\Cache;
use Cake\Cache\Engine\FileEngine;
use Cake\Core\Configure;
use TestApp\Controller\UsersController;

$findRoot = function ($root) {
    do {
        $lastRoot = $root;
        $root = dirname($root);
        if (is_dir($root . '/vendor/cakephp/cakephp')) {
            return $root;
        }
    } while ($root !== $lastRoot);
    throw new Exception('Cannot find the root of the application, unable to run tests');
};
$root = $findRoot(__FILE__);
unset($findRoot);
chdir($root);
require $root . '/vendor/cakephp/cakephp/tests/bootstrap.php';

Configure::write('Routing.controllers', [
    UsersController::class,
]);
Configure::write('Routing.autoRegister', true);

define('MYTMP', __DIR__ . DS . '..' . DS . 'tmp' . DS);

Cache::setConfig([
    'default' => [
        'engine' => FileEngine::class,
        'path' => MYTMP,
    ],
    'cake_attributes' => [
        'engine' => FileEngine::class,
        'path' => MYTMP,
    ],
]);

require CAKE . 'functions.php';

// See setUp() method inside tests
define('PLUGIN_TESTS', $root . DS . 'tests' . DS);
