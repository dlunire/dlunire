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

namespace DLUnire\Interfaces;

/**
 * Corre todo el proyecto
 */
interface ProjectInterface {
    /**
     * Corre todo el proyecto.
     *
     * @param bool $autoload_routes Si se cargan automáticamente las rutas en `routes/`
     *                              (mismo contrato que {@see \DLCore\Boot\Project::run}).
     * @return void
     */
    public static function run(bool $autoload_routes = true): void;
}
