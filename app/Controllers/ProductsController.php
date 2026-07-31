<?php

namespace DLUnire\Controllers;

use DLCore\Core\BaseController;
use DLUnire\Models\Products;

class ProductsController extends BaseController {
    public function index(): array {
        return Products::paginate(page: 1, rows: 3);
    }

    public function show(object $params): array {
        return [
            'id' => $params->id,
        ];
    }
}
