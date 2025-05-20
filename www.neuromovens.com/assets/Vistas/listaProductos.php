<?php
include '../Compartido/header.php';

use Entidades\Producto;
require '../Entidades/Producto.php';
$sesion_usuario = isset($_SESSION['usuario']) && $_SESSION['usuario'] === true;
$productos = isset($_SESSION['productos']) ? unserialize($_SESSION['productos']) : [];
?>

<div class="container my-5">
    <h1 class="title mb-4 text-center">Lista de Productos</h1>

    <?php if (!empty($productos)): ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
            <?php foreach ($productos as $producto): ?>
                <div class="col">
                    <div class="card h-100">
                        <img src="<?php echo htmlspecialchars($producto->getImagenUrl()); ?>" class="card-img-top" alt="Imagen del Producto">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($producto->getNombre()); ?></h5>
                            <p class="card-text"><?php echo htmlspecialchars($producto->getDescripcion()); ?></p>
                        </div>
                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <span class="fw-bold">€<?php echo number_format($producto->getPrecio(), 2); ?></span>
                            <?php if ($sesion_usuario): ?>
                                <div class="btn-group">
                                    <a href="../Controlador/ControladorProductos.php?accion=cargar&id=<?php echo $producto->getId(); ?>&redirect=listaProductos" class="btn btn-sm btn-outline-info">Editar</a>
                                    <a href="../Controlador/ControladorProductos.php?accion=eliminar&id=<?php echo $producto->getId(); ?>&redirect=listaProductos" class="btn btn-sm btn-outline-danger">Eliminar</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center" role="alert">
            No hay productos disponibles.
        </div>
    <?php endif; ?>
</div>

<?php include '../Compartido/footer.php'; ?>
