<?php
use Entidades\Producto;

include '../Compartido/header.php';
require '../Entidades/Producto.php';
require '../Entidades/Categoria.php';

// Verificación de sesión de usuario
$sesion_usuario = isset($_SESSION['usuario']) && $_SESSION['usuario'] === true;
$rol_usuario = $sesion_usuario ? $_SESSION['rol'] : 'visitante';
?>

<div class="container py-4">
    <h1 class="text-center mb-5">Nuestros productos</h1>

    <?php if ($sesion_usuario && $rol_usuario !== 'visitante'): ?>
        <div class="d-grid d-md-flex gap-2 justify-content-md-start mb-4">
            <a href="insertarCategoria.php" class="btn btn-success">
                <i class="fas fa-folder-plus me-2"></i> Insertar Nueva Categoría
            </a>
            <a href="../Controlador/ControladorProductos.php?accion=mostrarTodos" class="btn btn-info">
                <i class="fas fa-boxes me-2"></i> Mostrar todos los Productos
            </a>
        </div>
    <?php endif; ?>

    <?php
    $productosPorCategoria = isset($_SESSION['productos_por_categoria']) ? unserialize($_SESSION['productos_por_categoria']) : [];
    ?>

    <?php if (!empty($productosPorCategoria)): ?>
        <?php foreach ($productosPorCategoria as $idCategoria => $categoriaData): ?>
            <section class="mb-5">
                <h2 class="mb-3"><?php echo htmlspecialchars($categoriaData['nombre_categoria']); ?></h2>

                <?php if ($sesion_usuario): ?>
                    <p class="text-muted small">ID Categoría: <?php echo htmlspecialchars($categoriaData['id_categoria']); ?></p>
                <?php endif; ?>

                <div class="row g-4">
                    <?php foreach ($categoriaData['productos'] as $producto): ?>
                        <div class="col-12">
                            <div class="card flex-column flex-lg-row h-100 shadow-sm">
                                <img src="<?php echo htmlspecialchars($producto->getImagenUrl()); ?>" class="img-fluid rounded-start w-100 w-lg-25" alt="Imagen del producto" style="max-width: 200px; object-fit: cover;">
                                <div class="card-body d-flex flex-column flex-grow-1">
                                    <h5 class="card-title"><?php echo htmlspecialchars($producto->getNombre()); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($producto->getDescripcion()); ?></p>
                                    <p class="mt-auto fw-bold text-success">€<?php echo number_format($producto->getPrecio(), 2); ?></p>
                                </div>
                                <?php if ($sesion_usuario && $rol_usuario !== 'visitante'): ?>
                                    <div class="card-footer d-grid gap-2 p-3">
                                        <a href="../Controlador/ControladorProductos.php?accion=cargar&id=<?php echo $producto->getId(); ?>" class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-edit me-1"></i> Editar
                                        </a>
                                        <a href="../Controlador/ControladorProductos.php?accion=eliminar&id=<?php echo $producto->getId(); ?>" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-trash me-1"></i> Eliminar
                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>


                <?php if ($sesion_usuario && $rol_usuario !== 'visitante'): ?>
                    <div class="d-grid d-md-flex gap-2 mt-4">
                        <a href="../Controlador/ControladorCategoria.php?accion=cargar&id=<?php echo $idCategoria; ?>" class="btn btn-outline-info">
                            <i class="fas fa-edit me-2"></i>Editar Categoría
                        </a>
                        <a href="../Controlador/ControladorCategoria.php?accion=eliminar&id=<?php echo $idCategoria; ?>" class="btn btn-outline-danger">
                            <i class="fas fa-trash me-2"></i>Eliminar Categoría
                        </a>
                        <a href="../Controlador/ControladorProductos.php?accion=cargarInserccion" class="btn btn-success">
                            <i class="fas fa-plus me-2"></i>Insertar Nuevo Producto
                        </a>
                    </div>
                <?php endif; ?>
            </section>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="alert alert-warning text-center">
            <i class="fas fa-exclamation-circle me-2"></i> No hay productos disponibles.
        </div>
    <?php endif; ?>
</div>

<?php include_once '../Compartido/footer.php'; ?>
