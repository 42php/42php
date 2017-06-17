<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                               Core {
    /**
     * Class i18n
     * @package Core
     */
    class                                   i18n {
        /** @var array $translations Contain the translations */
        private static $translations = [];

        /** @var string $defaultLanguage Langue par défaut */
        public static $defaultLanguage = 'fr_FR';

        /** @var array $acceptedLanguages Langues disponibles */
        public static $acceptedLanguages = ['fr_FR'];

        public static function init() {
            self::$translations = Site::get('i18n.translations', []);
            self::$acceptedLanguages = Site::get('i18n.languages', ['fr_FR']);
            self::$defaultLanguage = Site::get('i18n.default', 'fr_FR');

            $user = false;

            if (Auth::logged()) {
                $user = Auth::user();
                $user->updateSessionWithThisUser();
            }

            if (isset($_GET['setLang'])) {
                $redirect = '/';
                if (isset($_GET['redirect']))
                    $redirect = $_GET['redirect'];

                $lang = $_GET['setLang'];
                if (!in_array($lang, self::$acceptedLanguages))
                    $lang = self::$defaultLanguage;

                Conf::set('lang', $lang);
                Session::set('lang', $lang);
                if ($user) {
                    $user->set('lang', $lang);
                    $user->save();
                }

                Redirect::http($redirect);
            }

            if (Session::get('lang', false)) {
                $lang = Session::get('lang', false);
                if (!in_array($lang, self::$acceptedLanguages)) {
                    $lang = self::$defaultLanguage;
                    Session::set('lang', $lang);
                    if ($user) {
                        $user->set('lang', $lang);
                        $user->save();
                    }
                }
                Conf::set('lang', $lang);
            } else {
                if (isset($_GET['lang']) && in_array($_GET['lang'], self::$acceptedLanguages)) {
                    Conf::set('lang', $_GET['lang']);
                    Session::set('lang', $_GET['lang']);
                    if ($user) {
                        $user->set('lang', $_GET['lang']);
                        $user->save();
                    }
                } else {
                    $lang = self::findBestLanguage(
                        isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : self::$defaultLanguage
                    );
                    Conf::set('lang', $lang);
                    Session::set('lang', $lang);
                }
            }
        }

        /**
         * Choose the best suitable language for user, according to browser parameters
         *
         * @param string $langs $_SERVER['HTTP_ACCEPT_LANGUAGE']
         * @return string                   Best language
         */
        public static function findBestLanguage($langs) {
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

        public static function setLang($lang) {
            // TODO
        }

        public static function get($k, $p = []) {
            // TODO
        }
    }
}

namespace {
    /**
     * Translates a string
     *
     * @param string $key       String to translate
     * @param array $params     Parameters
     *
     * @return string           Translated string
     */
    function _t($key, $params = []) {
        return \Core\i18n::get($key, $params);
    }
}