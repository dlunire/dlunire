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

use DLRoute\Routes\ResourceManager;
use DLRoute\Routes\RouteDebugger;
use DLRoute\Server\DLServer;

if (!function_exists('asset')) {

    /**
     * Devuelve la ruta HTTP de un recurso.
     *
     * @param string $uri URI del recurso.
     * @return string
     */
    function asset(string $uri): string {

        $uri = "public/{$uri}";

        /**
         * URL completa del archivo
         * 
         * @var string
         */
        $url = ResourceManager::asset($uri);

        return trim($url);
    }
}

if (!function_exists('route')) {

    /**
     * Permite establecer la ruta HTTP tomando en cuenta que el proyecto puede estar
     * en cualquier _subdirectorio_, si fuese el caso.
     *
     * @param string $uri
     * @param boolean $extension Opcional. Indica si la ruta a la que apunta lleva extensión de archivo
     * @return string
     */
    function route(string $uri, bool $extension = false) {
        
        if (!$extension) {
            $uri = RouteDebugger::dot_to_slash($uri);
        }

        $uri = trim($uri);
        $uri = ltrim($uri, "\/");

        /**
         * URL Base de la aplicación
         * 
         * @var string $url
         */
        $url = DLServer::get_base_url();
        
        $url = rtrim($url, "\/");
        $url = "{$url}/{$uri}";
        
        return trim($url);
    }
}
