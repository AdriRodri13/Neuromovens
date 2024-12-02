<?php include '../Compartido/header.php';?>

<div class="d-flex flex-column flex-sm-row justify-content-center align-items-center vh-100 gap-4">
    <div class="login-container">
        <div class="login-header">Iniciar Sesión</div>

        <form  action="../Controlador/ControladorUsuario.php" method="post">
            <input type="hidden" name="accion" value="iniciarSesion">
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <i class="fa-solid fa-user"></i> <!-- Ícono de usuario -->
                <input type="text" name="nombre_usuario" class="form-control" id="username" placeholder="Ingrese su usuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <i class="fa-solid fa-lock"></i> <!-- Ícono de candado -->
                <input type="password" name="contra" class="form-control" id="password" placeholder="Ingrese su contraseña" required>
            </div>

            <button type="submit" class="btn btn-login">Entrar</button>
        </form>

    </div>
    <div class="login-container">
        <h3>Registrarse</h3>
        <a href="registroUsuario.php" class="btn btn-info">Iniciar Registro</a>
    </div>

</div>

<script src="../js/comprobacion.js"></script>



<?php include '../Compartido/footer.php'; ?>
