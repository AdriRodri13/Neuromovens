<?php include '../Compartido/header.php';?>

    <div class="container py-4 py-md-5">
        <div class="row justify-content-center align-items-center my-3 my-md-4 g-4">
            <!-- Columna de inicio de sesión -->
            <div class="col-12 col-md-6 col-lg-5">
                <div class="login-container p-4 p-md-5 mx-auto" style="max-width: 450px;">
                    <div class="login-header mb-4 text-center">Iniciar Sesión</div>

                    <?php if(isset($_GET['mensaje']) && $_GET['mensaje'] == 'registro_exitoso'): ?>
                        <div class="alert alert-success d-flex align-items-center">
                            <i class="fa-solid fa-check-circle me-2"></i>
                            <span>¡Usuario registrado exitosamente! Ya puedes iniciar sesión con tus credenciales.</span>
                        </div>
                    <?php endif; ?>

                    <form id="form-login" action="../Controlador/ControladorUsuario.php" method="post">
                        <input type="hidden" name="accion" value="iniciarSesion">

                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Nombre de Usuario</label>
                            <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-user"></i>
                            </span>
                                <input type="text" name="nombre_usuario" class="form-control" id="username"
                                       placeholder="Ingrese su usuario" required>
                            </div>
                            <div id="username-feedback" class="invalid-feedback"></div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="form-label">Contraseña</label>
                            <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                                <input type="password" name="contra" class="form-control" id="password"
                                       placeholder="Ingrese su contraseña" required>
                            </div>
                            <div id="password-feedback" class="invalid-feedback"></div>
                        </div>

                        <button type="submit" id="btn-login" class="btn btn-login w-100 py-2 mt-2">Entrar</button>
                    </form>
                </div>
            </div>

            <!-- Columna de registro -->
            <div class="col-12 col-md-6 col-lg-5">
                <div class="login-container p-4 p-md-5 text-center mx-auto" style="max-width: 450px;">
                    <h3 class="mb-3">Registrarse</h3>
                    <p class="text-muted mb-4">¿No tienes una cuenta? Regístrate ahora</p>
                    <a href="registroUsuario.php" class="btn btn-info py-2 px-4">Iniciar Registro</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir archivo JavaScript separado -->
    <script src="../js/iniciarSesion.js"></script>

<?php include '../Compartido/footer.php'; ?>