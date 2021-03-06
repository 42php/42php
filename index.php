<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

use Core\Argv;
use Core\Conf;
use Core\Controller;
use Core\i18n;

require_once 'scripts/init.php';

/**
 * Routing : selecting the right route
 */
Conf::set('route', false);
$route = Argv::route($argv, Argv::$routes);
if (isset($route['route']))
    Conf::set('route', $route['route']);

/**
 * Setting the language according to route
 */
if (isset($route['lang']))
    $_GET['lang'] = $route['lang'];
i18n::init();

require_once ROOT . '/scripts/tools/initializeView.php';

if (!$route) {
    $route = [
        'controller' => 'SystemController@notFound',
        'params' => ''
    ];
}

echo Controller::run($route['controller'], $route['params']);