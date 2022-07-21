<?php
    require_once '../config/database.php';

    class CategoriaController extends Database {
        function get() {
            $conection = $this->connection();
            $query = $conection->query('SELECT * FROM CATEGORIAS WHERE ID_CATEGORIA_ANID IS NULL');
            $query->execute();
            $categorias = $query->fetchAll(PDO::FETCH_ASSOC);
            foreach ($categorias as $key => $c) {
                $categorias[$key]['SUBCATEGORIAS_SHOW'] = false;
                $query_subc = $conection->query('SELECT * FROM CATEGORIAS WHERE ID_CATEGORIA_ANID = ' . $c['ID']);
                $query_subc->execute();
                $sub_categorias = $query_subc->fetchAll(PDO::FETCH_ASSOC);
                $categorias[$key]['SUBCATEGORIAS'] = $sub_categorias;
            }
            return $categorias;
        }

    }
?>