<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                       Core;

/**
 * Can send emails
 *
 * Class Mail
 * @package Core
 */
class                           Mail {
    /**
     * Send an email
     *
     * @param string $to                To email address
     * @param string $from              From email address
     * @param string $subject           Subject
     * @param string $html              Email content
     * @param bool|string $replyTo      Reply email address
     * @param array $attachments        Attachments
     *
     * @return bool                     TRUE if the email is sent
     */
    public static function      send($to, $from, $subject, $html, $replyTo = false, $attachments = []) {
        $drivers = Site::get('mail', []);
        foreach ($drivers as $driver) {
            $factory = '\Drivers\Mail\\'. $driver['driver'] .'\\Factory';
            if (!class_exists($factory))
                continue;
            $o = $factory::getInstance(isset($driver['config']) ? $driver['config'] : []);
            $ret = $o->send($to, $from, $subject, $html, $replyTo, $attachments);
            if ($ret)
                return true;
        }
        return false;
    }
}