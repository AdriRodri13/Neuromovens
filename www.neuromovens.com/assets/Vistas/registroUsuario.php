<?php include '../Compartido/header.php';?>

    <div class="container py-4 py-md-5">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8 col-xl-6 mb-4">
                <div class="login-container p-4 p-md-5 mx-auto">
                    <div class="login-header mb-4 text-center">Registrarse</div>

                    <?php if(isset($_GET['error'])): ?>
                        <div class="alert alert-danger d-flex align-items-center">
                            <i class="fa-solid fa-exclamation-triangle me-2"></i>
                            <div>
                                <?php
                                switch($_GET['error']) {
                                    case 'campos_vacios':
                                        echo 'Todos los campos son obligatorios';
                                        break;
                                    case 'email_invalido':
                                        echo 'El email no tiene un formato válido';
                                        break;
                                    case 'nombre_muy_corto':
                                        echo 'El nombre de usuario debe tener al menos 3 caracteres';
                                        break;
                                    case 'contra_muy_corta':
                                        echo 'La contraseña debe tener al menos 6 caracteres';
                                        break;
                                    case 'usuario_existe':
                                        echo 'El nombre de usuario o email ya están registrados';
                                        break;
                                    case 'error_insercion':
                                        echo 'Error al registrar el usuario. Intente nuevamente';
                                        break;
                                    case 'error_interno':
                                        echo 'Error interno del servidor. Contacte al administrador';
                                        break;
                                    default:
                                        echo 'Error desconocido. Intente nuevamente';
                                }
                                ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <form action="../Controlador/ControladorUsuario.php" method="post" id="registroForm">
                        <input type="hidden" name="accion" value="registro">

                        <div class="form-group mb-3">
                            <label for="username" class="form-label">Nombre de Usuario <span class="text-danger">*</span></label>
                            <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-user"></i>
                            </span>
                                <input type="text" name="nombre_usuario" class="form-control" id="username"
                                       placeholder="Ingrese su usuario (mínimo 3 caracteres)"
                                       value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : ''; ?>"
                                       required minlength="3">
                            </div>
                            <div id="username-error" class="text-danger" style="display: none; font-size: 0.875em; margin-top: 5px;">
                                El nombre debe tener al menos 3 caracteres
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-envelope"></i>
                            </span>
                                <input type="email" name="email" class="form-control" id="email"
                                       placeholder="Ingrese su email"
                                       value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>"
                                       required>
                            </div>
                            <div id="email-error" class="text-danger" style="display: none; font-size: 0.875em; margin-top: 5px;">
                                Por favor, ingrese un email válido
                            </div>
                        </div>

                        <div class="form-group mb-4">
                            <label for="password" class="form-label">Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                            <span class="input-group-text">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                                <input type="password" name="contra" class="form-control" id="password"
                                       placeholder="Ingrese su contraseña (mínimo 6 caracteres)"
                                       required minlength="6">
                            </div>
                            <div id="password-error" class="text-danger" style="display: none; font-size: 0.875em; margin-top: 5px;">
                                La contraseña debe tener al menos 6 caracteres
                            </div>
                        </div>

                        <button type="submit" class="btn btn-login w-100 py-2" id="btn-registro">Registrarse</button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="mb-0">¿Ya tienes una cuenta? <a href="IniciarSesion.php" class="text-primary">Inicia sesión aquí</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Referencias a los elementos
            const $form = $('#registroForm');
            const $usernameInput = $('#username');
            const $emailInput = $('#email');
            const $passwordInput = $('#password');
            const $btnRegistro = $('#btn-registro');

            // Regex para validar email
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            // Función para mostrar error
            function setInvalid($input, message) {
                $input.addClass('is-invalid').removeClass('is-valid');
                $input.siblings('.text-danger').text(message).show();
            }

            // Función para mostrar válido
            function setValid($input) {
                $input.removeClass('is-invalid').addClass('is-valid');
                $input.siblings('.text-danger').hide();
            }

            // Función para limpiar validación
            function clearValidation($input) {
                $input.removeClass('is-valid is-invalid');
                $input.siblings('.text-danger').hide();
            }

            // Validación en tiempo real del nombre de usuario
            $usernameInput.on('input', function() {
                const username = $(this).val().trim();

                if (username.length > 0 && username.length < 3) {
                    setInvalid($(this), 'El nombre debe tener al menos 3 caracteres');
                } else if (username.length >= 3) {
                    setValid($(this));
                } else {
                    clearValidation($(this));
                }
            });

            // Validación en tiempo real del email
            $emailInput.on('input', function() {
                const email = $(this).val().trim();

                if (email.length > 0 && !emailRegex.test(email)) {
                    setInvalid($(this), 'Por favor, ingrese un email válido');
                } else if (email.length > 0) {
                    setValid($(this));
                } else {
                    clearValidation($(this));
                }
            });

            // Validación en tiempo real de la contraseña
            $passwordInput.on('input', function() {
                const password = $(this).val();

                if (password.length > 0 && password.length < 6) {
                    setInvalid($(this), 'La contraseña debe tener al menos 6 caracteres');
                } else if (password.length >= 6) {
                    setValid($(this));
                } else {
                    clearValidation($(this));
                }
            });

            // Validación al enviar el formulario
            $form.on('submit', function(e) {
                let esValido = true;

                // Validar nombre de usuario
                const username = $usernameInput.val().trim();
                if (username.length < 3) {
                    setInvalid($usernameInput, 'El nombre debe tener al menos 3 caracteres');
                    esValido = false;
                }

                // Validar email
                const email = $emailInput.val().trim();
                if (!emailRegex.test(email)) {
                    setInvalid($emailInput, 'Por favor, ingrese un email válido');
                    esValido = false;
                }

                // Validar contraseña
                const password = $passwordInput.val();
                if (password.length < 6) {
                    setInvalid($passwordInput, 'La contraseña debe tener al menos 6 caracteres');
                    esValido = false;
                }

                if (!esValido) {
                    e.preventDefault();

                    // Enfocar primer campo con error
                    const $firstError = $('.is-invalid').first();
                    if ($firstError.length) {
                        $firstError.focus();
                    }

                    // Mostrar alerta si SweetAlert2 está disponible
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Formulario incompleto',
                            text: 'Por favor, corrija los errores antes de continuar',
                            confirmButtonText: 'Entendido'
                        });
                    }
                } else {
                    // Deshabilitar botón y mostrar carga
                    $btnRegistro.prop('disabled', true).text('Registrando...');

                    // Mostrar carga si SweetAlert2 está disponible
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Registrando usuario...',
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                }
            });

            // Focus inicial en el primer campo
            $usernameInput.focus();
        });
    </script>


<?php include '../Compartido/footer.php'; ?>