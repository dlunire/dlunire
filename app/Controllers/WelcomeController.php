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

namespace DLUnire\Controllers;

use Framework\Config\Controller;

/**
 * Bienvenida del framework (GET /).
 * Contenido comercial (tienda, GET /): https://store.dlunire.dev/
 */
final class WelcomeController extends Controller {

    public function index(): string {
        return view('welcome', [
            'button_type' => 'button--login',
            'label' => 'Realizar una prueba',
            'token' => $this->get_random_token(),
        ]);
    }
}
