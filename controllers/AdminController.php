<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

use Core\Conf;
use Core\View;

/**
 * Class AdminController
 */
class                   AdminController extends \Core\Controller {
    public function     display() {
        Conf::set('page.title', _t("Panneau d'administration"));

        /**
         * Fonts
         */
        Conf::append('page.css', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,400italic');
        Conf::append('page.css', 'https://fonts.googleapis.com/icon?family=Material+Icons');

        /**
         * Vue2 JS
         */
        Conf::append('page.js', 'https://unpkg.com/vue@2.3.4');

        /**
         * Vue Material
         */
        Conf::append('page.js', 'https://unpkg.com/vue-material@0.7.1');
        Conf::append('page.css', 'https://unpkg.com/vue-material@0.7.1/dist/vue-material.css');

        /**
         * App
         */
        Conf::append('page.js', '/js/admin/app.min.js');

        return View::render('admin/app');
    }
}