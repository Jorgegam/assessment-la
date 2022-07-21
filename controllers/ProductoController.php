<?php
    require_once '../config/database.php';

    class ProductoController extends Database {

        function getProductosDestacados($filtros) {
            $where = '';
            if(!is_null($filtros['categoria'])) {
                $where = 'WHERE P.ID_CATEGORIA = ' . $filtros['categoria'];
            }
            $conection = $this->connection();
            $query = $conection->query('SELECT * FROM PRODUCTOS P
            '. $where .'
            ORDER BY RAND()');
            $query->execute();
            $productos = $query->fetchAll(PDO::FETCH_ASSOC);
            return $productos;
        }

        function getProductosMasVendidos($filtros) {
            $where = '';
            if(!is_null($filtros['categoria'])) {
                $where = 'WHERE P.ID_CATEGORIA = ' . $filtros['categoria'];
            }
            $conection = $this->connection();
            $query = $conection->query('SELECT 
            P.ID, P.NOMBRE, P.PRECIO, SUM(CALIFICACION), P.ESPECIFICACIONES
            FROM PRODUCTOS P
            INNER JOIN COMENTARIOS C ON P.ID = C.ID_PRODUCTO
            '. $where .'
            GROUP BY P.ID
            ORDER BY 4 DESC');
            $query->execute();
            $productos_mas_vendidos = $query->fetchAll(PDO::FETCH_ASSOC);
            return $productos_mas_vendidos;
        }

        function getSingleProductInfo($id) {
            $conection = $this->connection();
            $query = $conection->query('SELECT * FROM PRODUCTOS P
            WHERE P.ID = ' . $id . '
            LIMIT 1');
            $query->execute();
            $info_producto = $query->fetchAll(PDO::FETCH_ASSOC);
            return $info_producto;
        }

        function getReseñasProducto($id) {
            $conection = $this->connection();
            $query = $conection->query('SELECT * FROM COMENTARIOS C
            WHERE C.ID_PRODUCTO = ' . $id);
            $query->execute();
            $res_procuto = $query->fetchAll(PDO::FETCH_ASSOC);
            return $res_procuto;
        }

    }
?>