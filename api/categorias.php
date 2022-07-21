<?php
require_once '../controllers/CategoriaController.php';

$method = $_SERVER['REQUEST_METHOD'];

$c = new CategoriaController;

switch ($method) {
    case 'GET':

        $categorias = $c->get();
        print_r(json_encode([
            'response' => 'success',
            'data' => $categorias
        ]));

        break;
    default:
        # code...
        break;
}


?>