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

namespace                   Drivers\Database;

/**
 * Interface Factory
 * @package Drivers\Database
 */
interface                   Collection {
    /**
     * Insère un document en base
     *
     * @param array $data   Le document à insérer
     * @return mixed        ID du document inséré
     */
    public function         insert(&$data);

    /**
     * Met à jour un ou plusieurs documents
     *
     * @param array $clause     La requête, au format MongoDB
     * @param array $data       Les données à insérer
     * @param array $options    Options de mise à jour
     * @return mixed            Le nombre de documents mis à jour
     */
    public function         update($clause = [], $data = [], $options = []);

    /**
     * Enregistre un document (upsert)
     *
     * @param array $data   Le document à sauvegarder
     * @return mixed        ID du document sauvegardé
     */
    public function         save(&$data);

    /**
     * Trouve plusieurs documents
     *
     * @param array $clause     La requête, au format MongoDB
     * @param array $fields     Les champs à retourner
     * @param array $sort       Champs de tris
     * @param bool|int $skip    Nombre de documents à ignorer
     * @param bool|int $limit   Nombre de documents à retourner
     * @return mixed            Un curseur de lecture sur les documents
     */
    public function         find($clause = [], $fields = [], $sort = [], $skip = false, $limit = false);

    /**
     * Trouve un document
     *
     * @param array $clause     La requête, au format MongoDB
     * @param array $fields     Les champs à retourner
     * @return mixed            Le document
     */
    public function         findOne($clause = [], $fields = []);

    /**
     * Supprime un ou plusieurs documents
     *
     * @param array $clause     La requête, au format MongoDB
     * @param array $options    Options de suppression
     * @return mixed            Le nombre de documents supprimés
     */
    public function         remove($clause = [], $options = []);
}