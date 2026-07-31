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

use DLCore\Compilers\DLMarkdown;
use DLRoute\Core\Routing\Router;
use Framework\Config\Controller;

/**
 * Páginas públicas del skeleton (inicio y política de datos personales).
 *
 * Tienda comercial: https://store.dlunire.dev/
 */
final class WelcomeController extends Controller {
    /**
     * Devuelve el callable de enrutado para plantillas (`Router::to` → `$route(...)`).
     *
     * Respeta la URL base de la aplicación (incluidos despliegues en subdirectorio).
     *
     * @return callable(string=): string
     */
    private function route_callable(): callable {
        return Router::to(...);
    }

    /**
     * Genera un nonce CSP por petición (hex, apto para atributo HTML y cabecera).
     *
     * @return string
     */
    private function csp_nonce(): string {
        return bin2hex(random_bytes(32));
    }

    /**
     * Construye la política Content-Security-Policy de las páginas públicas.
     *
     * `script-src` / `style-src` solo con nonce (sin `'self'`): un script u hoja
     * del mismo origen sin el nonce de esta respuesta no se ejecuta. Google Fonts
     * se permite en `style-src` y `font-src`.
     *
     * @param string $nonce Token aleatorio de la petición.
     * @return string Cadena CSP lista para cabecera o meta.
     */
    private function build_csp(string $nonce): string {
        return implode('; ', [
            "default-src 'self'",
            "script-src 'nonce-{$nonce}'",
            "style-src 'nonce-{$nonce}' https://fonts.googleapis.com",
            "font-src 'self' https://fonts.gstatic.com",
            "img-src 'self' data:",
            "connect-src 'self'",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
            "frame-ancestors 'none'",
            // 'upgrade-insecure-requests',
        ]);
    }

    /**
     * Envía la cabecera HTTP Content-Security-Policy.
     *
     * Preferible a solo meta: `frame-ancestors` no aplica en `<meta http-equiv>`.
     *
     * @param string $csp Política completa.
     * @return void
     */
    private function send_csp(string $csp): void {
        header("Content-Security-Policy: {$csp}");
    }

    /**
     * Prepara nonce, política CSP y callable de rutas para las vistas públicas.
     *
     * @return array{
     *     route: callable(string=): string,
     *     token: string,
     *     csp: string
     * }
     */
    private function public_view_data(): array {
        $token = $this->csp_nonce();
        $csp = $this->build_csp($token);
        $this->send_csp($csp);

        $route = $this->route_callable();
        // print_r($route('style.css')); exit;

        return [
            'route' => $route,
            'token' => $token,
            'csp' => $csp,
        ];
    }

    /**
     * Renderiza la página de bienvenida (`GET /`).
     *
     * Incluye CSP por petición (cabecera + meta) y `$token` para atributos `nonce`.
     *
     * @return string HTML compilado de la vista `welcome`.
     */
    public function index(): string {
        return view('welcome', array_merge($this->public_view_data(), [
            'button_type' => 'button--login',
            'label' => 'Realizar una prueba',
        ]));
    }

    /**
     * Renderiza la política de tratamiento de datos personales (`GET /privacy-policy`).
     *
     * Origen: `docs/POLITICA-DATOS.md` (Ley 1581 de 2012, Colombia). El Markdown
     * se convierte con {@see DLMarkdown::stringMarkdown()}. Si el archivo no es
     * legible, responde HTTP 404. Misma CSP que la bienvenida.
     *
     * @return string HTML compilado de la vista `docs-politica-datos`.
     */
    public function privacy_policy(): string {
        $data = $this->public_view_data();
        $file = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'docs' . DIRECTORY_SEPARATOR . 'POLITICA-DATOS.md';

        if (!is_readable($file)) {
            http_response_code(404);

            return view('docs-politica-datos', array_merge($data, [
                'content' => '<p>Documento no encontrado.</p>',
            ]));
        }

        $content = DLMarkdown::stringMarkdown((string) file_get_contents($file));

        return view('docs-politica-datos', array_merge($data, [
            'content' => (string) $content,
        ]));
    }
}
