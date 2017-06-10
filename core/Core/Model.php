<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;

/**
 * Handles models
 *
 * Available callbacks : onInit, onExport, onChange, onSave, onDelete, beforeSave
 *
 * Class Model
 * @package Core
 */
trait                           Model {
    use DotAccessor;

    /**
     * @var null|mixed $id      Document ID
     */
    public                      $id = null;

    /**
     * Model constructor.
     * @param null|mixed $id    ID
     * @param null|array $d     Data to preload
     */
    public function             __construct($id = null, $d = null) {
        if (isset(self::$structure))
            $this->__data = self::$structure;

        $this->load($id, $d);

        if (method_exists($this, 'onInit'))
            $this->onInit();
    }

    /**
     * Get the DB Collection instance
     *
     * @return mixed
     */
    public static function      collection() {
        return Db::getInstance()->__get(self::$collection);
    }

    /**
     * Load data from database
     *
     * @param null|mixed $id    ID to load
     * @param null|array $data  Data to preload
     * @return bool
     * @throws ModelException
     */
    public function             load($id = null, $data = null) {
        if (is_null($data) && !is_null($id)) {
            $data = self::collection()->findOne([
                '_id' => Db::id($id)
            ]);
            if (!$data)
                throw new ModelException("Document not found.");

            if (isset($data['id']))
                unset($data['id']);
        }

        if (!is_null($data) && !is_null($id)) {
            $this->id = $id;
            $this->__data = ArrayTools::recursiveMerge($this->__data, $data);
        }

        return true;
    }

    /**
     * Export the data.
     *
     * @return array
     */
    public function             export() {
        $d = $this->__data;
        $d['id'] = $this->id;

        if (method_exists($this, 'onExport'))
            return $this->onExport($d);

        return $d;
    }

    /**
     * Displays a JSON representation of this model
     *
     * @return string
     */
    public function             __toString() {
        return json_encode($this->export(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Save the model in DB
     *
     * @return mixed            The document ID
     */
    public function             save() {
        if (method_exists($this, 'beforeSave'))
            $this->beforeSave();

        $d = $this->__data;
        if (!is_null($this->id))
            $d['id'] = Db::id($this->id);

        self::collection()->save($d);

        $this->id = $d['id'];

        if (method_exists($this, 'onSave'))
            $this->onSave();

        return $this->id;
    }

    /**
     * Delete the model in database
     *
     * @return bool
     */
    public function             delete() {
        if (is_null($this->id))
            return false;

        if (method_exists($this, 'onDelete'))
            $this->onDelete();

        self::collection()->remove([
            'id' => Db::id($this->id)
        ]);
        $this->id = null;

        return true;
    }

    /**
     * Duplicate this model into another new model
     *
     * @return Model
     */
    public function             duplicate() {
        $d = $this->__data;
        if (isset($d['id']))
            unset($d['id']);
        return new self(null, $d);
    }

    /**
     * Find one document
     *
     * @param array $criteria   Search criteria
     * @return bool|Model
     */
    public static function      findOne($criteria = []) {
        $item = self::collection()->findOne($criteria);
        if ($item)
            return new self($item['id'], $item);
        return false;
    }

    /**
     * Find many documents
     *
     * @param array $criteria   Search criteria
     * @param bool $order       Order fields
     * @param bool $skip        Skip documents
     * @param bool $limit       Limit returned documents
     * @return ModelIterator
     */
    public static function      find($criteria = [], $order = false, $skip = false, $limit = false) {
        $items = self::collection()->find($criteria, [], $order, $skip, $limit);

        return new ModelIterator($items, get_called_class());
    }

    /**
     * Update models in database, without instanciating them
     *
     * @param array $criteria   Search criteria
     * @param array $newObject  Update query
     * @param bool $multiple    Determine if we can update many documents
     * @return mixed
     */
    public static function      update($criteria = [], $newObject = [], $multiple = false) {
        return self::collection()->update($criteria, $newObject, ['multiple' => $multiple]);
    }
}