<?php

require_once "./config/app.php";
require_once "./autoload.php";


/*---------- Iniciando sesion ----------*/
require_once "./app/views/inc/session_start.php";

if (isset($_GET['views'])) {
    $url = explode("/", $_GET['views']);
} else {
    $url = ["login"];
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <?php require_once "./app/views/inc/head.php"; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <?php

    use app\controllers\viewsController;
    use app\controllers\loginController;
    use app\controllers\productController;

    $insLogin = new loginController();

    $viewsController = new viewsController();
    $productController = new productController();
    $vista = $viewsController->obtenerVistasControlador($url[0]);

    if ($vista == "login" || $vista == "404") {
        require_once "./app/views/content/" . $vista . "-view.php";
    } else {
    ?>
        <main class="page-container">
            <?php
            # Cerrar sesion #
            if ((!isset($_SESSION['id']) || $_SESSION['id'] == "") || (!isset($_SESSION['usuario']) || $_SESSION['usuario'] == "")) {
                $insLogin->cerrarSesionControlador();
                exit();
            }
            require_once "./app/views/inc/navlateral.php";
            # Mostrar productos con bajo stock #
            $productosBajoStock = $productController->obtenerProductosBajoStock(); // <-- Llama a la función de stock bajo

            if (count($productosBajoStock) > 0) {
                echo "<div class='alert alert-warning alert-dismissible fade show' role='alert' style='margin: 15px; padding: 15px; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);'>";
                echo "<div style='font-size: 24px; margin-right: 15px;'>⚠️</div>"; // Ícono de advertencia
                echo "<div>";
                echo "<h4 class='alert-heading'><strong>¡Atención!</strong> Tienes " . count($productosBajoStock) . " productos con stock bajo:</h4>";
                echo "<hr style='border-top: 1px solid #ffc107;'>";
                echo "<ul style='list-style-type: none; padding-left: 0;'>";
                foreach ($productosBajoStock as $producto) {
                    echo "<li style='margin-bottom: 8px;'><strong>Producto:</strong> " . $producto['nombre'] . " <strong>| Stock:</strong> <span style='color: red;'>" . $producto['stock'] . "</span></li>";
                }
                echo "</ul>";
                echo "</div>";

                // Botón para cerrar la alerta
                echo "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";

                echo "</div>";
            }

            require_once 'app\controllers\productController.php';
            ?>
            <section class="full-width pageContent scroll" id="pageContent">
                <?php
                require_once "./app/views/inc/navbar.php";

                require_once $vista;

                ?>
            </section>
        </main>
    <?php
    }

    require_once "./app/views/inc/script.php";
    ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>