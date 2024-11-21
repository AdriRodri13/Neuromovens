<?php

include '../Compartido/header.php';

use Entidades\Producto;
require '../Entidades/Producto.php';
$sesion_usuario = isset($_SESSION['usuario']) && $_SESSION['usuario'] === true;
$productos = isset($_SESSION['productos']) ? unserialize($_SESSION['productos']) : [];



?>

<h1 class="title">Lista de Productos</h1>

<main>
    <?php if (!empty($productos)): ?>
        <?php foreach ($productos as $producto): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src="<?php echo htmlspecialchars($producto->getImagenUrl()); ?>" alt="Imagen del Producto">
                    <div class="product-price">€<?php echo number_format($producto->getPrecio(), 2); ?></div>
                </div>
                <div class="product-info">
                    <h3><?php echo htmlspecialchars($producto->getNombre()); ?></h3>
                    <p><?php echo htmlspecialchars($producto->getDescripcion()); ?></p>
                </div>

                <?php if ($sesion_usuario): ?>
                    <div>
                        <a href="../Controlador/ControladorProductos.php?accion=cargar&id=<?php echo $producto->getId(); ?>" class="btn btn-info">Editar</a>
                        <a href="../Controlador/ControladorProductos.php?accion=eliminar&id=<?php echo $producto->getId(); ?>" class="btn btn-danger">Eliminar</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No hay productos disponibles.</p>
    <?php endif; ?>
</main>

<?php include '../Compartido/footer.php'; ?>
