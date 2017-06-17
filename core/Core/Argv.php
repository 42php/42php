<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;

/**
 * Handle URLS and routing.
 *
 * Class Argv
 * @package Core
 */
class 							Argv {
    public static               $routes = [];

    public static function      init() {
        self::$routes = json_decode(file_get_contents(ROOT . '/config/routes.json'), true);
    }

    public static function      loadSiteRoutes($routes = []) {
        self::$routes = array_merge(self::$routes, $routes);
    }

    /**
     * Read URL to extract parameters.
     *
     * @param string $url   URL
     * @param int $offset   Number of elements to ignore.
     *
     * @return array        Argv array
     */
    public static function 		parse($url, $offset = 0) {
        $argv = array();
        $url = urldecode($url);
        $url = explode('?', $url);
        $url = explode('/', $url[0]);
        foreach ($url as $u)
            if (strlen(trim($u)))
                $argv[] = str_replace('__separator__', '/', trim($u));
        while ($offset--)
            array_shift($argv);
        return $argv;
    }

    /**
     * Tests if the route matches supplied URL.
     *
     * @param string $url       URL
     * @param string $route     Route
     * @param bool $base        Determine if the route is just the base or the full URL
     *
     * @return array            Result
     * @throws ArgvException
     */
    private static function 	routeMatch($url, $route, $base = false) {
        $parsed_url = self::parse($url);
        $parsed_route = self::parse($route);

        $params = [];
        $lastVariableIsOptional = false;

        while (sizeof($parsed_url)) {
            $u = array_shift($parsed_url);
            $r = array_shift($parsed_route);

            if (is_null($r)) {
                if ($base) {
                    return ['match' => true, 'params' => $params, 'offset' => $lastVariableIsOptional ? 1 : 0];
                }
                return ['match' => false, 'params' => [], 'offset' => 0];
            }

            if ($r[0] == ':') {
                if (substr($r, -1) == '?') {
                    if (sizeof($parsed_route))
                        throw new ArgvException("Route can only have optionnal parameter at the end.");
                    $r = substr($r, 0, strlen($r) - 1);
                    $lastVariableIsOptional = true;
                }
                $r = substr($r, 1);
                $params[$r] = $u;
            } elseif ($r != '*' && $r != $u) {
                return ['match' => false, 'params' => [], 'offset' => 0];
            }
        }

        if (sizeof($parsed_route)) {
            if (sizeof($parsed_route) > 1)
                return ['match' => false, 'params' => [], 'offset' => 0];

            $r = $parsed_route[0];
            if ($r[0] == ':' && substr($r[0], -1) == '?') {
                $r = substr($r, 1, strlen($r) - 2);
                $params[$r] = false;
                $lastVariableIsOptional = true;
                return ['match' => true, 'params' => $params, 'offset' => $lastVariableIsOptional ? 1 : 0];
            }
        }

        return ['match' => true, 'params' => $params, 'offset' => $lastVariableIsOptional ? 1 : 0];
    }

    /**
     * Do the routing.
     * Tests one by one routes and find the best match.
     *
     * @param array $argv               Argv array.
     * @param array $routes             Route list
     * @param string $fieldToReturn     Field to return
     *
     * @return array|bool               Returns the matched route, or FALSE
     */
    public static function 		route($argv, $routes, $fieldToReturn = 'controller') {
        if (!sizeof($argv))
            $url = '/';
        else
            $url = '/'.implode('/', $argv).'/';
        $offset = -1;
        $toReturn = false;

        foreach ($routes as $name => $r) {
            foreach ($r['routes'] as $lang => $route) {
                if (substr($route, -1) != '/')
                    $route .= '/';
                $res = self::routeMatch($url, $route, isset($r['base']) && $r['base']);

                if ($res['match']) {
                    $potentialOffset = sizeof(self::parse($route)) - $res['offset'];
                    if ($potentialOffset > $offset) {
                        $offset = $potentialOffset;
                        $toReturn = [
                            $fieldToReturn => $r[$fieldToReturn],
                            'params' => $res['params'],
                            'route' => array(
                                'params' => $res['params'],
                                'name' => $name
                            ),
                            'offset' => $offset,
                            'conf' => $r,
                            'lang' => $lang
                        ];
                    }
                }
            }
        }
        return $toReturn;
    }

    /**
     * Create an absolute URL from a route and parameters.
     *
     * @param string $name          Route name
     * @param array $params         Parameters to apply
     * @param string|bool $lang     Route language
     *
     * @return string               Absolute URL
     */
    public static function 		createUrl($name, $params = [], $lang = false) {
        if (!$lang)
            $lang = Conf::get('lang');
        $routes = self::$routes;
        if (!isset($routes[$name]['routes'][$lang]))
            return '/';
        $url = str_replace('?', '', $routes[$name]['routes'][$lang]);
        foreach ($params as $k => $v)
            $url = str_replace('{'.$k.'}', $v, $url);
        return Conf::get('argv.base', '') . $url;
    }
}