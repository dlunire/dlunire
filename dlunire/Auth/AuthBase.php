<?php

namespace Framework\Auth;

use DLCore\Auth\DLAuth;
use Framework\Config\Token;

/**
 * Autenticación base
 * 
 * @package Framework\Auth
 * 
 * @version 1.0.0 (release)
 * @author David E Luna M <contact@dlunire.pro>
 * @copyright 2024 David E Luna M
 * @license AGPL-3.0-or-later
 */
abstract class AuthBase extends DLAuth {
    use Token;
}
