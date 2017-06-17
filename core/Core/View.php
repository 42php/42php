<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                   Core;

/**
 * Handles view generation
 *
 * Class View
 * @package Core
 */
class                       View {
    /**
     * Check if a view file exists
     *
     * @param string $viewName View file name. Can have slashes to delimit folders.
     * @param string $folder   The folder where the view is searched
     *
     * @return bool TRUE if the view file exists
     */
    public static function  exists($viewName, $folder) {
        return file_exists($folder.$viewName.'.php');
    }

    /**
     * Render a view file.
     *
     * @param string $viewName View file name. Can have slashes to delimit folders.
     * @param array $params Variables to pass to the view.
     * @param bool $generateHeader Defines if this method should render a HTML full body.
     * @param string $folder The folder where the view is searched
     *
     * @return string The rendered view.
     */
    private static function renderFile($viewName, $params, $generateHeader, $folder) {
        $folder = rtrim($folder, '/') . '/';
        if (!self::exists($viewName, $folder))
            return '';
        extract($params);
        ob_start();
        if ($generateHeader && file_exists(ROOT.'/views/system/htmlheader.php'))
            include ROOT . '/views/system/htmlheader.php';
        include $folder.$viewName.'.php';
        if ($generateHeader && file_exists(ROOT.'/views/system/htmlfooter.php'))
            include ROOT . '/views/system/htmlfooter.php';
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }

    /**
     * Renders a view file with the HTML full body.
     *
     * @param string $viewName View file name. Can have slashes to delimit folders.
     * @param array $params Variables to pass to the view.
     * @param string|bool $folder The folder where the view is searched
     *
     * @return string The rendered view.
     */
    public static function  render($viewName, $params = [], $folder = false) {
        if ($folder === false)
            $folder = ROOT . '/views/';
        return self::renderFile($viewName, $params, true, $folder);
    }

    /**
     * Renders a view file without the HTML full body. Useful to render a view inside another view.
     *
     * @param string $viewName View file name. Can have slashes to delimit folders.
     * @param array $params Variables to pass to the view.
     * @param string|bool $folder The folder where the view is searched
     *
     * @return string The rendered view.
     */
    public static function  partial($viewName, $params = [], $folder = false) {
        if ($folder === false)
            $folder = ROOT . '/views/';
        return self::renderFile($viewName, $params, false, $folder);
    }
}