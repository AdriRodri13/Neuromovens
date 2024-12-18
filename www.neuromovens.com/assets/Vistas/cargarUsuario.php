<?php

include '../Compartido/header.php';
use Entidades\Rol;
use Entidades\Usuario;
require '../Entidades/Rol.php';
require '../Entidades/Usuario.php';
$usuario = unserialize($_SESSION['usuarioUpdate']);
?>

<?php
if ($usuario instanceof Usuario):
    ?>
    <body>

    <div class="form-container">
        <h2>Actualizar Usuario</h2>
        <form action="../Controlador/ControladorUsuario.php" method="post" >
            <!-- Campo oculto para indicar la acción de actualización -->
            <input type="hidden" name="accion" value="actualizar">

            <!-- Campo oculto para el ID del usuario -->
            <input type="hidden" name="usuario[id]" value="<?= $usuario->getId(); ?>">

            <!-- Campo para el nombre de usuario -->
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario:</label>
                <input type="text" id="nombre_usuario" name="usuario[nombre_usuario]" value="<?= $usuario->getNombreUsuario(); ?>" required>
            </div>

            <!-- Campo para el email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="text" id="email" name="usuario[email]" value="<?= $usuario->getEmail(); ?>" required>
            </div>

            <!-- Campo para la contraseña (no es un input, sino un párrafo) -->
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <p id="contrasena"><?= $usuario->getContra(); ?></p>
                <input type="hidden" name="usuario[contra]" value="<?= $usuario->getContra(); ?>">
            </div>

            <!-- Mostrar el campo para el rol solo si el usuario no es jefe -->
            <?php if ($usuario->getRol()->name != 'jefe'): ?>
                <!-- Campo para el rol -->
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="usuario[rol]" required>
                        <option value="administrador" <?= ($usuario->getRol()->name == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                        <option value="visitante" <?= ($usuario->getRol()->name == 'visitante') ? 'selected' : ''; ?>>Visitante</option>
                    </select>
                </div>
            <?php else : ?>
                <input type="hidden" name="usuario[rol]" value="<?= ($usuario->getRol()->name); ?>">
            <?php endif; ?>

            <!-- Botón de envío -->
            <input type="submit" value="Actualizar Usuario">
        </form>
    </div>

    </body>
<?php
else:
    // Si $usuario no es una instancia de Usuario, mostrar un mensaje de error o redirigir
    echo "El usuario no es válido.";
endif;
?>


<?php

include '../Compartido/footer.php';
