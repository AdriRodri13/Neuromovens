<?php
use Entidades\Producto;

include '../Compartido/header.php';
require '../Entidades/Producto.php';
require '../Entidades/Categoria.php';

// Verificación de sesión de usuario
$sesion_usuario = isset($_SESSION['usuario']) && $_SESSION['usuario'] === true;
?>

    <h1 class="title">Nuestros productos</h1>

    <main>

        <?php if ($sesion_usuario): ?>
            <div>
                <a href="insertarCategoria.php"
                   class="btn btn-success">Insertar Nueva Categoria</a>
                <a href="#" class="btn btn-info">Mostrar todos los Productos</a>
            </div>
        <?php endif; ?>

        <?php
        // Deserializar los productos organizados por categoría desde la sesión
        $productosPorCategoria = isset($_SESSION['productos_por_categoria']) ? unserialize($_SESSION['productos_por_categoria']) : [];
        ?>

        <!-- Iterar sobre las categorías para mostrar sus productos -->
        <?php if (!empty($productosPorCategoria)): ?>
            <?php foreach ($productosPorCategoria as $idCategoria => $categoriaData): ?>
                <section class="category-section">
                    <h2><?php echo htmlspecialchars($categoriaData['nombre_categoria']); ?></h2>
                    <?php if ($sesion_usuario): ?>
                    <h2>ID CATEGORIA: <?php echo htmlspecialchars($categoriaData['id_categoria']);?></h2>
                    <?php endif; ?>

                    <?php foreach ($categoriaData['productos'] as $producto): ?>
                        <div class="product-card">
                            <div class="product-image">
                                <img src="<?php echo htmlspecialchars($producto->getImagenUrl()); ?>" alt="Imagen del Producto">
                                <div class="product-price">€<?php echo number_format($producto->getPrecio(), 2); ?></div>
                            </div>
                            <div class="product-info">
                                <h3><?php echo htmlspecialchars($producto->getNombre()); ?></h3>
                                <p><?php echo htmlspecialchars($producto->getDescripcion()); ?></p>
                            </div>

                            <!-- Mostrar botones de editar y eliminar si el usuario está logueado -->
                            <?php if ($sesion_usuario): ?>
                                <div>
                                    <a href="../Controlador/ControladorProductos.php?accion=cargar&id=<?php echo $producto->getId(); ?>"
                                       class="btn btn-info">Editar</a>
                                    <a href="../Controlador/ControladorProductos.php?accion=eliminar&id=<?php echo $producto->getId(); ?>"
                                       class="btn btn-danger">Eliminar</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <?php if ($sesion_usuario): ?>
                        <div>
                            <a href="../Controlador/ControladorCategoria.php?accion=cargar&id=<?php echo $idCategoria; ?>"
                               class="btn btn-info">Editar Categoria</a>
                            <a href="../Controlador/ControladorCategoria.php?accion=eliminar&id=<?php echo $idCategoria; ?>"
                               class="btn btn-danger">Eliminar Categoria</a>
                            <a href="../Vistas/InsertarProducto.php?accion=insertar" class="btn btn-success">Insertar
                                Nuevo Producto</a>
                        </div>
                    <?php endif; ?>

                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>
    </main>

<?php include_once '../Compartido/footer.php'; ?>