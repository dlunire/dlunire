<?php

namespace Boot;

use DLRoute\Requests\DLRoute;
use DLRoute\Server\DLServer;
use DLUnire\Interfaces\ProjectInterface;
use Framework\Auth\SystemCredentials;

/**
 * Corre todo el proyecto
 *
 * @package DLUnire
 *
 * @author David E Luna M <davidlunamontilla@gmail.com>
 * @copyright 2023–2026 David E Luna M
 * @license AGPL-3.0-or-later
 */
class Project implements ProjectInterface {

    /**
     * Incluye archivos PHP en función del directorio seleccionado
     *
     * @param string $folder
     * @return void
     */
    private static function includes(string $folder = "app/Helpers"): void {
        $root = DLServer::get_document_root();
        $dir = "{$root}/{$folder}";

        
        if (!file_exists($dir) || !is_dir($dir)) {
            return;
        }

        /**
         * Patrón de búsqueda de archivos PHP.
         * 
         * @var string
         */
        $search_files = self::clear_route($dir);

        /**
         * Array de archivos
         * 
         * @var array<string>
         */
        $filenames = glob($search_files);

        foreach ($filenames as $filename) {
            $filename = trim($filename);
            include $filename;
        }
    }

    /**
     * Depura las rutas de directorio.
     *
     * @param string $route
     * @return string
     */
    private static function clear_route(string $route): string {
        $route = trim($route);
        // Solo normaliza separadores; no tocar puntos (rompería rutas con ".")
        $route = preg_replace('#/+#', DIRECTORY_SEPARATOR, $route) ?? $route;
        $route = rtrim($route, "/\\");

        return "{$route}/*.php";
    }

    public static function run(): void {
        // Credenciales / sesión y .env.type antes de CORS (puede leer DL_CORS_ORIGINS)
        SystemCredentials::load();

        Authorizations::register_domain(self::cors_domains());
        Authorizations::init();

        self::includes();
        self::includes('app/Constants');
        self::includes("routes");

        DLRoute::execute();
    }

    /**
     * Hosts CORS permitidos desde `DL_CORS_ORIGINS` (lista separada por comas).
     * Por defecto: localhost y 127.0.0.1.
     *
     * @return list<string>
     */
    private static function cors_domains(): array {
        $defaults = ['localhost', '127.0.0.1'];

        try {
            $env = \DLCore\Config\Environment::get_instance();
            $raw = $env->get_env_value('DL_CORS_ORIGINS');
        } catch (\Throwable) {
            return $defaults;
        }

        if ($raw === null || trim($raw) === '') {
            return $defaults;
        }

        $parts = preg_split('/\s*,\s*/', trim($raw)) ?: [];
        $domains = [];

        foreach ($parts as $part) {
            $part = trim((string) $part);
            if ($part === '') {
                continue;
            }

            // Acepta host o URL completa; se registra solo el host.
            $part = (string) preg_replace('#^https?://#i', '', $part);
            $part = (string) preg_replace('#/.*$#', '', $part);
            $part = (string) preg_replace('#:\d+$#', '', $part);
            $part = strtolower(trim($part));

            if ($part !== '') {
                $domains[] = $part;
            }
        }

        $domains = array_values(array_unique($domains));

        return $domains !== [] ? $domains : $defaults;
    }
}