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

namespace                       Drivers\Database\PDO;

/**
 * Gère la connexion à une base de données SQL via PDO
 *
 * Class Factory
 * @package Drivers\Database\PDO
 */
class                           Factory implements \Drivers\Database\Factory {
    /**
     * @var null|Factory $singleton Contient une instance singleton de la factory
     */
    private static              $singleton = null;

    /**
     * Retourne une instance singleton
     *
     * @return bool|Factory
     */
    public static function      getInstance() {
        \Core\Debug::trace();
        if (is_null(self::$singleton)) {
            try {
                $pdo = new \PDO(
                    \Core\Conf::get('database.config.dsn', 'mysql:host=localhost;dbname=42php'),
                    \Core\Conf::get('database.config.user', 'root'),
                    \Core\Conf::get('database.config.pass', '')
                );
                $pdo->exec("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
                self::$singleton = new self($pdo);
            } catch (\PDOException $e) {
                echo "PDO: " . $e->getMessage() . "\n";
                return false;
            }
        }
        return self::$singleton;
    }

    /**
     * @var null|\PDO           Contient l'objet PDO
     */
    private                     $pdo = null;

    /**
     * Factory constructor.
     * @param \PDO $pdo
     */
    public function             __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Déconnecte PDO
     */
    public function             close() {
        \Core\Debug::trace();
        $this->pdo = null;
        self::$singleton = null;
    }

    /**
     * Retourne une table
     *
     * @param string $k     Nom de la table
     * @return Collection
     */
    public function             __get($k) {
        \Core\Debug::trace();
        return new Collection($k, $this, $this->pdo);
    }

    /**
     * Appelle PDO::quote()
     *
     * @param string $value     Valeur
     * @return string           Valeur filtrée
     */
    public function             quote($value) {
        \Core\Debug::trace();
        try {
            return $this->pdo->quote($value);
        } catch (\PDOException $e) {
            if (Conf::get('debug', false))
                echo "PDO: SQL error: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Appelle PDO::exec()
     *
     * @param string $query     Requête SQL
     * @return int              Nombre de résultats affectés
     */
    public function             exec($query) {
        \Core\Debug::trace();
        try {
            return $this->pdo->exec($query);
        } catch (\PDOException $e) {
            if (Conf::get('debug', false))
                echo "PDO: SQL error: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Appelle PDO::query() et retourne l'ensemble des résultats
     *
     * @param string $query     Requête SQL
     * @return array|bool       L'ensemble des résultats ou FALSE
     */
    public function             query($query) {
        \Core\Debug::trace();
        try {
            $ret = $this->pdo->query($query);
            if (!$ret)
                return false;
            return $ret->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            if (Conf::get('debug', false))
                echo "PDO: SQL error: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Appelle PDO::query() et retourne le premier résultat
     *
     * @param string $query     Requête SQL
     * @return mixed            Le résultat sous la forme d'un tableau ou FALSE
     */
    public function             get($query) {
        \Core\Debug::trace();
        try {
            $ret = $this->pdo->query($query);
            if (!$ret)
                return false;
            return $ret->fetch(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            if (Conf::get('debug', false))
                echo "PDO: SQL error: " . $e->getMessage() . "\n";
            return false;
        }
    }

    /**
     * Retourne l'identifiant unique du dernier document inséré
     *
     * @return string           Dernier identifiant inséré
     */
    public function             lastId() {
        \Core\Debug::trace();
        try {
            return $this->pdo->lastInsertId();
        } catch (\PDOException $e) {
            if (Conf::get('debug', false))
                echo "PDO: SQL error: " . $e->getMessage() . "\n";
            return false;
        }
    }
}
