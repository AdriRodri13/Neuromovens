<?php include '../Compartido/header.php';?>

    <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center vh-100 gap-4">
        <div class="login-container">
            <div class="login-header">Iniciar Sesión</div>

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
            <a href="registroUsuario.php" class="btn btn-info">Iniciar Registro</a>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Referencias a los elementos
            const $form = $('#form-login');
            const $usernameInput = $('#username');
            const $passwordInput = $('#password');
            const $usernameFeedback = $('#username-feedback');
            const $passwordFeedback = $('#password-feedback');
            const $btnLogin = $('#btn-login');

            // Función para mostrar error
            function setInvalid($input, $feedback, message) {
                $input.addClass('is-invalid').removeClass('is-valid');
                $feedback.text(message);
            }

            // Función para mostrar válido
            function setValid($input, $feedback) {
                $input.removeClass('is-invalid').addClass('is-valid');
                $feedback.text('');
            }

            // Función para limpiar validación
            function clearValidation($input, $feedback) {
                $input.removeClass('is-valid is-invalid');
                $feedback.text('');
            }

            // Validación del formulario al enviar
            $form.on('submit', function(event) {
                let esValido = true;

                // Comprobar nombre de usuario
                if ($usernameInput.val().trim() === '') {
                    setInvalid($usernameInput, $usernameFeedback, 'El nombre de usuario es obligatorio');
                    esValido = false;
                } else {
                    setValid($usernameInput, $usernameFeedback);
                }

                // Comprobar contraseña
                if ($passwordInput.val().trim() === '') {
                    setInvalid($passwordInput, $passwordFeedback, 'La contraseña es obligatoria');
                    esValido = false;
                } else {
                    setValid($passwordInput, $passwordFeedback);
                }

                // Si hay errores, prevenir envío
                if (!esValido) {
                    event.preventDefault();

                    // Enfocar primer campo con error
                    const $firstError = $('.is-invalid').first();
                    if ($firstError.length) {
                        $firstError.focus();
                    }

                    // Opcional: mostrar alerta si SweetAlert2 está disponible
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Campos requeridos',
                            text: 'Por favor, complete todos los campos',
                            confirmButtonText: 'Entendido'
                        });
                    }
                } else {
                    // Opcional: mostrar carga si SweetAlert2 está disponible
                    if (typeof Swal !== 'undefined') {
                        $btnLogin.prop('disabled', true).text('Entrando...');
                        Swal.fire({
                            title: 'Iniciando sesión...',
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                }
            });

            // Limpiar validación cuando el usuario comience a escribir
            $usernameInput.on('input', function() {
                if ($(this).hasClass('is-invalid') && $(this).val().trim() !== '') {
                    clearValidation($(this), $usernameFeedback);
                }
            });

            $passwordInput.on('input', function() {
                if ($(this).hasClass('is-invalid') && $(this).val().trim() !== '') {
                    clearValidation($(this), $passwordFeedback);
                }
            });

            // Focus inicial en el primer campo
            $usernameInput.focus();
        });
    </script>

<?php include '../Compartido/footer.php'; ?>