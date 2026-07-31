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

use DLRoute\Core\Routing\Router;
use DLRoute\Requests\DLRequest;
use DLCore\Compilers\DLView;
use Dompdf\Dompdf;

if (!function_exists("view")) {
    /**
     * Carga una vista a partir de una plantilla y devuelve su contenido renderizado.
     *
     * @param string $view Ruta a la vista (nombre de archivo o ruta completa).
     * @param array $options Opcional. Variables disponibles dentro de la plantilla.
     * @return string Contenido de la vista ya renderizada.
     */
    function view(string $view, array $options = []): string {
        ob_start();
        DLView::load($view, $options);
        $viewContent = ob_get_clean();

        return trim($viewContent);
    }
}

if (!function_exists("view_pdf")) {
    /**
     * ## ¿Qué hace?
     *
     * Transforma código HTML a formato PDF, permitiendo tanto introducir variables en la plantilla
     * como configurar las opciones de salida del documento generado.
     *
     * ### Variables (Opcional)
     *
     * Para pasar variables a la plantilla, se debe indicar un array asociativo como segundo argumento:
     *
     * ```php
     * <?php
     * ...
     *
     * view_pdf('ruta.vista', [
     *  "variable1" => "Valor de la variable 1",
     *  "variable2" => "Valor de la variable 2"
     * ]);
     * ```
     *
     * ### Configuración de salida (Opcional)
     *
     * Para configurar la salida del documento PDF, se debe pasar un array asociativo como tercer
     * argumento:
     *
     * ```php
     * <?php
     * ...
     *
     * view_pdf('ruta.vista', $options, [
     *  "filename" => 'document.pdf',
     *  "compress" => 1,
     *  "attachment" => 0,
     *  "paper_size" => 'a4',
     *  "orientation" => 'portrait',
     *  "encoding" => 'utf-8'
     * ]);
     * ```
     *
     * #### Explicación de las opciones
     *
     * - **`filename`:** Opcional. Nombre del documento a descargar. Por defecto, `document.pdf`.
     *
     * - **`compress`:** Controla la compresión del flujo de datos del documento PDF generado. Cuando
     * vale `1` (predeterminado), **Dompdf** comprime el contenido, lo que puede reducir el tamaño del
     * archivo a costa de un mayor uso de CPU. Si vale `0`, no se aplica compresión.
     *
     * - **`attachment`:** Establece el encabezado HTTP `Content-Disposition` en `attachment` cuando
     * vale `1`, forzando al navegador a ofrecer el contenido como archivo descargable con el nombre
     * definido en `filename` (o uno por defecto). El valor por defecto es `0`, en cuyo caso el
     * documento se muestra directamente en los navegadores que lo soporten.
     *
     * - **`paper_size`:** Tamaño de la hoja del documento PDF. Puede consultar todos los tamaños
     * admitidos en [Adapter/CPDF.php](https://github.com/dompdf/dompdf/blob/master/src/Adapter/CPDF.php)
     * del repositorio de Dompdf.
     *
     * - **`encoding`:** Codificación de caracteres del documento. Por defecto, `utf-8`.
     *
     * @param string $view Vista de la plantilla a cargar.
     * @param array|null $options Opcional. Variables disponibles en la plantilla.
     * @param array $config Opcional. Opciones de configuración del documento PDF (ver arriba).
     * @return string Documento PDF generado, como cadena binaria.
     */
    function view_pdf(
        string $view,
        ?array $options = null,
        array $config = [],
    ): string {
        /**
         * Nombre de archivo PDF.
         *
         * @var string
         */
        $filename = "document.pdf";

        if (array_key_exists("filename", $config)) {
            $filename = $config["filename"];
        }

        /**
         * Determina si se aplica compresión al flujo de contenido del PDF. Si vale `0`,
         * no se aplicará compresión.
         *
         * @var integer
         */
        $compress = 1;

        if (array_key_exists("compress", $config)) {
            $compress = $config["compress"];
        }

        /**
         * Determina si se establece el encabezado HTTP `Content-Disposition` en `attachment`.
         * Cuando vale `1`, el navegador ofrece el PDF como archivo descargable. Si vale `0`,
         * el PDF se muestra directamente en los navegadores que lo soporten.
         *
         * @var integer
         */
        $attachment = 0;

        if (array_key_exists("attachment", $config)) {
            $attachment = $config["attachment"];
        }

        /**
         * Tamaño del lienzo que representará la hoja en el documento PDF.
         *
         * @var string
         */
        $paper_size = "a4";

        if (array_key_exists("paper_size", $config)) {
            $paper_size = $config["paper_size"];
        }

        /**
         * Orientación de la hoja del documento PDF.
         *
         * @var string
         */
        $orientation = "portrait";

        if (array_key_exists("orientation", $config)) {
            $orientation = $config["orientation"];
        }

        /**
         * Codificación de caracteres del documento PDF.
         *
         * @var string
         */
        $encoding = "utf-8";

        if (array_key_exists("encoding", $config)) {
            $encoding = $config["encoding"];
        }

        /**
         * Contenido de la vista ya renderizado a partir de la plantilla.
         *
         * @var string
         */
        $view = view($view, $options ?? []);

        $pdf = new Dompdf();

        $pdf->loadHtml($view, $encoding);
        $pdf->setPaper($paper_size, $orientation);
        $pdf->render();

        return (string) $pdf->stream($filename, [
            "compress" => $compress,
            "Attachment" => $attachment,
        ]);
    }
}

if (!function_exists("redirect")) {
    /**
     * Redirige al usuario a una nueva URL y termina la ejecución del script.
     *
     * @param string $uri URI a la que redirigir.
     * @param integer $code Código de estado HTTP de la redirección. Por defecto, `302`.
     * @return never
     */
    function redirect(string $uri, int $code = 302) {
        /** @var non-empty-string $url */
        $url = Router::to($uri);

        header("Location: {$url}", true, $code);
        exit();
    }
}

if (!function_exists("is_valid_ref")) {
    /**
     * Verifica si el token CSRF enviado por el cliente coincide con el almacenado en sesión.
     *
     * @param string $field Nombre del campo que contiene el token CSRF en la petición.
     * Por defecto, `csrf-token`.
     * @return boolean `true` si el token es válido, `false` en caso contrario.
     */
    function is_valid_ref(string $field = "csrf-token"): bool {
        /**
         * Petición del cliente HTTP.
         *
         * @var DLRequest $request
         */
        $request = DLRequest::get_instance();

        /**
         * Valores de la petición.
         *
         * @var array $values
         */
        $values = $request->get_values();

        /**
         * Token enviado por el cliente.
         *
         * @var string|null
         */
        $csrf_token = $values[$field] ?? null;

        /**
         * Token almacenado en la sesión actual.
         *
         * @var string|null
         */
        $token = $_SESSION["csrf-token"] ?? null;

        if (is_null($token)) {
            return false;
        }

        return $csrf_token === $token;
    }
}

if (!function_exists("regenerate_activation_code")) {
    /**
     * Rellena un código de activación con ceros a la izquierda hasta completar 13 dígitos.
     *
     * @param string $activation_code Código de activación a normalizar.
     * @return string Código de activación normalizado a 13 dígitos.
     */
    function regenerate_activation_code(string $activation_code): string {
        /**
         * Cantidad de dígitos del código de activación recibido.
         *
         * @var integer $quantity
         */
        $quantity = strlen($activation_code);

        /**
         * Relleno de ceros necesario para completar 13 dígitos.
         *
         * @var string $fill
         */
        $fill = implode("", array_fill(0, 13 - $quantity, 0));

        $activation_code = "{$fill}{$activation_code}";

        return trim($activation_code);
    }
}

if (!function_exists("datetime")) {
    /**
     * Convierte una marca de tiempo UNIX al formato de fecha y hora (`Y-m-d H:i:s`).
     *
     * @param integer $timestamp Marca de tiempo UNIX a convertir.
     * @return string Fecha y hora en formato `Y-m-d H:i:s`.
     */
    function datetime(int $timestamp): string {
        return date("Y-m-d H:i:s", $timestamp);
    }
}
