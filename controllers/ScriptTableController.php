<?php
    require_once '../config/database.php';

    class ScriptTableController extends Database {
        
        function script() {

            $conection = $this->connection();
            
            $logs = [
                'categorias' => [
                    'success' => 0,
                    'failed' => []
                ],
                'productos' => [
                    'success' => 0,
                    'failed' => []
                ],
                'comentarios' => [
                    'success' => 0,
                    'failed' => []
                ]
            ];

            // CATEGORIAS
            $get_cat_rand = $conection->query('SELECT ID FROM CATEGORIAS C WHERE C.ID_CATEGORIA_ANID IS NULL ORDER BY RAND() LIMIT 1');
            $get_cat_rand->execute();
            $cat_rand = $get_cat_rand->fetchAll(PDO::FETCH_ASSOC)[0];

            $query = $conection->prepare('INSERT INTO CATEGORIAS (ID_CATEGORIA_ANID, NOMBRE) 
            VALUES (:id_cat_an, :nombre)');
            
            for ($i=0; $i < 10; $i++) { 
                $data = [
                    'id_cat_an' => $cat_rand['ID'],
                    'nombre' => 'Categoria script ' . $i . '. '. date('Y-m-d H:i:s'). ' ' . uniqid()
                ];
                try {
                    if($i == 9) throw new Exception('Error productos insert.');
                    $query->execute($data);
                    $logs['categorias']['success'] += 1;
                }
                catch (\PDOException $e ) {
                    array_push($logs['productos']['failed'], [
                        'message' => $e->getMessage(),
                        'data' => $data
                    ]);
                } 
                catch (\Throwable $e) {
                    array_push($logs['productos']['failed'], [
                        'message' => $e->getMessage(),
                        'data' => $data
                    ]);
                }
            }

            $lastIdCategoria = $conection->lastInsertId();

            // PRODUCTOS
            $query_productos = $conection->prepare('INSERT INTO PRODUCTOS 
            (MODELO, ID_CATEGORIA, NOMBRE, ESPECIFICACIONES, PRECIO)
            VALUES (:modelo, :id_categoria, :nombre, :especificaciones, :precio)');

            for ($i=0; $i < 10; $i++) { 
                $data = [
                    'modelo' => uniqid(md5(date('Y-m-d H:i:s'))),
                    'id_categoria' => $lastIdCategoria,
                    'nombre' => 'Producto script ' . $i . '. '. date('Y-m-d H:i:s'). ' ' . uniqid(),
                    'especificaciones' => 'Especificaciones script ' . $i . '. '. date('Y-m-d H:i:s'). ' ' . uniqid(),
                    'precio' => rand(10000, 100000) / 10
                ];
                try {
                    if($i == 9) throw new Exception('Error productos insert.');
                    $query_productos->execute($data);
                    $logs['productos']['success'] += 1;
                }
                catch (\PDOException $e ) {
                    array_push($logs['productos']['failed'], [
                        'message' => $e->getMessage(),
                        'data' => $data
                    ]);
                } 
                catch (\Throwable $e) {
                    array_push($logs['productos']['failed'], [
                        'message' => $e->getMessage(),
                        'data' => $data
                    ]);
                }
            }
            $lastIdProducto = $conection->lastInsertId();

            // COMENTARIOS
            $query_comentarios = $conection->prepare('INSERT INTO COMENTARIOS 
            (ID_PRODUCTO, TITULO, TEXTO, CALIFICACION)
            VALUES (:id_producto, :titulo, :texto, :calificacion)');

            for ($i=0; $i < 10; $i++) { 
                $data = [
                    'id_producto' => $lastIdProducto,
                    'titulo' => 'Titulo comentario ' . $i . '. '. date('Y-m-d H:i:s'). ' ' . uniqid(),
                    'texto' => 'Descripcion comentario ' . $i . '. '. date('Y-m-d H:i:s'). ' ' . uniqid(),
                    'calificacion' => rand(1, 5)
                ];
                try {
                    if($i == 9) throw new Exception('Error productos insert.');
                    $query_comentarios->execute($data); 
                    $logs['comentarios']['success'] += 1;
                }
                catch (\PDOException $e ) {
                    array_push($logs['productos']['failed'], [
                        'message' => $e->getMessage(),
                        'data' => $data
                    ]);
                } 
                catch (\Throwable $e) {
                    array_push($logs['productos']['failed'], [
                        'message' => $e->getMessage(),
                        'data' => $data
                    ]);
                }
            }

            // LOGS
            $query_logs = $conection->prepare('INSERT INTO LOGS 
            (TABLA, ESTATUS_INSERT, REGISTROS_INSERTADOS, MENSAJE_ERROR)
            VALUES (:tabla, :et, :ri, :me)');

            foreach ($logs as $key => $log) {
                foreach ($log as $key2 => $estatus) {
                    $query_logs->execute([
                        'tabla' => $key,
                        'et' => $key2,
                        'ri' => $key2 == 'success' ? $estatus : null,
                        'me' => $key2 == 'failed' ? json_encode($estatus) : null
                    ]);
                }
            }

            return true;
        }
    }
?>