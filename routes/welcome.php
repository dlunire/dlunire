<?php

/**
 * Puede eliminar este archivo si lo desea. Borrarás automáticamente las rutas que
 * vienen por defecto.
 */
use DLRoute\Requests\DLRoute;
use DLUnire\Controllers\WelcomeController;

DLRoute::get("/", [WelcomeController::class, "index"]);

# Privacidad
DLRoute::get("/privacy", [WelcomeController::class, "privacy"]);

# Términos y condiciones
DLRoute::get("/terms", [WelcomeController::class, "terms"]);

# Cookies
DLRoute::get("/cookies", [WelcomeController::class, "cookies"]);

# Script
DLRoute::get("/script", [WelcomeController::class, "script"]);

# Estilos
DLRoute::get("/styles", [WelcomeController::class, "styles"]);
