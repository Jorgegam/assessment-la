<?php
require_once '../controllers/ProductoController.php';

$method = $_SERVER['REQUEST_METHOD'];

$p = new ProductoController;

switch ($method) {
    case 'GET':
        if(isset($_GET['producto'])) {
            $id = $_GET['producto'];
            $producto_info = $p->getSingleProductInfo($id);
            $comentarios = $p->getReseñasProducto($id);
            print_r(json_encode([
                'response' => 'success',
                'data' => [
                    'producto' => $producto_info,
                    'comentarios' => $comentarios
                ]
            ]));
            die();    
        }

        $categoria = null;
        if (isset($_GET['categoria'])) $categoria = $_GET['categoria'];
        $filtros = [
            'categoria' => $categoria
        ];

        $productos_destacados = $p->getProductosDestacados($filtros);
        $productos_mas_vendidos = $p->getProductosMasVendidos($filtros);

        print_r(json_encode([
            'response' => 'success',
            'data' => [
                'productos_destacados' => $productos_destacados,
                'productos_mas_vendidos' => $productos_mas_vendidos
            ]
        ]));

        break;
    case 'POST':

        break;
    default:
        # code...
        break;
}


?>