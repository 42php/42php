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