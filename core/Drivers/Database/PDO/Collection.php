<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Drivers\Database\PDO;

/**
 * Class Collection
 * @package Drivers\Database\PDO
 */
class                           Collection implements \Drivers\Database\Collection {
    /**
     * @var null|\PDO           PDO object
     */
    private                     $pdo = null;

    /**
     * @var Factory|null        Factory
     */
    private                     $handler = null;

    /**
     * @var string $table       Table name
     */
    private                     $table = '';

    /**
     * Collection constructor.
     *
     * @param string $tableName Table name
     * @param Factory $handler  Handler
     * @param \PDO $pdo         PDO object
     */
    public function             __construct($tableName, $handler, $pdo) {
        $this->pdo = $pdo;
        $this->handler = $handler;
        $this->table = $tableName;
    }

    /**
     * Insert a document into database
     *
     * @param array $data       Document to insert
     * @return string           Inserted document ID
     */
    public function             insert(&$data) {
        $query = MongoToSQL::insert($this->table, $data);
        $this->handler->exec($query);
        $data['id'] = $this->handler->lastId();
        return $data['id'];
    }

    /**
     * Update documents
     *
     * @param array $clause     Query
     * @param array $data       Update
     * @param array $options    Update options
     * @return mixed            Number of affected documents
     */
    public function             update($clause = [], $data = [], $options = []) {
        $limit = 1;
        if (isset($option['multiple']) && $option['multiple'])
            $limit = false;
        $query = MongoToSQL::update($this->table, $data, $clause, $limit);
        return $this->handler->exec($query);
    }

    /**
     * Upserts a document
     *
     * @param array $data       Document to save
     * @return mixed            Document ID
     */
    public function             save(&$data) {
        if (isset($data['id'])) {
            $d = $data;
            unset($d['id']);
            $this->update([
                'id' => \Core\Db::id($data['id'])
            ], [
                '$set' => $d
            ]);
        } else {
            $this->insert($data);
        }
        return $data['id'];
    }

    /**
     * Find a list of documents
     *
     * @param array $clause     Query
     * @param array $fields     Fields to return
     * @param array $sort       Sort
     * @param bool|int $skip    Number of documents to skip
     * @param bool|int $limit   Number of documents to return
     * @return array|bool       List
     */
    public function             find($clause = [], $fields = [], $sort = [], $skip = false, $limit = false) {
        $query = MongoToSQL::select($this->table, $clause, $fields, $sort, $skip, $limit);
        $ret = $this->handler->query($query);
        if (!$ret)
            return false;
        foreach ($ret as &$row)
            foreach ($row as &$d)
                $d = \Drivers\Database\PDO\MongoToSQL::fromDb($d);
        return $ret;
    }

    /**
     * Finds a document
     *
     * @param array $clause     Query
     * @param array $fields     Fields to return
     * @return mixed            First found document
     */
    public function             findOne($clause = [], $fields = []) {
        $ret = $this->find($clause, $fields, [], false, 1);
        if ($ret && sizeof($ret)) {
            return $ret[0];
        }
        return false;
    }

    /**
     * Delete documents
     *
     * @param array $clause     Query
     * @param array $options    Deletion options
     * @return int              Number of deleted documents
     */
    public function             remove($clause = [], $options = []) {
        $limit = false;
        if (isset($options['justOne']) && $options['justOne'])
            $limit = 1;
        $query = MongoToSQL::delete($this->table, $clause, $limit);
        return $this->handler->exec($query);
    }
}