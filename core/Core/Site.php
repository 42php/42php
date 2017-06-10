<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;

/**
 * Class Site
 * @package Core
 */
class                           Site {
    use                         StaticDotAccessor;

    /**
     * Load a site conf file into $__data.
     *
     * @param string $file      Conf file path
     * @return bool             TRUE if the conf file is loaded.
     */
    public static function      load($file) {
        if (!file_exists($file))
            return false;
        self::$__data = json_decode(file_get_contents($file), true);
        return true;
    }

    /**
     * Re-generate the /config/domains.json file with all site data.
     */
    public static function      generateDomainFile() {
        $sitesFiles = FS::listFiles(ROOT . '/config/sites/');

        $domains = [];

        foreach ($sitesFiles as $filepath) {
            $file = json_decode(
                file_get_contents($filepath),
                true
            );
            $domains[$file['domain']['main']] = $file['slug'];
            foreach ($file['domain']['alias'] as $domain)
                $domains[$domain] = $file['slug'];
        }

        file_put_contents(ROOT . '/config/domains.json', json_encode($domains, JSON_PRETTY_PRINT));
    }
}