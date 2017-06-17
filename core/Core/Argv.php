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
     * @param string $url      URL
     * @param string $route    Route
     *
     * @return array           Result
     */
    private static function 	routeMatch($url, $route) {
        $tmp = array();
        preg_match_all('/(\{[a-z0-9\-\_]+\})/i', $route, $matches);
        foreach ($matches[0] as $k)
            $tmp[] = [substr($k, 1, strlen($k) - 2), ''];

        $originalRoute = $route;

        if (strstr($originalRoute, '*') !== false) {
            if (fnmatch($originalRoute, $url))
                return ['match' => true, 'params' => [], 'offset' => 0 ];
            return ['match' => false, 'params' => [], 'offset' => 0 ];
        }

        $route = preg_replace('/(\{[a-z0-9\-\_]+\}\?)/i', '([\w\.\-\_]*)', $route);
        $route = preg_replace('/(\{[a-z0-9\-\_]+\})/i', '([\w\.\-\_]+)', $route);
        $route = str_replace('/', '\/', $route);
        $route = '/^'.$route.'?$/i';
        $res = preg_match_all($route, $url, $matches);
        for ($i = 1; $i < sizeof($matches); $i++)
            if (isset($matches[$i][0]))
                $tmp[$i - 1][1] = $matches[$i][0];

        $params = array();
        foreach ($tmp as $t)
            $params[$t[0]] = $t[1];

        return ['match' => $res ? true : false, 'params' => $params, 'offset' => substr($originalRoute, -2) == '?/' ? 1 : 0 ];
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
                $res = self::routeMatch($url, $route);
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
        return $url;
    }
}