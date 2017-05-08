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
                    $lang = self::findBestLanguage(
                        isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : self::$defaultLanguage
                    );
                    Conf::set('lang', $lang);
                    Session::set('lang', $lang);
                }
            }
        }
    }

    /**
     * Choose the best suitable language for user, according to browser parameters
     *
     * @param string $langs             $_SERVER['HTTP_ACCEPT_LANGUAGE']
     * @return string                   Best language
     */
    public static function              findBestLanguage($langs) {
        $langs = explode(',', $langs);
        foreach ($langs as $lang) {
            $lang = str_replace('-', '_', explode(';', $lang)[0]);
            if (in_array($lang, self::$acceptedLanguages))
                return $lang;
            $small = strtolower(explode('_', $lang)[0]);
            foreach (self::$acceptedLanguages as $l) {
                if (strtolower(explode('_', $l)[0]) == $small)
                    return $l;
            }
        }
        return self::$defaultLanguage;
    }
}