<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                   Core;

/**
 * Handles filesystem.
 *
 * Class FS
 * @package Core
 */
class				        FS {
    /**
     * Reads and returns the list of the files in a folder, with an absolute path.
     *
     * @param string $path          Folder path
     * @param bool $recursive       Recursive option
     * @param string $filter        fnmatch() filter
     *
     * @return array                File list
     */
    public static function	listFiles($path, $recursive = false, $filter = '') {
        $list = array();
        $path = realpath($path);
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle)))
                if ($entry != '.' && $entry != '..') {
                    if ($filter == '' || fnmatch($filter, $entry))
                        $list[] = "$path/$entry";
                    if ($recursive && is_dir("$path/$entry"))
                        $list = array_merge($list, FS::listFiles("$path/$entry", true, $filter));
                }
            closedir($handle);
        }
        return $list;
    }
}