<?php
/**
 * @author      Guillaume Gagnaire <contact@42php.com>
 * @link        https://www.github.com/42php/42php
 * @license     https://opensource.org/licenses/mit-license.html MIT
 * @copyright   2015-2017 42php
 */

namespace                   Core;

/**
 * Class Http
 * @package Core
 */
class                       Http {
    /**
     * Sets the content-type of the response.
     *
     * @param string $type
     * @param string $charset
     */
    public static function  setContentType($type = 'text/html', $charset = 'utf-8') {
        header("Content-Type: $type; charset=$charset");
    }

    /**
     * Recode of the http_response_code() function. (Because only available on PHP 5.4+).
     *
     * @param null|int $code Return code.
     *
     * @return int|mixed|null
     */
    public static function  responseCode($code = NULL) {
        if ($code !== NULL) {
            switch ($code) {
                case 100: $text = 'Continue'; break;
                case 101: $text = 'Switching Protocols'; break;
                case 200: $text = 'OK'; break;
                case 201: $text = 'Created'; break;
                case 202: $text = 'Accepted'; break;
                case 203: $text = 'Non-Authoritative Information'; break;
                case 204: $text = 'No Content'; break;
                case 205: $text = 'Reset Content'; break;
                case 206: $text = 'Partial Content'; break;
                case 300: $text = 'Multiple Choices'; break;
                case 301: $text = 'Moved Permanently'; break;
                case 302: $text = 'Moved Temporarily'; break;
                case 303: $text = 'See Other'; break;
                case 304: $text = 'Not Modified'; break;
                case 305: $text = 'Use Proxy'; break;
                case 400: $text = 'Bad Request'; break;
                case 401: $text = 'Unauthorized'; break;
                case 402: $text = 'Payment Required'; break;
                case 403: $text = 'Forbidden'; break;
                case 404: $text = 'Not Found'; break;
                case 405: $text = 'Method Not Allowed'; break;
                case 406: $text = 'Not Acceptable'; break;
                case 407: $text = 'Proxy Authentication Required'; break;
                case 408: $text = 'Request Time-out'; break;
                case 409: $text = 'Conflict'; break;
                case 410: $text = 'Gone'; break;
                case 411: $text = 'Length Required'; break;
                case 412: $text = 'Precondition Failed'; break;
                case 413: $text = 'Request Entity Too Large'; break;
                case 414: $text = 'Request-URI Too Large'; break;
                case 415: $text = 'Unsupported Media Type'; break;
                case 421: $text = 'Bad mapping'; break;
                case 500: $text = 'Internal Server Error'; break;
                case 501: $text = 'Not Implemented'; break;
                case 502: $text = 'Bad Gateway'; break;
                case 503: $text = 'Service Unavailable'; break;
                case 504: $text = 'Gateway Time-out'; break;
                case 505: $text = 'HTTP Version not supported'; break;
                default:
                    exit('Unknown http status code "' . htmlentities($code) . '"');
                    break;
            }
            $protocol = (isset($_SERVER['SERVER_PROTOCOL']) ? $_SERVER['SERVER_PROTOCOL'] : 'HTTP/1.0');
            header($protocol . ' ' . $code . ' ' . $text);
            $GLOBALS['http_response_code'] = $code;
        } else {
            $code = (isset($GLOBALS['http_response_code']) ? $GLOBALS['http_response_code'] : 200);
        }
        return $code;
    }

    /**
     * Throw a 404 Not Found error.
     */
    public static function  notFound() {
        self::responseCode(404);
        die();
    }

    /**
     * List all the request HTTP headers. Similar to getallheaders().
     * (Because not available on nginx environnements)
     *
     * @return array All the request HTTP headers.
     */
    public static function  headers() {
        $headers = [];
        foreach ($_SERVER as $name => $value) {
            if (substr($name, 0, 5) == 'HTTP_') {
                $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
            }
        }
        return $headers;
    }

    /**
     * Returns the base URL of the current request. Ex: http://www.foo.bar
     *
     * @return string The base URL.
     */
    public static function  baseUrl() {
        $pageUrl = 'http';
        if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {
            $pageUrl .= "s";
        }
        $pageUrl .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageUrl .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"];
        } else {
            $pageUrl .= $_SERVER["SERVER_NAME"];
        }
        return $pageUrl;
    }

    /**
     * Returns the current URL. Ex: http://www.foo.bar/foo/bar
     *
     * @return string
     */
    public static function  url() {
        $pageUrl = self::baseUrl();
        $pageUrl .= $_SERVER['REQUEST_URI'];
        return $pageUrl;
    }
}