<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                           Core;

/**
 * Gère les requêtes sur la base de données
 *
 * Class Db
 * @package Core
 */
class                               Db {
    /**
     * Appelle la Factory du driver configuré. (\Drivers\Database\nomDuDriver\Factory::getInstance())
     *
     * @return mixed                L'instance de base de données
     */
    public static function          getInstance() {
        $factory = '\Drivers\Database\\'. Conf::get('database.type', 'PDO') .'\\Factory';
        if (class_exists($factory))
            return $factory::getInstance();
        return false;
    }

    /**
     * Formatte une date pour le driver configuré
     *
     * @param bool|int $timestamp   Timestamp
     * @param bool $withTime        Inclure le temps
     * @return mixed                La date formattée
     */
    public static function          date($timestamp = false, $withTime = true) {
        $factory = '\Drivers\Database\\'. Conf::get('database.type', 'PDO') .'\\Date';
        if (class_exists($factory))
            return $factory::format($timestamp === false ? time() : $timestamp, $withTime);
        return false;
    }

    /**
     * Formatte un identifiant de document pour le driver configuré
     *
     * @param mixed $id             Identifiant du document
     * @return mixed                L'identifiant formatté
     */
    public static function          id($id = false) {
        $factory = '\Drivers\Database\\'. Conf::get('database.type', 'PDO') .'\\Id';
        if (class_exists($factory))
            return $factory::format($id);
        return false;
    }

    /**
     * Formatte une expression régulière pour le driver configuré
     *
     * @param string $regex     L'expression régulière
     * @return mixed            L'expression régulière formattée
     */
    public static function          regex($regex) {
        $factory = '\Drivers\Database\\'. Conf::get('database.type', 'PDO') .'\\Regex';
        if (class_exists($factory))
            return $factory::format($regex);
        return false;
    }
}