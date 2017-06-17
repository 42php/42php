<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

/**
 * Class SystemController
 */
class                                   SystemController extends \Core\Controller {
    /**
     * Displays 404 page
     */
    public function                     notFound() {
        \Core\Http::responseCode(404);
        return '';
    }

    /**
     * Displays favicon
     */
    public function                     favicon() {
        $path = ROOT . \Core\Site::get('page.favicon');
        header("Content-Type: " . mime_content_type($path));
        return file_get_contents($path);
    }
}