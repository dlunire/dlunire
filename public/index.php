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

ini_set("display_errors", 1);

use Boot\Project;
use Framework\Errors\DLExceptionHandler;

include dirname(__DIR__) . "/vendor/autoload.php";

set_exception_handler([DLExceptionHandler::class, 'handle']);

Project::run();