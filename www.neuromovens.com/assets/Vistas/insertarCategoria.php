<?php include '../Compartido/header.php'; ?>
<style>
    .form-container {
        background-color: var(--color-blanco);
        padding: 30px;
        border-radius: 8px;
        box-shadow: var(--color-sombra);
        max-width: 600px;
        margin: 50px auto;
    }

    .form-container h2 {
        color: var(--color-principal);
        margin-bottom: 20px;
    }

    .form-container label {
        font-weight: bold;
        color: var(--color-oscuro);
    }

    .form-container input[type="text"],
    .form-container input[type="number"],
    .form-container textarea,
    .form-container input[type="file"] {
        width: 100%;
        padding: 10px;
        margin: 10px 0 20px 0;
        border-radius: 5px;
        border: 1px solid var(--color-gris-suave);
        box-sizing: border-box;
    }

    .form-container input[type="submit"] {
        background-color: var(--color-principal);
        color: var(--color-blanco);
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    .form-container input[type="submit"]:hover {
        background-color: var(--color-suave);
    }

    .form-container .form-group {
        margin-bottom: 20px;
    }
</style>

<div class="form-container">
    <h2>Insertar Nueva Categoría</h2>
    <form action="../Controlador/ControladorCategoria.php" method="post">
        <!-- Campo oculto para indicar la acción de inserción -->
        <input type="hidden" name="accion" value="insertar">

        <!-- Campo para el nombre de la categoría -->
        <div class="form-group">
            <label for="nombre_categoria">Nombre de la Categoría:</label>
            <input type="text" id="nombre_categoria" name="nombreCategoria" required>
        </div>

        <!-- Botón de envío -->
        <input type="submit" value="Insertar Categoría">
    </form>
</div>
<?php include '../Compartido/footer.php'; ?>
