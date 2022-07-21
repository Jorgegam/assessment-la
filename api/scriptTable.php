<?php
require_once '../controllers/ScriptTableController.php';

$method = $_SERVER['REQUEST_METHOD'];

$p = new ScriptTableController;

switch ($method) {
    case 'GET':
        $productos = $p->script();
        print_r(json_encode([
            'status' => 200,
            'response' => 'success'
        ]));
        break;
    default:
        # code...
        break;
}


?>