<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Drivers\Payment;

/**
 * Gère les paiements
 *
 * Interface Factory
 * @package Drivers\Payment
 */
interface                       Factory {
    /**
     * Récupère une instance singleton du driver
     *
     * @return mixed
     */
    public static function      getInstance();

    /**
     * Permet de demander l'autorisation à la banque pour un paiement
     *
     * @param string $reference Référence du paiement
     * @param float $amount     Montant
     * @param string $cardno    Numéro de carte
     * @param string $exp       Date d'expiration (format YYYY/MM)
     * @param string $cvc       Cryptogramme de sécurité
     *
     * @return mixed            Les codes d'autorisation
     */
    public function             auth($reference, $amount, $cardno, $exp, $cvc);

    /**
     * Permet de demander la capture d'un paiement précédemment autorisé
     *
     * @param array $authcodes  Codes d'autorisation précédemment obtenus avec auth()
     *
     * @return mixed            Retours de la banque
     */
    public function             capture($authcodes = []);

    /**
     * Permet de demander l'autorisation et la capture immédiate à la banque pour un paiement
     *
     * @param string $reference Référence du paiement
     * @param float $amount     Montant
     * @param string $cardno    Numéro de carte
     * @param string $exp       Date d'expiration (format YYYY/MM)
     * @param string $cvc       Cryptogramme de sécurité
     *
     * @return mixed            Les codes d'autorisation
     */
    public function             authcapture($reference, $amount, $cardno, $exp, $cvc);

    /**
     * Permet de rembourser un ancien paiement complètement ou partiellement
     *
     * @param string $reference Référence du paiement
     * @param mixed $amount     Montant à rembourser, ou FALSE pour un remboursement intégral
     *
     * @return mixed            Retours de la banque
     */
    public function             refund($reference, $amount = false);
}