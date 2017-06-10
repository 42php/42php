<?php

/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

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
}