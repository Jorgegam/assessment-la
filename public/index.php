<?php

require_once '../config/env.php';

$categoria = null;
$subcategoria = null;
if (isset($_GET['categoria'])) $categoria = $_GET['categoria'];
if (isset($_GET['subcategoria'])) $subcategoria = $_GET['subcategoria'];

$opts = [
    "http" => [
        "method" => "GET"
    ]
];
$context = stream_context_create($opts);
$reponse = file_get_contents(global_api . '/categorias.php', false, $context);
$categorias = json_decode($reponse)->data;

$opts = [
    "http" => [
        "method" => "GET"
    ]
];
$params = [
    'categoria' => $categoria
];
$response_productos = file_get_contents(global_api . '/productos.php/?'.http_build_query($params), false, $context);
$productos = json_decode($response_productos)->data->productos_destacados;
$productos_mas_vendidos = json_decode($response_productos)->data->productos_mas_vendidos;

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
    <main class="container mt-4" id="vue-main">
        <section class="catalogo row">
            <aside class="col-md-3 py-2 rounded">
                <div class="filtros">
                    <section class="filtro">
                        <label class="form-label filtro-titulo fw-bolder">Categorias</label>
                        <div class="filtro-categorias" style="max-height: 400px; overflow-y: scroll">
                            <div class="accordion" id="accordionExample">
                                <?php foreach ($categorias as $key => $c) : ?>
                                    <?php
                                    $button = 'collapsed';
                                    $expanded = 'false';
                                    $show = '';
                                    if ($categoria == $c->ID) {
                                        $button = '';
                                        $expanded = 'true';
                                        $show = 'show';
                                    }
                                    ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button fw-normal <?= $button ?>" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne<?= $key ?>" aria-expanded="<?= $expanded ?>" aria-controls="collapseOne<?= $key ?>">
                                                <?= $c->NOMBRE ?>
                                            </button>
                                        </h2>
                                        <div id="collapseOne<?= $key ?>" class="accordion-collapse collapse <?= $show ?>" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="list-group">
                                                    <?php foreach ($c->SUBCATEGORIAS as $key2 => $s) : ?>
                                                        <?php
                                                            $active = '';
                                                            if ($subcategoria == $s->ID) $active = 'active';
                                                        ?>
                                                        <a href=".?categoria=<?= $c->ID ?>&subcategoria=<?= $s->ID ?>" class="list-group-item list-group-item-action border-top-0 border-start-0 border-end-0 <?= $active ?>">
                                                            <?= $s->NOMBRE ?>
                                                        </a>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </section>
                </div>
            </aside>
            <section class="col-md-9 seccion-productos pb-3">
                <div class="p-seccion productos-destacados pb-3">
                    <?php if(!is_null($categoria) && !is_null($subcategoria)): ?>
                    <div class="d-flex justify-content-end">
                        <a href="./" class="btn btn-link ml-auto text-dark text-decoration-none">
                            <span class="badge bg-light text-dark">
                                <i class="las la-times"></i>
                                Quitar filtros
                            </span>
                        </a>
                    </div>
                    <?php endif; ?>
                    <h4 class="productos-titulo">
                        Productos destacados
                    </h4>
                    <div class="border-bottom mb-2"></div>
                    <div class="row align-items-stretch listado-productos">
                        <?php foreach ($productos as $key => $p) : ?>
                            <div class="col-md-4 col-producto mb-3">
                                <div data-aos="fade-up" class="card h-100">
                                    <a href="./detalles-producto?producto=<?= $p->ID ?>" class="text-dark text-decoration-none">               
                                        <img src="https://picsum.photos/150/150" class="card-img-top" alt="...">
                                    </a>
                                    <div class="card-body">
                                        <a href="./detalles-producto?producto=<?= $p->ID ?>" class="text-dark text-decoration-none">
                                            <p class="card-precio fw-bolder">
                                                <?= '$' . number_format($p->PRECIO, 2) ?>
                                            </p>
                                            <h5 class="card-title">
                                                <?= $p->NOMBRE ?>
                                            </h5>
                                            <p class="card-text">
                                                <?= $p->ESPECIFICACIONES ?>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="p-seccion productos-mas-vendidos">
                    <h4 class="productos-titulo">
                        Productos mas vendidos
                    </h4>
                    <div class="border-bottom mb-2"></div>
                    <div class="row align-items-stretch listado-productos">
                        <?php foreach ($productos_mas_vendidos as $key => $p) : ?>
                            <div class="col-md-4 col-producto mb-3">
                                <div data-aos="fade-up" class="card h-100">
                                    <a href="./detalles-producto?producto=<?= $p->ID ?>" class="text-dark text-decoration-none">     
                                        <img src="https://picsum.photos/150/150" class="card-img-top" alt="...">
                                    </a>
                                    <div class="card-body">
                                        <a href="./detalles-producto?producto=<?= $p->ID ?>" class="text-dark text-decoration-none">
                                            <p class="card-precio fw-bolder">
                                                <?= '$' . number_format($p->PRECIO, 2) ?>
                                            </p>
                                            <h5 class="card-title">
                                                <?= $p->NOMBRE ?>
                                            </h5>
                                            <p class="card-text">
                                                <?= $p->ESPECIFICACIONES ?>
                                            </p>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>
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