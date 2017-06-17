<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

class                   AuthController extends \Core\Controller {
    public function     login() {

    }

    public function     register() {

    }

    public function     resetPassword() {

    }

    public function     logout() {
        $redirect = '/';
        if (isset($_GET['redirect']))
            $redirect = $_GET['redirect'];
        \Core\Auth::logout($redirect);
    }
}