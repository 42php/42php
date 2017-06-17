<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

use Core\Api,
    Core\Auth,
    Core\Session,
    Core\Site;

/**
 * Class Api_Auth
 */
trait                   Api_Auth {
    public function     auth() {
        Api::post('/session', function() {
            Api::needFields(['email', 'password']);

            $ret = User::login($_REQUEST['email'], $_REQUEST['password']);
            if (!$ret)
                Api::error(403);

            return Session::get('user');
        });

        Api::delete('/session', function() {
            if (!Auth::logged())
                Api::error(403);

            Auth::logout(false);
            Api::code(204);
        });

        Api::post('/user', function() {
            if (!Site::get('auth.allowRegister', false))
                Api::error(403);

            Api::needFields(['email', 'password', 'password2']);

            if (!strlen($_REQUEST['email']) || !filter_var($_REQUEST['email'], FILTER_VALIDATE_EMAIL))
                Api::error(500, _t("Email address is not valid."));

            if (!strlen($_REQUEST['password']) || $_REQUEST['password'] != $_REQUEST['password2'])
                Api::error(400, _t("Passwords must be identical."));

            $checkEmail = User::findOne(['email' => $_REQUEST['email']]);
            if ($checkEmail)
                Api::error(409, _t("An user already exists with this email address."));

            $u = new User();
            $u->set('email', $_REQUEST['email']);
            $u->setPassword($_REQUEST['password']);
            foreach (['gender', 'firstname', 'lastname'] as $key)
                if (isset($_REQUEST[$key]))
                    $u->set($key, $_REQUEST[$key]);
            $u->save();

            if (isset($_REQUEST['loginOnSuccess']) && $_REQUEST['loginOnSuccess']) {
                $u->updateSessionWithThisUser();
            }

            Api::code(201);
        });
    }
}