<?php

namespace Framework\Auth;

use DLRoute\Server\DLServer;
use DLCore\Config\Credentials;
use DLCore\Config\DLConfig;


/**
 * Valida las credenciales del usuario autenticado
 * 
 * @package Framework\Auth
 * 
 * @version 1.0.0 (release)
 * @author David E Luna M <contact@dlunire.pro>
 * @copyright 2024 David E Luna M
 * @license MIT
 */
final class SystemCredentials {
    use DLConfig;

    /**
     * Instanciade clase
     *
     * @var self|null
     */
    private static ?self $instance = null;

    /**
     * Credenciales tomadas a partir de las variables de entorno
     *
     * @var object|null
     */
    private static ?object $credentials = null;

    /**
     * Determina si el sistema está o no en modo producción.
     *
     * @var boolean
     */
    private static bool $is_production = false;

    private function __construct() {
        $this->parse_file();
        self::$credentials = $this->get_environments_as_object();

        /**
         * Credenciales de las variables de entorno
         * 
         * @var Credentials $credentials
         */
        $credentials = $this->get_credentials();

        self::$is_production = $credentials->is_production();
    }

    /**
     * Carga las credenciales y sesiones del sistema
     *
     * @return void
     */
    public static function load() {
        self::get_instance();

        ini_set('session.cookie_httponly', 1);
        session_set_cookie_params(3600 * 24 * 30 * 6);
        session_start();

        self::validate_time();
        self::validate_origin();
    }

    /**
     * Valida el origen de la petición
     *
     * @return void
     */
    private static function validate_origin(): void {
        /**
         * Autenticador
         * 
         * @var AuthBase $auth
         */
        $auth = AuthBase::get_instance();

        /**
         * Datos de la sesión
         * 
         * @var array $session_data
         */
        $session_data = $auth->get_session_value('auth');

        if (!is_array($session_data)) {
            $_SESSION['auth'] = null;
            return;
        }

        if (!array_key_exists('user_agent', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }

        if (!array_key_exists('hostname', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }

        if (!array_key_exists('http_host', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }

        if (!array_key_exists('server_software', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }

        if (!array_key_exists('port', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }

        if (!array_key_exists('expire_time', $session_data)) {
            $_SESSION['auth'] = null;
            return;
        }

        /**
         * Agente de usuario almacenada en la sesión
         * 
         * @var string $user_agent
         */
        $user_agent = $session_data['user_agent'];

        if ($user_agent !== DLServer::get_user_agent()) {
            $_SESSION['auth'] = null;
            return;
        }

        /**
         * Nombre de host almacenada en la sesión
         * 
         * @var string $hostname
         */
        $hostname = $session_data['hostname'];

        if ($hostname !== DLServer::get_hostname()) {
            $_SESSION['auth'] = null;
            return;
        }

        /**
         * Nombre HTTP HOST almacenada en la sesión
         * 
         * @var string $http_host
         */
        $http_host = $session_data['http_host'];

        if ($http_host !== DLServer::get_http_host()) {
            $_SESSION['auth'] = null;
            return;
        }

        /**
         * Software del servidor almacenada en la sesión
         * 
         * @var string $server_software
         */
        $server_software = $session_data['server_software'];

        if ($server_software !== DLServer::get_server_software()) {
            $_SESSION['auth'] = null;
            return;
        }

        /**
         * Número de puerto almacenada en la variable de sesión
         * 
         * @var integer $port
         */
        $port = (int) $session_data['port'];

        if ($port !== DLServer::get_port()) {
            $_SESSION['auth'] = null;
            return;
        }

        self::validate_token();
    }

    /**
     * Mediante un token enviado previamente al cliente, valida que el 
     * origen de la petición sea válida.
     * 
     * El token es de un solo uso.
     *
     * @return void
     */
    private static function validate_token(): void {

        /**
         * Autenticador
         * 
         * @var AuthBase $auth
         */
        $auth = AuthBase::get_instance();

        /**
         * Token aleatorio de autenticación envaida previamente al cliente
         * 
         * @var string $token
         */
        $token = $_COOKIE['__auth__'] ?? null;

        if (is_null($token)) {
            $_SESSION['auth'] = null;
            return;
        }

        if (!hash_equals($token, $auth->get_session_value('__auth__'))) {
            $_SESSION['auth'] = null;
            return;
        }

        static::update_token_session();
    }

    /**
     * Actualiza los tokens de la sesión
     *
     * @return void
     */
    private static function update_token_session(): void {
        /**
         * Nuevo token de sesión generado.
         * 
         * @var string $new_token
         */
        $new_token = static::generate_unique_token();

        if (self::is_token_update_required() || is_null($_SESSION['__auth__'])) {
            setcookie(
                '__auth__',
                $new_token,
                time() + 60 * 60 * 24 * 30 * 6,
                "/",
                DLServer::get_hostname(),
                self::$is_production,
                true
            );

            $_SESSION['__auth__'] = $new_token;

            header("DLUnire: {$new_token}");
            header("Framework: DLUnire");
        }
    }

    /**
     * Devuelve la fecha y hora actual del sistema en formato UNIX + 60 segundos o tiempo restante adicional.
     *
     * @return integer
     */
    private static function get_time(): int {

        /**
         * @var integer|null $time
         */
        $time = $_SESSION['token-time'] ?? null;

        if (is_null($time)) {
            $time = time() + 60;
            $_SESSION['token-time'] = $time;
        }

        return $time;
    }

    /**
     * Indica si la actualización del token es requerida tras alcanzar el tiempo de expiración.
     *
     * @return boolean
     */
    private static function is_token_update_required(): bool {
        /**
         * Fecha y hora actual del sistema + 60 segundos o tiempo restante.
         * 
         * @var integer $time
         */
        $time = self::get_time() - time();

        if ($time < 0) {
            $_SESSION['token-time'] = time() + 60;
        }

        return $time < 0;
    }

    /**
     * Devuelve un único token aleatorio
     *
     * @return string
     */
    private static function generate_unique_token(): string {
        /**
         * Bytes aleatorios
         * 
         * @var string $bytes
         */
        $bytes = random_bytes(128);

        /**
         * Token nuevo
         * 
         * @var string $new_token
         */
        $new_token = bin2hex($bytes);

        return $new_token;
    }

    /**
     * Devuelve el tiempo de vida de la sesión
     *
     * @return int
     */
    private static function get_lifetime(): int {
        /**
         * Tiempo de vida definida en la variable de sesión
         * 
         * @var integer|null $lifetime
         */
        $lifetime = null;

        if (isset(self::$credentials->DL_LIFETIME)) {
            $lifetime = (int) self::$credentials->DL_LIFETIME['value'] ?? 0;
        }

        if (is_null($lifetime)) {
            $lifetime = 3600;
        }

        return time() + $lifetime;
    }

    /**
     * Valida el tiempo de expiración y lo actualiza si no se ha vencido
     *
     * @return void
     */
    private static function validate_time(): void {
        /**
         * Tiempo de vida de la sesión del usuario.
         * 
         * @var integer $time;
         */
        $time = self::get_lifetime();

        /**
         * Datos de autenticación del usuario
         * 
         * @var array|null $auth
         */
        $auth = &$_SESSION['auth'] ?? null;

        if (is_null($auth)) {
            return;
        }

        /**
         * Tiempo restante de expiración de la sesión
         * 
         * @var integer|null
         */
        $expire_time = &$auth['expire_time'] ?? null;

        if (is_null($expire_time)) {
            $expire_time = $time;
        }

        /**
         * Tiempo transcurrido desde que se inició la sesión o se actualizó el tiempo de expiración
         * 
         * @var string $elapsed_time
         */
        $elapsed_time = $time - $expire_time;

        /**
         * Tiempo restante de la sesión
         * 
         * @var integer $remaining_time
         */
        $remaining_time = ($time - time()) - $elapsed_time;

        if ($remaining_time > 0) {
            $expire_time = $time;
            return;
        }

        $auth = '';

        setcookie('__auth__', null, time() - 60 * 60 * 30);
    }

    /**
     * Devuelve una instacia de clase siguiente el patrón singleton
     *
     * @return self
     */
    public static function get_instance(): self {
        if (!(self::$instance instanceof self)) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}
