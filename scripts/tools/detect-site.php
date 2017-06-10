<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

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
