<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

use Core\Hash;
use Core\Model;

class                           User {
    use Model;

    public static               $collection = 'users';

    public static               $structure = [
        'email' => '',
        'password' => '',
        'gender' => '',
        'firstname' => '',
        'lastname' => '',
        'registered' => null,
        'admin' => 0,
        'lang' => '',
        'photo' => '',
        'email_verified' => 0,
        'slug' => null,
        'addr1' => null,
        'addr2' => null,
        'zipcode' => null,
        'city' => '',
        'country' => '',
        'phone' => '',
        'mobile' => '',
        'website' => '',
        'birthday' => ''
    ];

    public function             onExport($data) {
        unset($data['password']);
        return $data;
    }

    public static function      login($email, $password) {
        $user = self::findOne(['email' => $email]);
        if (!$user)
            return false;
        if (!Hash::same($password, $user->get('password')))
            return false;

        $user->updateSessionWithThisUser();

        return true;
    }

    public function             updateSessionWithThisUser() {
        $userData = $this->export();
        \Core\Session::set('user', $userData);
    }
}