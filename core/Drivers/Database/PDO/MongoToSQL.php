<?php
/**
 * LICENSE: This source file is subject to version 3.0 of the GPL license
 * that is available through the world-wide-web at the following URI:
 * https://www.gnu.org/licenses/gpl-3.0.fr.html (french version).
 *
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://www.gnu.org/licenses/gpl-3.0.fr.html GPL
 */

namespace                           Drivers\Database\PDO;

/**
 * Permet de traduire une requête MongoDB en SQL
 *
 * Class MongoToSQL
 * @package Drivers\Database\PDO
 */
class                               MongoToSQL {
    /**
     * Traduit une requête SELECT
     *
     * @param string $table         Nom de la table
     * @param array $query          Requête MongoDB
     * @param array $fields         Liste des champs à retourner, si vide, "*"
     * @param array $sort           Champs de tri
     * @param bool $skip            Nombre de résultats à passer
     * @param bool $limit           Nombre de résultats à retourner
     * @return string               Requête SQL
     */
    public static function          select($table, $query = [], $fields = [], $sort = [], $skip = false, $limit = false) {
        \Core\Debug::trace();
        if (!$fields)
            $fields = [];
        $sql = 'SELECT ' . (sizeof($fields) ? '`'.implode('`, `', $fields).'`' : '*') . ' FROM `' . $table . '` ';
        if (sizeof($query))
            $sql .= 'WHERE ' . self::where($query) . ' ';
        if (sizeof($sort))
            $sql .= 'ORDER BY ' . self::sort($sort) . ' ';
        $limitstr = [];
        if ($skip !== false)
            $limitstr[] = intval($skip);
        if ($limit !== false)
            $limitstr[] = intval($limit);
        if (sizeof($limitstr))
            $sql .= 'LIMIT ' . implode(', ', $limitstr);
        return trim($sql);
    }

    /**
     * Traduit une requête INSERT INTO
     *
     * @param string $table         Nom de la table
     * @param array $data           Données à insérer. Passer un tableau multi-dimensionnel pour une insertion batch
     * @param bool $multiple        Détermine si les données sont à insérer en batch
     * @return bool|string          Requête SQL
     */
    public static function          insert($table, $data = [], $multiple = false) {
        \Core\Debug::trace();
        if (!sizeof($data))
            return false;
        if (!$multiple)
            $data = [$data];
        $fields = [];

        foreach ($data as $line)
            foreach ($line as $k => $v)
                if (!in_array($k, $fields))
                    $fields[] = $k;

        $lines = [];
        foreach ($data as $line) {
            $tmp = [];
            foreach ($fields as $f)
                $tmp[] = 'null';
            foreach ($line as $k => $v) {
                $tmp[array_search($k, $fields)] = \Core\Db::getInstance()->quote(self::toDb($v));
            }
            $lines[] = $tmp;
        }

        foreach ($lines as &$line)
            $line = implode(', ', $line);

        return 'INSERT INTO `' . $table . '` (`'.implode('`, `', $fields).'`) VALUES (' . implode('), (', $lines) . ')';
    }

    /**
     * Traduit une requête UPDATE
     *
     * @param string $table         Nom de la table
     * @param array $values         Valeurs à modifier
     * @param array $query          Requête MongoDB
     * @param bool $limit           Nombre de résultats à modifier au maximum
     * @return bool|string          Requête SQL
     */
    public static function          update($table, $values = [], $query = [], $limit = false) {
        \Core\Debug::trace();
        if (!sizeof($values))
            return false;

        $list = [];
        foreach ($values as $k => $v) {
            switch ($k) {
                case '$set':
                    foreach ($v as $kk => $vv) {
                        $list[] = '`' . $kk . '`=' . \Core\Db::getInstance()->quote(self::toDb($vv));
                    }
                    break;
                case '$currentDate':
                    foreach ($v as $kk => $vv) {
                        if (is_array($vv)) {
                            switch ($vv['$type']) {
                                case 'timestamp':
                                    $list[] = '`' . $kk . '`=' . \Core\Db::getInstance()->quote(\Core\Db::date(time(), true));
                                    break;
                                case 'date':
                                    $list[] = '`' . $kk . '`=' . \Core\Db::getInstance()->quote(\Core\Db::date(time(), false));
                                    break;
                            }
                        } elseif ($vv === true)
                            $list[] = '`' . $kk . '`=' . \Core\Db::getInstance()->quote(\Core\Db::date(time(), true));
                    }
                    break;
                case '$inc':
                    foreach ($v as $kk => $vv) {
                        $list[] = '`' . $kk . '`=`' . $kk . '` + ' . floatval($vv);
                    }
                    break;
                case '$mul':
                    foreach ($v as $kk => $vv) {
                        $list[] = '`' . $kk . '`=`' . $kk . '` * ' . floatval($vv);
                    }
                    break;
                case '$unset':
                    foreach ($v as $kk => $vv) {
                        $list[] = '`' . $kk . '`=null';
                    }
                    break;
            }
        }

        $sql = 'UPDATE `' . $table . '` SET ' . implode(', ', $list) . ' ';

        if (sizeof($query))
            $sql .= 'WHERE ' . self::where($query) . ' ';
        if ($limit !== false)
            $sql .= 'LIMIT ' . intval($limit);
        return trim($sql);
    }

    /**
     * Traduit une requête DELETE FROM
     *
     * @param string $table         Nom de la table
     * @param array $query          Requête MongoDB
     * @param bool $limit           Nombre de résultats à supprimer au maximum
     * @return string               Requête SQL
     */
    public static function          delete($table, $query = [], $limit = false) {
        \Core\Debug::trace();
        $sql = 'DELETE FROM `' . $table . '` ';
        if (sizeof($query))
            $sql .= 'WHERE ' . self::where($query) . ' ';
        if ($limit !== false)
            $sql .= 'LIMIT ' . intval($limit);
        return trim($sql);
    }

    /**
     * Traduit un champ WHERE
     *
     * @param array $query          Requête MongoDB
     * @param string $join          Jointure
     * @param string $parent        Champ parent
     * @return string               Champ WHERE
     */
    public static function          where($query = [], $join = ' AND ', $parent = '') {
        $str = [];
        foreach ($query as $k => $v) {
            if (is_array($v) && !in_array($k, ['$in', '$nin', '$and', '$or', '$not', '$nor']))
                $str[] = '(' . self::where($v, ' AND ', $k) . ')';
            else
                switch ($k) {
                    case '$regex':
                        $str[] = '`' . $parent . '` REGEXP ' . \Core\Db::getInstance()->quote(\Core\Db::regex($v));
                        break;
                    case '$eq':
                        $str[] = '`' . $parent . '`=' . \Core\Db::getInstance()->quote($v);
                        break;
                    case '$gt':
                        $str[] = '`' . $parent . '`>' . \Core\Db::getInstance()->quote($v);
                        break;
                    case '$gte':
                        $str[] = '`' . $parent . '`>=' . \Core\Db::getInstance()->quote($v);
                        break;
                    case '$lt':
                        $str[] = '`' . $parent . '`<' . \Core\Db::getInstance()->quote($v);
                        break;
                    case '$lte':
                        $str[] = '`' . $parent . '`<=' . \Core\Db::getInstance()->quote($v);
                        break;
                    case '$ne':
                        $str[] = '`' . $parent . '`!=' . \Core\Db::getInstance()->quote($v);
                        break;
                    case '$in':
                        $els = [];
                        foreach ($v as $vv)
                            $els[] = \Core\Db::getInstance()->quote($vv);
                        $str[] = '`' . $parent . '` IN (' . implode(', ', $els) . ')';
                        break;
                    case '$nin':
                        $els = [];
                        foreach ($v as $vv)
                            $els[] = \Core\Db::getInstance()->quote($vv);
                        $str[] = '`' . $parent . '` NOT IN (' . implode(', ', $els) . ')';
                        break;
                    case '$or':
                        $str[] = '(' . self::where($v, ' OR ', $parent) . ')';
                        break;
                    case '$and':
                        $str[] = '(' . self::where($v, ' AND ', $parent) . ')';
                        break;
                    case '$not':
                        $str[] = '!(' . self::where($v, ' AND ', $parent) . ')';
                        break;
                    case '$nor':
                        $str[] = '!(' . self::where($v, ' OR ', $parent) . ')';
                        break;
                    default:
                        $str[] = '`' . $k . '`=' . \Core\Db::getInstance()->quote($v);
                        break;
                }
        }
        return implode($join, $str);
    }

    /**
     * Traduit un champ ORDER BY
     *
     * @param array $sort           Champs de tri
     * @return string               Champ ORDER BY
     */
    public static function          sort($sort = []) {
        \Core\Debug::trace();
        $sql = [];
        foreach ($sort as $k => $v) {
            $sql[] = '`' . $k . '` ' . ($v ? 'ASC' : 'DESC');
        }
        return implode(', ', $sql);
    }

    /**
     * Traite un champ avant son retour de la DB
     *
     * @param string $data          Chaîne de caractère
     * @return array|string|object  Chaîne traitée
     */
    public static function      fromDb($data) {
        \Core\Debug::trace();
        if (substr($data, 0, 20) == '42php.db.json.array:') {
            return json_decode(substr($data, 20), true);
        }
        if (substr($data, 0, 21) == '42php.db.json.object:')
            return json_decode(substr($data, 21), false);
        return $data;
    }

    /**
     * Traite un champ avant son import en base
     *
     * @param mixed $data       Valeur
     * @return string           Résultat
     */
    public static function      toDb($data) {
        \Core\Debug::trace();
        if (is_array($data))
            $data = '42php.db.json.array:'.json_encode($data);
        if (is_object($data))
            $data = '42php.db.json.object:'.json_encode($data);
        return $data;
    }
}