<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                           Drivers\Database\PDO;

/**
 * Translates a mongodb style query into SQL
 *
 * Class MongoToSQL
 * @package Drivers\Database\PDO
 */
class                               MongoToSQL {
    /**
     * Translates a SELECT query
     *
     * @param string $table         Table name
     * @param array $query          MongoDB query
     * @param array $fields         List of returned fields
     * @param array $sort           Sort fields
     * @param bool $skip            Number of results to skip
     * @param bool $limit           Max number of results
     * @return string               SQL query
     */
    public static function          select($table, $query = [], $fields = [], $sort = [], $skip = false, $limit = false) {
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
     * Translates a INSERT query
     *
     * @param string $table         Table name
     * @param array $data           Data to insert. Pass a multi-dimensionnal array to batch insert
     * @param bool $multiple        Determine if multiple sets of data are passed
     * @return bool|string          SQL query
     */
    public static function          insert($table, $data = [], $multiple = false) {
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
     * Translates an UPDATE query
     *
     * @param string $table         Table name
     * @param array $values         Values to update
     * @param array $query          MongoDB query
     * @param bool $limit           Limit of documents to update
     * @return bool|string          SQL query
     */
    public static function          update($table, $values = [], $query = [], $limit = false) {
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
     * Translates a DELETE query
     *
     * @param string $table         Table name
     * @param array $query          MongoDB query
     * @param bool $limit           Number of documents to delete
     * @return string               SQL query
     */
    public static function          delete($table, $query = [], $limit = false) {
        $sql = 'DELETE FROM `' . $table . '` ';
        if (sizeof($query))
            $sql .= 'WHERE ' . self::where($query) . ' ';
        if ($limit !== false)
            $sql .= 'LIMIT ' . intval($limit);
        return trim($sql);
    }

    /**
     * Translates a WHERE field
     *
     * @param array $query          MongoDB query
     * @param string $join          Glue
     * @param string $parent        Parent field
     * @return string               WHERE query
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
     * Translates an ORDER BY query
     *
     * @param array $sort           Sort fields
     * @return string               ORDER query
     */
    public static function          sort($sort = []) {
        $sql = [];
        foreach ($sort as $k => $v) {
            $sql[] = '`' . $k . '` ' . ($v ? 'ASC' : 'DESC');
        }
        return implode(', ', $sql);
    }

    /**
     * Updates data coming from DB
     *
     * @param string $data          String
     * @return array|string|object  Data
     */
    public static function      fromDb($data) {
        if (substr($data, 0, 20) == '42php.db.json.array:') {
            return json_decode(substr($data, 20), true);
        }
        if (substr($data, 0, 21) == '42php.db.json.object:')
            return json_decode(substr($data, 21), false);
        return $data;
    }

    /**
     * Updates data before inserting in DB
     *
     * @param mixed $data       Value
     * @return string           Result
     */
    public static function      toDb($data) {
        if (is_array($data))
            $data = '42php.db.json.array:'.json_encode($data);
        if (is_object($data))
            $data = '42php.db.json.object:'.json_encode($data);
        return $data;
    }
}