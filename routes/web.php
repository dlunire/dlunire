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

use DLRoute\Requests\DLRoute;
use DLUnire\Controllers\WelcomeController;
use DLUnire\Controllers\ProductsController;

/**
 * Páginas del skeleton publicadas con el framework.
 * Comercial (tienda): https://store.dlunire.dev/
 */
DLRoute::get('/privacy-policy', [WelcomeController::class, 'privacy_policy']);

DLRoute::get('/products', [ProductsController::class, 'index']);

# Una ruta con definición de tipo, donde `UUID` es un
# tipo de dato que representa un identificador único universal (UUID):
DLRoute::get('/products/{id}', [ProductsController::class, 'show'])
    ->filter_by_type([
        'id' => 'uuid',
    ]);
