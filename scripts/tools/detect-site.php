<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

use Core\Argv;
use Core\Conf;
use Core\Redirect;
use Core\Site;
use Core\Http;

$domains = json_decode(
    file_get_contents(ROOT . '/config/domains.json'),
    true
);

if (!isset($domains[$_SERVER['SERVER_NAME']])) {
    Http::responseCode(421);
    die();
}

Site::load(ROOT . '/config/sites/' . $domains[$_SERVER['SERVER_NAME']] . '.json');

if ($_SERVER['SERVER_NAME'] != Site::get('domain.main')) {
    $url = Http::baseUrl();
    $url = str_replace($_SERVER['SERVER_NAME'], Site::get('domain.main'), $url);
    $url .= $_SERVER['REQUEST_URI'];
    Redirect::permanent($url);
}

if (Site::get('ssl.force', false) && substr(Http::baseUrl(), 0, 8) != 'https://') {
    $url = Http::baseUrl();
    $url = str_replace($_SERVER['SERVER_NAME'], Site::get('domain.main'), $url);
    $url .= $_SERVER['REQUEST_URI'];
    $url = 'https://' . substr($url, 7);
    Redirect::permanent($url);
}

Argv::loadSiteRoutes(Site::get('routes', []));

/**
 * Merge page base data
 */
foreach (Site::get('page', []) as $k => $v) {
    if (is_array($v))
        Conf::set('page.' . $k, array_merge(Conf::get('page.' . $k, []), $v));
    else
        Conf::set('page.' . $k, $v);
}