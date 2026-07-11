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
 *
 * Post create-project: crea `.env.type` desde `.env.type.example`
 * con indicador de carga en terminal interactiva (TTY).
 *
 * Uso manual:
 *   php bin/setup-env.php
 *   composer run-script post-create-project-cmd
 */

declare(strict_types=1);

$root = dirname(__DIR__);
$example = $root . DIRECTORY_SEPARATOR . '.env.type.example';
$target = $root . DIRECTORY_SEPARATOR . '.env.type';

$is_tty = function_exists('stream_isatty') && @stream_isatty(STDOUT);

/**
 * Escribe una línea completa (con salto).
 */
$say = static function (string $message) use ($is_tty): void {
    if ($is_tty) {
        // Limpia restos de una línea de spinner anterior
        echo "\r" . str_repeat(' ', 72) . "\r";
    }
    echo $message . PHP_EOL;
};

/**
 * Actualiza la línea de spinner (solo TTY).
 *
 * @param list<string> $frames
 */
$spin_frame = static function (string $message, array $frames, int &$index) use ($is_tty): void {
    if (!$is_tty) {
        return;
    }

    $frame = $frames[$index % count($frames)];
    $index++;
    echo "\r  {$frame}  {$message}";
    if (function_exists('flush')) {
        flush();
    }
};

echo PHP_EOL;
$say('  DLUnire — configuración inicial');

if (!is_file($example)) {
    $say('  ✗ No se encontró .env.type.example');
    exit(1);
}

if (is_file($target)) {
    $say('  · .env.type ya existe — no se sobrescribe');
    $say('');
    exit(0);
}

$frames = ['⠋', '⠙', '⠹', '⠸', '⠼', '⠴', '⠦', '⠧', '⠇', '⠏'];
// Fallback ASCII si la terminal no representa bien braille
if (!$is_tty || (function_exists('getenv') && getenv('DLUNIRE_SPINNER_ASCII') === '1')) {
    $frames = ['|', '/', '-', '\\'];
}

$index = 0;
$message = 'Creando .env.type…';

// Varias vueltas de spinner (sensación de progreso en create-project)
$ticks = $is_tty ? 12 : 0;
for ($t = 0; $t < $ticks; $t++) {
    $spin_frame($message, $frames, $index);
    usleep(55_000);
}

if (!$is_tty) {
    echo "  · {$message}" . PHP_EOL;
}

if (!@copy($example, $target)) {
    $say('  ✗ No se pudo crear .env.type');
    exit(1);
}

if (!is_file($target)) {
    $say('  ✗ .env.type no quedó en el disco');
    exit(1);
}

$say('  ✓ .env.type creado desde .env.type.example');
$say('  Edite .env.type y ejecute: composer run dev');
$say('');

exit(0);
