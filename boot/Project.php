<?php

/**
 * DLUnire
 * Copyright (C) 2026 David E Luna M
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * @license AGPL-3.0-or-later
 */

namespace Boot;

use DLCore\Core\Parsers\Slug\Path;
use DLRoute\Requests\DLRoute;
use DLRoute\Server\DLServer;
use DLUnire\Interfaces\ProjectInterface;
use Framework\Auth\SystemCredentials;

/**
 * Punto de entrada del skeleton: combina el arranque de **DLCore**
 * (`Path`, includes de Constants/Helpers/routes, `$autoload_routes`)
 * con la lógica propia de **DLUnire** (`SystemCredentials`, CORS desde
 * `DL_CORS_ORIGINS`, `clear_route` / includes por carpeta).
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
     * (API del skeleton; no elimina puntos del path).
     *
     * @param string $folder Ruta relativa al document root (p. ej. `app/Helpers`)
     * @return void
     */
    private static function includes(string $folder = 'app/Helpers'): void {
        $root = DLServer::get_document_root();
        $dir = $root . DIRECTORY_SEPARATOR . str_replace(['/', '\\'], DIRECTORY_SEPARATOR, $folder);

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
         * @var array<int, string>|false
         */
        $filenames = glob($search_files);

        if (!is_array($filenames)) {
            return;
        }

        foreach ($filenames as $filename) {
            $filename = trim((string) $filename);
            if ($filename === '' || !is_file($filename)) {
                continue;
            }
            include $filename;
        }
    }

    /**
     * Depura las rutas de directorio para un glob `*.php`.
     *
     * Solo normaliza separadores; no toca puntos (rutas con "." válidas).
     *
     * @param string $route
     * @return string
     */
    private static function clear_route(string $route): string {
        $route = trim($route);
        $route = preg_replace('#/+#', DIRECTORY_SEPARATOR, $route) ?? $route;
        $route = rtrim($route, "/\\");

        return $route . DIRECTORY_SEPARATOR . '*.php';
    }

    /**
     * Igual que {@see \DLCore\Boot\Project}: asegura el directorio y
     * hace `require_once` de cada `*.php` (vía `Path` del núcleo).
     *
     * @param string $dir Ruta lógica tipo `/app/Helpers` o `/routes`
     * @return void
     */
    private static function auto_include_dir(string $dir): void {
        Path::ensure_dir($dir);

        /** @var non-empty-string $path */
        $path = Path::resolve("{$dir}/*.php");

        /** @var array<int, string>|false $includes */
        $includes = glob($path);

        if (!\is_array($includes)) {
            return;
        }

        foreach ($includes as $include) {
            if (!\is_string($include) || !file_exists($include)) {
                continue;
            }

            require_once $include;
        }
    }

    /**
     * Constantes globales (`app/Constants`) — fase del núcleo.
     */
    private static function include_constants_dir(): void {
        self::auto_include_dir('/app/Constants');
    }

    /**
     * Helpers globales (`app/Helpers`) — fase del núcleo.
     */
    private static function include_helper_dir(): void {
        self::auto_include_dir('/app/Helpers');
    }

    /**
     * Rutas HTTP (`routes/`) — fase del núcleo.
     */
    private static function include_routes_dir(): void {
        self::auto_include_dir('/routes');
    }

    /**
     * Arranque de la aplicación.
     *
     * Orden:
     * 1. **Skeleton:** `SystemCredentials::load()` (`.env.type`, sesión)
     * 2. **Skeleton:** CORS desde `DL_CORS_ORIGINS` + `Authorizations::init()`
     * 3. **Núcleo (DLCore):** Constants → Helpers → routes (si `$autoload_routes`)
     * 4. **Núcleo / HTTP:** `DLRoute::execute()`
     *
     * La carga automática de `routes/` puede desactivarse con
     * `$autoload_routes = false` (mismo contrato que `DLCore\Boot\Project::run`).
     *
     * @param bool $autoload_routes Si debe incluirse `routes/*.php` antes del despacho
     * @return void
     */
    public static function run(bool $autoload_routes = true): void {
        // --- Skeleton (DLUnire) ---
        SystemCredentials::load();

        Authorizations::register_domain(self::cors_domains());
        Authorizations::init();

        // --- Núcleo (misma secuencia que DLCore\Boot\Project) ---
        self::include_constants_dir();
        self::include_helper_dir();

        if ($autoload_routes) {
            self::include_routes_dir();
        }

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

        if ($raw === null || trim((string) $raw) === '') {
            return $defaults;
        }

        $parts = preg_split('/\s*,\s*/', trim((string) $raw)) ?: [];
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
