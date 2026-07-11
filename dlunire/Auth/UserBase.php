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

namespace Framework\Auth;

use DLRoute\Server\DLHost;
use DLRoute\Server\DLServer;
use DLCore\Auth\DLAuth;
use DLCore\Auth\DLAuthOptions;
use DLCore\Auth\DLCookie;
use DLCore\Auth\DLUser;
use Error;

abstract class UserBase extends DLUser {

    /**
     * Nombre del campo usuario del formulario
     *
     * @var string|null
     */
    protected static ?string $username_field = null;

    /**
     * Nombre del campo de contraseña del formulario
     *
     * @var string|null
     */
    protected static ?string $password_field = null;

    /**
     * Columna de usuario de la tabla usuarios
     *
     * @var string|null
     */
    protected static ?string $username_column = null;

    /**
     * Nombre de la columna de contraseñas de la tabla de usuarios
     *
     * @var string|null
     */
    protected static ?string $password_column = null;

    /**
     * Nombre de la columna tokens de la tabla de usuarios. Se recomienda usarla para 
     * facilitar el cierre de sesión en múltiples dispositivos.
     *
     * @var string|null
     */
    protected static ?string $token_column = null;

    /**
     * Captura del cliente HTTP las credenciales del usuario
     *
     * @return bool
     * 
     * @throws Error
     */
    public function capture_credentials(): bool {

        /**
         * Autenticación del usuario
         * 
         * @var DLAuth
         */
        $auth = DLAuth::get_instance();

        /**
         * Nombre de usuario
         * 
         * @var string
         */
        $username = $this->get_required(
            static::$username_field ?? 'username'
        );

        if (strlen($username) < 4) {
            throw new Error("El nombre de usuario debe contar, con al menos, 4 caracteres");
        }

        $this->set_username(
            $username
        );

        /**
         * Contraseña captura del formulario del cliente HTTP.
         * 
         * @var string
         */
        $password = $this->get_password_valid(
            static::$password_field ?? 'password'
        );

        $this->set_password(
            $password
        );

        /** @var DLCookie $cookie */
        $cookie = new DLCookie();
        $cookie->set_domain(DLServer::get_hostname());
        $cookie->set_http_only(true);
        $cookie->set_secure(DLHost::is_https());

        $auth_options = new DLAuthOptions();
        // Columnas de la tabla (no los nombres de campos del formulario)
        $auth_options->set_username_field(static::$username_column ?? 'username');
        $auth_options->set_password_field(static::$password_column ?? 'password');
        $auth_options->set_token_field(static::$token_column ?? 'token');

        /**
         * Indica si se ha loggeado el usuario
         * 
         * @var boolean $logged
         */
        $logged = $auth->auth($this, $auth_options, $cookie);

        return $logged;
    }
}
