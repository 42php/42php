<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                           Core;

/**
 * Handle a REST API
 *
 * Class Api
 * @package Core
 */
class                               Api {
    /**
     * @var array $getMethods       List of GET methods
     */
    private static                  $getMethods = [];
    /**
     * @var array $postMethods      List of POST methods
     */
    private static                  $postMethods = [];
    /**
     * @var array $putMethods       List of PUT methods
     */
    private static                  $putMethods = [];
    /**
     * @var array $patchMethods     List of PATCH methods
     */
    private static                  $patchMethods = [];
    /**
     * @var array $deleteMethods    List of DELETE methods
     */
    private static                  $deleteMethods = [];
    /**
     * @var int $returnCode         API return code
     */
    private static                  $returnCode = 200;

    /**
     * Throws an API error
     *
     * @param int $code             Error code
     * @param bool|string $message  Error message
     * @param bool $noException     Send directly json payload
     *
     * @throws ApiException
     */
    public static function          error($code, $message = false, $noException = false) {
        if ($message === false) {
            $messages = json_decode(file_get_contents(ROOT . '/config/api.errors.json'), true);
            if (!$messages || !isset($messages[$code]))
                $message = 'Unknown error.';
            else
                $message = $messages[$code];
        }
        if ($noException) {
            self::send([
                'error' => $code,
                'error_message' => $message
            ], $code);
        }
        throw new ApiException($message, $code);
    }

    /**
     * Define API return code
     *
     * @param $code
     */
    public static function          code($code) {
        self::$returnCode = $code;
    }

    /**
     * Adds a GET method
     *
     * @param string $path          Path
     * @param callable $callback    Callback
     */
    public static function          get($path, callable $callback) {
        self::$getMethods[$path] = $callback;
    }

    /**
     * Adds a POST method
     *
     * @param string $path          Path
     * @param callable $callback    Callback
     */
    public static function          post($path, callable $callback) {
        self::$postMethods[$path] = $callback;
    }

    /**
     * Adds a PUT method
     *
     * @param string $path          Path
     * @param callable $callback    Callback
     */
    public static function          put($path, callable $callback) {
        self::$putMethods[$path] = $callback;
    }

    /**
     * Adds a DELETE method
     *
     * @param string $path          Path
     * @param callable $callback    Callback
     */
    public static function          delete($path, callable $callback) {
        self::$deleteMethods[$path] = $callback;
    }

    /**
     * Adds a PATCH method
     *
     * @param string $path          Path
     * @param callable $callback    Callback
     */
    public static function          patch($path, callable $callback) {
        self::$patchMethods[$path] = $callback;
    }

    /**
     * Send the data payload
     *
     * @param mixed $data           Data to send
     * @param bool|int $code        Return code
     */
    public static function          send($data = [], $code = false) {
        if ($code !== false)
            self::code($code);
        if (!is_array($data))
            $data = ['data' => $data];
        ob_end_clean();
        http_response_code(self::$returnCode);
        header('Content-Type: application/json');
        header('X-Token: ' . Session::id());
        echo json_encode($data, \JSON_UNESCAPED_UNICODE);
        Session::save();
        die();
    }

    /**
     * Find the most optimized path
     *
     * @param array $config         Route list
     * @param bool $argv            Current Argv
     *
     * @return array|bool           Returns the most optimized path
     */
    public static function          route($config, $argv = false) {
        uksort($config, function($a, $b) {
            if (strlen($a) == strlen($b))
                return 0;
            return (strlen($a) > strlen($b)) ? -1 : 1;
        });
        if (!$argv)
            global $argv;
        foreach ($config as $path => $value) {
            $path = Argv::parse($path);
            $good = true;
            $cpt = -1;
            $params = array();
            while (isset($path[++$cpt])) {
                if (!isset($argv[$cpt]) || (isset($argv[$cpt]) && $argv[$cpt] != $path[$cpt] && $path[$cpt] != '*'))
                    $good = false;
                if (isset($path[$cpt], $argv[$cpt]) && $path[$cpt] == '*')
                    $params[] = $argv[$cpt];
            }
            if ($good) {
                return [
                    'path'   => '/'.implode('/', $path),
                    'offset' => sizeof($path),
                    'selected' => $value,
                    'params' => $params
                ];
            }
        }
        return false;
    }

    /**
     * Run API and send payload to user.
     *
     * @param int $offset           Number of Argv parameters to ignore
     */
    public static function          run($offset = 0) {
        $headers = Http::headers();
        header('Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, PATCH, DELETE');
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header('Access-Control-Allow-Origin: *');
            die();
        }

        $allowed = false;
        if (isset($headers['X-App-Key'])) {
            header('Access-Control-Allow-Origin: *');

            $app = Db::getInstance()->apikeys->findOne([
                'key' => $headers['X-App-Key']
            ]);
            if ($app) {
                Conf::set('api.device', $app['name']);
                $allowed = true;
            }
        }
        if (!$allowed)
            self::error(401, "This application isn't allowed.", true);

        $lang = i18n::$defaultLanguage;
        if (isset($headers['X-Lang']) && in_array($headers['X-Lang'], i18n::$acceptedLanguages))
            $lang = $headers['X-Lang'];
        i18n::setLang($lang);

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                $_REQUEST = $_GET;
                break;
            case 'POST':
            case 'PUT':
            case 'PATCH':
                $json = file_get_contents('php://input');
                $params = json_decode($json, true);
                if (is_null($params))
                    self::error(421, false, true);
                $_REQUEST = $params;
                break;
            case 'DELETE':
                $_REQUEST = [];
                break;
            default:
                self::error(405, false, true);
                break;
        }
        try {
            global $argv;
            $ret = self::local($_SERVER['REQUEST_METHOD'], '/' . implode('/', array_slice($argv, $offset)), $_REQUEST);
            self::send($ret);
        } catch (ApiException $e) {
            self::send([
                'error' => $e->getCode(),
                'error_message' => $e->getMessage()
            ], $e->getCode());
        }
    }

    /**
     * Execute API locally
     *
     * @param string $method        Method
     * @param string $path          Path
     * @param array $data           Parameters
     *
     * @return mixed                API return
     */
    public static function          local($method, $path, $data = []) {
        $oldRequest = $_REQUEST;
        $_REQUEST = $data;
        $argv = Argv::parse($path);
        $var = strtolower($method) . 'Methods';
        $functions = self::$$var;
        $selected = self::route($functions, $argv);
        if ($selected) {
            $newArgv = $selected['params'];
            foreach ($argv as $i => $value) {
                if ($i >= $selected['offset']) {
                    $newArgv[] = $value;
                }
            }
            $result = call_user_func_array($selected['selected'], $newArgv);
            $_REQUEST = $oldRequest;
            return $result;
        }
        self::error(405);
        return false;
    }

    /**
     * Check if all fields are submitted
     * If one of the fields aren't in the parameters, returns a 400 error.
     *
     * @param array|string $fields  Field list
     */
    public static function          needFields($fields) {
        if (!is_array($fields))
            $fields = [$fields];
        foreach ($fields as $field) {
            if (!isset($_REQUEST[$field]))
                self::error(400, _t("The field '%s' is required.", [$field]));
        }
    }
}