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

namespace                           Drivers\Mail\System;

/**
 * Permet d'envoyer un mail depuis le serveur
 *
 * Class Factory
 * @package Drivers\Mail\System
 */
class                               Factory implements \Drivers\Mail\Factory {
    /**
     * @var null|Factory $singleton Contient une instance singleton de la factory
     */
    private static                  $singleton = null;

    /**
     * Retourne une instance singleton
     *
     * @param array $parameters Liste des paramètres
     *
     * @return Factory
     */
    public static function          getInstance($parameters = []) {
        if (is_null(self::$singleton)) {
            self::$singleton = new self();
        }
        return self::$singleton;
    }

    /**
     * Permet d'envoyer un mail
     *
     * @param string $to            Destinataire
     * @param string $from          Expéditeur
     * @param string $subject       Sujet
     * @param string $html          Contenu du mail
     * @param bool|string $replyTo  Adresse de réponse ou FALSE
     * @param array $attachments    Liste des pièces jointes : ["mailName.txt" => "/path/to/file.txt"]
     *
     * @return bool                 Le status d'envoi du mail
     */
    public function                 send($to, $from, $subject, $html, $replyTo = false, $attachments = []) {
        if ($replyTo === false)
            $replyTo = $from;

        $boundary = "_----------=_mailpart_".md5(uniqid(rand()));
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'From: '.$from . "\r\n";
        $headers .= 'Reply-To: ' . $replyTo . "\r\n";
        $headers .= 'X-Sender: <'.Conf::get('mail.x-sender', $_SERVER['HTTP_HOST']).'>'."\r\n";
        $headers .= 'Content-Transfer-Encoding: 8bit'."\r\n";
        $headers .= 'Content-type: multipart/alternative; boundary="'.$boundary.'"; charset="utf-8"' . "\r\n";

        $text = new \Html2Text\Html2Text($html);
        $textstr = $text->getText();

        $mailContent = '';

        $mailContent .= "--".$boundary."\r\n";
        $mailContent .= "Content-Type: text/plain\r\n";
        $mailContent .= "charset=\"utf-8\"\r\n";
        $mailContent .= "Content-Transfer-Encoding: 8bit\r\n\r\n";
        $mailContent .= $textstr;

        $mailContent .= "\r\n\r\n--".$boundary."\r\n";
        $mailContent .= "Content-Type: text/html; charset=\"utf-8\"; \r\n";
        $mailContent .= "Content-Transfer-Encoding: 8bit;\r\n\r\n";
        $mailContent .= $html;

        foreach ($attachments as $innername => $file) {
            $mailContent .= "\r\n\r\n--" . $boundary . "\r\n";
            $mailContent .= "Content-Type: application/octet-stream; name=\"".$innername."\"\r\n";
            $mailContent .= "Content-Transfer-Encoding: base64;\r\n";
            $mailContent .= "Content-Disposition: attachment;\r\n";
            $mailContent .= chunk_split(base64_encode(file_get_contents($file))) . "\r\n";
            $mailContent .= $html;
        }

        $mailContent .= "\r\n--".$boundary."--";

        $subject = html_entity_decode($subject);
        $subject = mb_encode_mimeheader(utf8_decode($subject), "UTF-8", "B");

        return mail($to, $subject, $mailContent, $headers);
    }

}