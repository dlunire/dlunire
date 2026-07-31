<?php

use DLRoute\Requests\DLRoute;
use DLUnire\Controllers\WelcomeController;

DLRoute::get('/', [WelcomeController::class, 'index']);

# Privacidad
DLRoute::get('/privacy', [WelcomeController::class, 'privacy']);

# Términos y condiciones
DLRoute::get('/terms', [WelcomeController::class, 'terms']);

# Cookies
DLRoute::get('/cookies', [WelcomeController::class, 'cookies']);
