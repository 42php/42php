<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

/**
 * Main autoloader
 *
 * Replaces namespaces and underscores with folders.
 * The files can be located in these four folders : core, controllers, models, vendor
 *
 * Examples : Class name -> Called file
 *  - \Foo\Bar      -> /{folder}/Foo/Bar.php
 *  - \Foo\Bar_Foo  -> /{folder}/Foo/Bar/Foo.php
 *  - FooBar        -> /{folder}/FooBar.php
 *  - Foo_Bar       -> /{folder}/Foo/Bar.php
 */
spl_autoload_register(function($class) {
    $folders = ['core', 'controllers', 'models', 'vendor'];
    $class = str_replace(['_', '\\'], '/', $class);
    foreach ($folders as $folder) {
        $file = implode('/', [
            ROOT,
            $folder,
            $class . '.php'
        ]);
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});