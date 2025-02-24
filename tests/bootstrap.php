<?php
declare(strict_types=1);

use Cake\Cache\Cache;
use Cake\Cache\Engine\FileEngine;
use Cake\Core\Configure;

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

require_once 'vendor/autoload.php';
// require $root . '/vendor/cakephp/cakephp/tests/bootstrap.php';

define('ROOT', $root . DS . 'tests' . DS . 'test_app' . DS);
define('CAKE', $root . DS . 'vendor' . DS . 'cakephp' . DS . 'cakephp' . DS . 'src' . DS);
define('APP', ROOT . 'src' . DS);
// define('CONFIG', APP);
define('TMP', sys_get_temp_dir() . DS);
define('CACHE', TMP . 'cache' . DS);

Configure::write('debug', true);
Configure::write('Routing.autoRegister', true);

Cache::setConfig([
    'default' => [
        'engine' => FileEngine::class,
        'path' => TMP,
    ],
    'cake_attributes' => [
        'engine' => FileEngine::class,
        'path' => TMP,
    ],
]);

require CAKE . 'functions.php';

// See setUp() method inside tests
define('PLUGIN_TESTS', $root . DS . 'tests' . DS);
