<?php include '../Compartido/header.php';?>

    <div class="d-flex flex-column flex-sm-row justify-content-center align-items-center vh-100 gap-4">
        <div class="login-container">
            <div class="login-header">Registrarse</div>

            <?php if(isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <i class="fa-solid fa-exclamation-triangle"></i>
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
            <?php endif; ?>

            <form action="../Controlador/ControladorUsuario.php" method="post" id="registroForm">
                <input type="hidden" name="accion" value="registro">

                <div class="form-group">
                    <label for="username">Nombre de Usuario <span class="text-danger">*</span></label>
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="nombre_usuario" class="form-control" id="username"
                           placeholder="Ingrese su usuario (mínimo 3 caracteres)"
                           value="<?php echo isset($_GET['nombre']) ? htmlspecialchars($_GET['nombre']) : ''; ?>"
                           required minlength="3">
                    <div id="username-error" class="text-danger" style="display: none; font-size: 0.875em; margin-top: 5px;">
                        El nombre debe tener al menos 3 caracteres
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <i class="fa-solid fa-envelope"></i>
                    <input type="email" name="email" class="form-control" id="email"
                           placeholder="Ingrese su email"
                           value="<?php echo isset($_GET['email']) ? htmlspecialchars($_GET['email']) : ''; ?>"
                           required>
                    <div id="email-error" class="text-danger" style="display: none; font-size: 0.875em; margin-top: 5px;">
                        Por favor, ingrese un email válido
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">Contraseña <span class="text-danger">*</span></label>
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="contra" class="form-control" id="password"
                           placeholder="Ingrese su contraseña (mínimo 6 caracteres)"
                           required minlength="6">
                    <div id="password-error" class="text-danger" style="display: none; font-size: 0.875em; margin-top: 5px;">
                        La contraseña debe tener al menos 6 caracteres
                    </div>
                </div>

                <button type="submit" class="btn btn-login" id="btn-registro">Registrarse</button>
            </form>

            <div class="text-center mt-3">
                <p>¿Ya tienes una cuenta? <a href="IniciarSesion.php" class="text-primary">Inicia sesión aquí</a></p>
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

    <style>
        /* Estilos adicionales */
        .form-control.is-invalid {
            border-color: #dc3545;
            box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        }

        .form-control.is-valid {
            border-color: #28a745;
            box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
        }

        .alert {
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-danger {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }

        .text-danger {
            color: #dc3545 !important;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 500;
            margin-bottom: 0.5rem;
            display: block;
        }

        .btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }
    </style>

<?php include '../Compartido/footer.php'; ?>