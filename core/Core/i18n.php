<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                               Core;

/**
 * Class i18n
 * @package Core
 */
class                                   i18n {
    /** @var array $translations        Contain the translations */
    private static                      $translations = [];

    /** @var string $defaultLanguage    Langue par défaut */
    public static                       $defaultLanguage = 'fr_FR';

    /** @var array $acceptedLanguages   Langues disponibles */
    public static                       $acceptedLanguages = ['fr_FR'];

    public static function              init() {
        self::$acceptedLanguages = Conf::get('i18n.languages');
        self::$defaultLanguage = Conf::get('i18n.default');

        if (false) {
            /**
             * TODO: We must create Auth system before testing, here, if the user is logged, to get his language preference
             */
        } else {
            if (Session::get('lang', false)) {
                $lang = Session::get('lang', false);
                if (!in_array($lang, self::$acceptedLanguages)) {
                    $lang = self::$defaultLanguage;
                    Session::set('lang', $lang);
                }
                Conf::set('lang', $lang);
            } else {
                if (isset($_GET['lang']) && in_array($_GET['lang'], self::$acceptedLanguages)) {
                    Conf::set('lang', $_GET['lang']);
                    Session::set('lang', $_GET['lang']);
                } else {
                    var_dump($_SERVER['HTTP_ACCEPT_LANGUAGE']);
                    die();
                }
            }
        }
    }
}