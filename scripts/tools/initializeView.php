<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

use Core\Conf;
use Core\Session;

Conf::append('page.bottom', '<script type="text/javascript">
    api.setLang("' . Conf::get('lang') . '");
    api.setToken("' . Session::id() . '");
</script>');