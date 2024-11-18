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
        <!-- Si el usuario está logueado, mostrar enlace para insertar nuevo producto -->
        <?php if ($sesion_usuario): ?>
            <a href="../Vistas/InsertarProducto.php?accion=insertar" class="btn-insert">Insertar Nuevo Producto</a>
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
                                <a href="../Controlador/ControladorProductos.php?accion=cargar&id=<?php echo $producto->getId(); ?>" class="btn-edit">Editar</a>
                                <a href="../Controlador/ControladorProductos.php?accion=eliminar&id=<?php echo $producto->getId(); ?>" class="btn-delete">Eliminar</a>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </section>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay productos disponibles.</p>
        <?php endif; ?>
    </main>

<?php include_once '../Compartido/footer.php'; ?>