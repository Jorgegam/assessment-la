<?php

require_once '../config/env.php';
$producto = null;
if (isset($_GET['producto'])) $producto = $_GET['producto'];

$opts = [
    "http" => [
        "method" => "GET"
    ]
];
$context = stream_context_create($opts);
$params = [
    'producto' => $producto
];
$response_producto = file_get_contents(global_api . '/productos.php/?'.http_build_query($params), false, $context);
$producto = json_decode($response_producto)->data->producto[0];
$comentarios = json_decode($response_producto)->data->comentarios;

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio</title>
    <link rel="stylesheet" href="./assets/css/styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css" integrity="sha512-vebUliqxrVkBy3gucMhClmyQP9On/HAWQdKDXRaAlb/FKuTbxkjPKUyqVOxAcGwFDka79eTF+YXwfke1h3/wfg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.23/sweetalert2.css" integrity="sha512-gAU9FxrcktP/m5fRrn5P4FmIutdMP/kpVKsPerqffFy9gKQkR4cxrcrK3PtgTAgFiiN7b5+fwRbpCcO1F5cPew==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>

<body>
    <?php
    require_once './utils/header.php';
    ?>
    <main class="container mt-4" id="detalles-producto">
        <section class="row">
            <div class="col-md-7">
                <img src="https://picsum.photos/seed/picsum/500/300" alt="Img producto" class="img-fluid rounded mx-auto shadow d-block" srcset="">
            </div>
            <div class="col-md-5 border-start">
                <h4 class="mb-3"><?= $producto->NOMBRE ?></h4>
                <h5>
                    <?= $producto->ESPECIFICACIONES ?>
                </h5>
                <p class="fw-bolder">
                    $<?= number_format($producto->PRECIO, 2) ?>
                </p>
                <p>
                    Modelo <?= $producto->MODELO ?>
                </p>
            </div>
            <?php foreach ($comentarios as $key => $c): ?>
            <div class="col-md-12 mt-4 border-top py-2 px-0">
                <h4 class="mb-2">Opiniones de clientes</h4>
                <div class="comentarios px-3 py-2 my-2 shadow rounded border-bottom">
                    <div class="d-flex align-items-center mb-3">
                        <h5 class="mb-0"> 
                            <?= $c->TITULO ?>
                        </h5>
                        <?php
                            for ($i=0; $i < $c->CALIFICACION; $i++) { 
                                echo '<i class="las la-star ms-2 text-warning"></i>';
                            } 
                        ?>
                    </div>
                    <p><?= $c->TEXTO ?></p>
                </div>
            </div>
            <?php  endforeach; ?>
        </section>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.27.2/axios.min.js" integrity="sha512-odNmoc1XJy5x1TMVMdC7EMs3IVdItLPlCeL5vSUPN2llYKMJ2eByTTAIiiuqLg+GdNr9hF6z81p27DArRFKT7A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.4.23/sweetalert2.min.js" integrity="sha512-eJK7xM/jkT80Ixs4NJuFhaqb/DfpGFP9j/GkZGzlQyn6nZmJPSXkWsLvRTcR4HBBe7bUlqwyWFpb0pJ44GyP/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        AOS.init();
    </script>
    <script src="./assets/js/env.js"></script>
    <script src="./assets/js/main.js"></script>
</body>

</html>