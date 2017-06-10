<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                   Core;

/**
 * Class Text
 * @package Core
 */
class 						Text {
    /**
     * Creates a random string
     *
     * @param int $length           Length of the random string
     * @param string $charset       Charset
     *
     * @return string               Created string
     */
    public static function 	random($length = 8, $charset = 'azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN1234567890') {
        $text = '';
        while ($length-- > 0)
            $text .= $charset[rand(0, strlen($charset) - 1)];
        return $text;
    }

    /**
     * Sluggifies a string
     *
     * @param string $str           String to clean
     * @param array $replace        Chars to delete
     * @param string $delimiter     Delimiter
     *
     * @return string               Cleaned string
     */
    public static function 	slug($str, $replace = array(), $delimiter = '-') {
        setlocale(LC_ALL, 'en_US.UTF8');
        if (!empty($replace)) {
            $str = str_replace((array)$replace, ' ', $str);
        }
        $str = self::ru2lat($str);
        $clean = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
        return $clean;
    }

    /**
     * Convert a string from cyrilic to latin.
     *
     * @param string $str   Cyrilic string
     *
     * @return string       Latin string
     */
    public static function 	ru2lat($str) {
        $tr = array(
            "А"=>"a", "Б"=>"b", "В"=>"v", "Г"=>"g", "Д"=>"d",
            "Е"=>"e", "Ё"=>"yo", "Ж"=>"zh", "З"=>"z", "И"=>"i",
            "Й"=>"j", "К"=>"k", "Л"=>"l", "М"=>"m", "Н"=>"n",
            "О"=>"o", "П"=>"p", "Р"=>"r", "С"=>"s", "Т"=>"t",
            "У"=>"u", "Ф"=>"f", "Х"=>"kh", "Ц"=>"ts", "Ч"=>"ch",
            "Ш"=>"sh", "Щ"=>"sch", "Ъ"=>"", "Ы"=>"y", "Ь"=>"",
            "Э"=>"e", "Ю"=>"yu", "Я"=>"ya", "а"=>"a", "б"=>"b",
            "в"=>"v", "г"=>"g", "д"=>"d", "е"=>"e", "ё"=>"yo",
            "ж"=>"zh", "з"=>"z", "и"=>"i", "й"=>"j", "к"=>"k",
            "л"=>"l", "м"=>"m", "н"=>"n", "о"=>"o", "п"=>"p",
            "р"=>"r", "с"=>"s", "т"=>"t", "у"=>"u", "ф"=>"f",
            "х"=>"kh", "ц"=>"ts", "ч"=>"ch", "ш"=>"sh", "щ"=>"sch",
            "ъ"=>"", "ы"=>"y", "ь"=>"", "э"=>"e", "ю"=>"yu",
            "я"=>"ya", " "=>"-", "."=>"", ","=>"", "/"=>"-",
            ":"=>"", ";"=>"","—"=>"", "–"=>"-"
        );
        return strtr($str,$tr);
    }

    /**
     * Get filename extension
     *
     * @param string $filename      Filename
     * @param bool $uppercase       Chooses if the extension should be uppercase or lowercase
     *
     * @return string               Extension
     */
    public static function  getExtension($filename, $uppercase = false) {
        $ext = explode('.', $filename);
        $ext = $ext[sizeof($ext) - 1];
        if ($uppercase)
            $ext = strtoupper($ext);
        return $ext;
    }
}