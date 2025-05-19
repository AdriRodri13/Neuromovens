<?php include '../Compartido/header.php';?>

    <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center vh-100 gap-4">
        <div class="login-container">
            <div class="login-header">Iniciar Sesión</div>

            <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'registro_exitoso'): ?>
                <div class="alert alert-success">
                    <i class="fa-solid fa-check-circle"></i>
                    ¡Usuario registrado exitosamente! Ya puedes iniciar sesión con tus credenciales.
                </div>
            <?php endif; ?>

            <form id="form-login" action="../Controlador/ControladorUsuario.php" method="post">
                <input type="hidden" name="accion" value="iniciarSesion">

                <div class="form-group">
                    <label for="username">Nombre de Usuario</label>
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="nombre_usuario" class="form-control" id="username"
                           placeholder="Ingrese su usuario" required>
                    <div id="username-feedback" class="invalid-feedback"></div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña</label>
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="contra" class="form-control" id="password"
                           placeholder="Ingrese su contraseña" required>
                    <div id="password-feedback" class="invalid-feedback"></div>
                </div>

                <button type="submit" id="btn-login" class="btn btn-login">Entrar</button>
            </form>

        </div>
        <div class="login-container">
            <h3>Registrarse</h3>
            <p class="text-muted mb-3">¿No tienes una cuenta? Regístrate ahora</p>
            <a href="registroUsuario.php" class="btn btn-info">Iniciar Registro</a>
        </div>
    </div>

    <!-- Incluir archivo JavaScript separado -->
    <script src="../js/iniciarSesion.js"></script>

<?php include '../Compartido/footer.php'; ?>