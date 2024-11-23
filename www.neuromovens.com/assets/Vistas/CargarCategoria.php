<?php

include '../Compartido/header.php'; ?>


<?php if (isset ($categoria) && $categoria instanceof Categoria) : ?>

    <body>
    <div class="form-container">
        <h2>Actualizar Categoria</h2>
        <form action="../Controlador/ControladorCategoria.php" method="post">
            <input type="hidden" name="accion" value="actualizar">
            <input type="hidden" name="id" value="<?= $categoria->getIdCategoria(); ?>">
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="<?= $categoria->getNombreCategoria(); ?>" required>
            </div>
            <input type="submit" value="actualizar">
        </form>
    </div>
    </body>

<?php endif;?>

<?php include '../Compartido/footer.php';
