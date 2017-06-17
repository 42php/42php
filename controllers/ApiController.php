<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */
use Core\Api;

/**
 * Class ApiController
 */
class                   ApiController extends \Core\Controller {
    use Api_Auth;

    /**
     * Run API
     */
    public function     process() {
        $methods = get_class_methods($this);
        foreach ($methods as $method) {
            if (in_array($method, ['process', '__construct', 'run', 'exists']))
                continue;
            $this->$method();
        }

        Api::run(1);
    }
}