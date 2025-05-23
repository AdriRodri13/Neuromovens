<?php
use Entidades\Producto;

include '../Compartido/header.php';
require '../Entidades/Producto.php';
require '../Entidades/Categoria.php';

// Verificación de sesión de usuario
$sesion_usuario = isset($_SESSION['usuario']) && $_SESSION['usuario'] === true;
$rol_usuario = $sesion_usuario ? $_SESSION['rol'] : 'visitante';
?>

    <main class="container py-4">
        <h1 class="title d-flex justify-content-center mb-4">Nuestros productos</h1>

        <?php if ($sesion_usuario && $rol_usuario !== 'visitante'): ?>
            <nav class="d-grid d-md-flex gap-2 justify-content-md-start mb-4" aria-label="Administración de productos">
                <a href="insertarCategoria.php" class="btn btn-success">
                    <i class="fas fa-folder-plus me-2"></i> Insertar Nueva Categoría
                </a>
                <a href="../Controlador/ControladorProductos.php?accion=mostrarTodos" class="btn btn-info">
                    <i class="fas fa-boxes me-2"></i> Mostrar todos los Productos
                </a>
            </nav>
        <?php endif; ?>

        <?php
        $productosPorCategoria = isset($_SESSION['productos_por_categoria']) ? unserialize($_SESSION['productos_por_categoria']) : [];
        ?>

        <?php if (!empty($productosPorCategoria)): ?>
            <?php foreach ($productosPorCategoria as $idCategoria => $categoriaData): ?>
                <section class="categoria-productos mb-5" aria-labelledby="categoria-<?php echo $idCategoria; ?>">
                    <h2 id="categoria-<?php echo $idCategoria; ?>" class="mb-3"><?php echo htmlspecialchars($categoriaData['nombre_categoria']); ?></h2>

                    <ul class="row g-4 list-unstyled">
                        <?php foreach ($categoriaData['productos'] as $producto): ?>
                            <li class="col-12">
                                <article class="card shadow-sm producto-item">
                                    <div class="row g-0">
                                        <!-- Imagen del producto -->
                                        <figure class="col-12 col-lg-3 m-0 p-2 d-flex align-items-center justify-content-center">
                                            <div class="product-image-container bg-light p-2 rounded w-100 text-center">
                                                <img src="<?php echo htmlspecialchars($producto->getImagenUrl()); ?>"
                                                     class="img-fluid rounded"
                                                     style="object-fit: cover; max-height: 220px; width: auto; max-width: 100%;"
                                                     alt="<?php echo htmlspecialchars($producto->getNombre()); ?>">
                                            </div>
                                        </figure>

                                        <!-- Contenido del producto -->
                                        <div class="col-12 col-lg-9">
                                            <div class="card-body h-100 d-flex flex-column">
                                                <header class="p-1">
                                                    <h3 class="card-title mb-3 h5"><?php echo htmlspecialchars($producto->getNombre()); ?></h3>
                                                </header>

                                                <p class="card-text flex-grow-1 mb-3"><?php echo htmlspecialchars($producto->getDescripcion()); ?></p>

                                                <!-- Precio y botones -->
                                                <footer class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mt-auto">
                                                    <span class="fw-bold text-success fs-4">€<?php echo number_format($producto->getPrecio(), 2); ?></span>

                                                    <?php if ($sesion_usuario && $rol_usuario !== 'visitante'): ?>
                                                        <nav class="d-grid d-md-flex gap-2 producto-acciones">
                                                            <a href="../Controlador/ControladorProductos.php?accion=cargar&id=<?php echo $producto->getId(); ?>"
                                                               class="btn btn-outline-info btn-sm">
                                                                <i class="fas fa-edit me-1"></i> Editar
                                                            </a>
                                                            <a href="../Controlador/ControladorProductos.php?accion=eliminar&id=<?php echo $producto->getId(); ?>"
                                                               class="btn btn-outline-danger btn-sm">
                                                                <i class="fas fa-trash me-1"></i> Eliminar
                                                            </a>
                                                        </nav>
                                                    <?php endif; ?>
                                                </footer>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <?php if ($sesion_usuario && $rol_usuario !== 'visitante'): ?>
                        <nav class="d-grid d-md-flex gap-2 mt-4 categoria-acciones" aria-label="Acciones de categoría">
                            <a href="../Controlador/ControladorCategoria.php?accion=cargar&id=<?php echo $idCategoria; ?>" class="btn btn-outline-info">
                                <i class="fas fa-edit me-2"></i>Editar Categoría
                            </a>
                            <a href="../Controlador/ControladorCategoria.php?accion=eliminar&id=<?php echo $idCategoria; ?>" class="btn btn-outline-danger">
                                <i class="fas fa-trash me-2"></i>Eliminar Categoría
                            </a>
                            <a href="../Controlador/ControladorProductos.php?accion=cargarInserccion" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i>Insertar Nuevo Producto
                            </a>
                        </nav>
                    <?php endif; ?>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="alert alert-warning text-center" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> No hay productos disponibles.
            </p>
        <?php endif; ?>
    </main>

<?php include_once '../Compartido/footer.php'; ?>