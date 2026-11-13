<?php

namespace DLUnire\Controllers;

use DLCore\Core\BaseController;
use DLCore\Core\Output\View;
use DLRoute\Core\Routing\Router;

final class WelcomeController extends BaseController {

    public function welcome(): string {

        return View::get('welcome', [
            "token" => $this->generate_uuid(),
            "route" => Router::to(...)
        ]);
    }
}
