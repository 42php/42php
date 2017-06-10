<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Drivers\Mail;

/**
 * Permet d'envoyer un mail
 *
 * Interface Factory
 * @package Drivers\Mail
 */
interface                       Factory {
    /**
     * Récupère une instance singleton du driver
     *
     * @param array $parameters Liste des paramètres
     *
     * @return mixed
     */
    public static function      getInstance($parameters = []);

    /**
     * Permet d'envoyer un mail
     *
     * @param string $to                Destinataire
     * @param string $from              Expéditeur
     * @param string $subject           Sujet
     * @param string $html              Contenu du mail
     * @param bool|string  $replyTo     Adresse de réponse ou FALSE
     * @param array $attachments        Liste des pièces jointes
     *
     * @return bool                     Le status d'envoi du mail
     */
    public function             send($to, $from, $subject, $html, $replyTo = false, $attachments = []);
}