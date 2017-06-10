<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;

/**
 * Permet d'itérer sur des modèles en économisant l'empreinte mémoire
 *
 * Class ModelIterator
 * @package Core
 */
class                           ModelIterator implements \Iterator {
    /**
     * @var int $position       Position du curseur
     */
    private                     $position = 0;

    /**
     * @var mixed $data         Données de l'itérateur : peut être un tableau multi-dimensionnel ou un \Iterator
     */
    private $data = null;

    /**
     * @var null|string $model  Nom du modèle
     */
    private $model = null;

    /**
     * @var mixed $fields       Détermine si le modèle est complet ou pas
     */
    private $fields = null;

    /**
     * @var mixed $curr         Données courantes
     */
    private $curr = null;

    /**
     * Instancie un Model
     *
     * @param array $data       Données du modèle
     *
     * @return mixed            Le modèle instancié
     */
    public function             factory($data) {
        $m = '\\'.$this->model;
        return new $m($data['id'], $data);
    }

    /**
     * ModelIterator constructor.
     *
     * @param mixed $data       Données de l'itérateur : peut être un tableau multi-dimensionnel ou un \Iterator
     * @param string $modelName Nom du modèle
     * @param mixed $fields     Détermine si le modèle est complet ou pas
     */
    public function             __construct($data, $modelName) {
        $this->data = $data;
        $this->model = $modelName;
        $this->position = 0;
    }

    /**
     * Rembobine le curseur
     */
    public function             rewind() {
        if ($this->data instanceof \Iterator)
            $this->data->rewind();
        else
            $this->position = 0;
    }

    /**
     * Retourne le modèle courant
     *
     * @return mixed            Le modèle courant instancié
     */
    public function             current() {
        if ($this->data instanceof \Iterator)
            return $this->factory($this->data->current());
        return $this->factory($this->data[$this->position]);
    }

    /**
     * Retourne la clé courante
     *
     * @return mixed            La clé courante
     */
    public function             key() {
        if ($this->data instanceof \Iterator)
            return $this->data->key();
        return $this->position;
    }

    /**
     * Fait avancer le curseur
     */
    public function             next() {
        if ($this->data instanceof \Iterator)
            $this->curr = $this->data->getNext();
        else
            ++$this->position;
    }

    /**
     * Détermine si le curseur est valide
     *
     * @return bool
     */
    public function             valid() {
        if ($this->data instanceof \Iterator)
            return $this->data->valid();
        return isset($this->data[$this->position]);
    }

    /**
     * Retourne le nombre d'éléments dans le curseur
     *
     * @return int              Le nombre d'éléments dans le curseur
     */
    public function             count() {
        if ($this->data instanceof \Iterator)
            return $this->data->count();
        return sizeof($this->data);
    }

    /**
     * Exporte toutes les données des modèles sous la forme d'un tableau multi-dimensionnel
     *
     * @return array            Les données des modèles
     */
    public function             export() {
        $ret = [];
        foreach ($this->data as $d) {
            $d = $this->factory($d);
            $ret[] = $d->export();
        }
        return $ret;
    }
}