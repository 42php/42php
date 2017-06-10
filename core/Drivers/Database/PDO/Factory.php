<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Drivers\Database\PDO;

/**
 * Handle DB connection with PDO
 *
 * Class Factory
 * @package Drivers\Database\PDO
 */
class                           Factory implements \Drivers\Database\Factory {
    /**
     * @var null|Factory $singleton Singleton instance
     */
    private static              $singleton = null;

    /**
     * Gets the singleton instance
     *
     * @return bool|Factory
     */
    public static function      getInstance() {
        if (is_null(self::$singleton)) {
            try {
                $pdo = new \PDO(
                    \Core\Site::get('database.config.dsn', 'mysql:host=localhost;dbname=42php'),
                    \Core\Site::get('database.config.user', 'root'),
                    \Core\Site::get('database.config.pass', '')
                );
                $pdo->exec("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
                self::$singleton = new self($pdo, \Core\Site::get('database.config.prefix', ''));
            } catch (\PDOException $e) {
                echo "PDO: " . $e->getMessage() . "\n";
                return false;
            }
        }
        return self::$singleton;
    }

    /**
     * @var null|\PDO           PDO object
     */
    private                     $pdo = null;

    /**
     * @var string $prefix      Prefix for all the tables
     */
    private                     $prefix = '';

    /**
     * Factory constructor.
     * @param \PDO $pdo
     * @param string $prefix
     */
    public function             __construct(\PDO $pdo, $prefix = '') {
        $this->pdo = $pdo;
        $this->prefix = $prefix;
    }

    /**
     * Logout PDO
     */
    public function             close() {
        $this->pdo = null;
        self::$singleton = null;
    }

    /**
     * Get a collection
     *
     * @param string $k         Collection name
     * @return Collection
     */
    public function             __get($k) {
        return new Collection($this->prefix . $k, $this, $this->pdo);
    }

    /**
     * Calls PDO::quote()
     *
     * @param string $value     Value
     * @return string           Filtered value
     */
    public function             quote($value) {
        try {
            return $this->pdo->quote($value);
        } catch (\PDOException $e) {
            if (\Core\Site::get('debug', false))
                echo "PDO: SQL error: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Calls PDO::exec()
     *
     * @param string $query     SQL query
     * @return int              Number of affected results
     */
    public function             exec($query) {
        try {
            return $this->pdo->exec($query);
        } catch (\PDOException $e) {
            if (\Core\Site::get('debug', false))
                echo "PDO: SQL error: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Calls PDO::query() and returns all results
     *
     * @param string $query     SQL query
     * @return array|bool       All the results or FALSE if an error occured
     */
    public function             query($query) {
        try {
            $ret = $this->pdo->query($query);
            if (!$ret)
                return false;
            return $ret->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            if (\Core\Site::get('debug', false))
                echo "PDO: SQL error: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Calls PDO::query() and returns the first result
     *
     * @param string $query     SQL query
     * @return mixed            The result or FALSE
     */
    public function             get($query) {
        try {
            $ret = $this->pdo->query($query);
            if (!$ret)
                return false;
            return $ret->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            if (\Core\Site::get('debug', false))
                echo "PDO: SQL error: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Return the last inserted ID
     *
     * @return string           Last inserted ID
     */
    public function             lastId() {
        try {
            return $this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            if (\Core\Site::get('debug', false))
                echo "PDO: SQL error: " . $e->getMessage() . "\n";
            return false;
        }
    }
}
