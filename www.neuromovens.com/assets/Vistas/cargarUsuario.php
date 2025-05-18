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

    <style>
        @media screen and (max-width: 768px){
            #contrasena{
                font-size: 11px;
            }
        }
    </style>

    <body>

    <div class="form-container">
        <h2>Actualizar Usuario</h2>
        <form id="form-actualizar-usuario" action="../Controlador/ControladorUsuario.php" method="post">
            <!-- Campo oculto para indicar la acción de actualización -->
            <input type="hidden" name="accion" value="actualizar">

            <!-- Campo oculto para el ID del usuario -->
            <input type="hidden" name="usuario[id]" value="<?= $usuario->getId(); ?>">

            <!-- Campo para el nombre de usuario -->
            <div class="form-group">
                <label for="nombre_usuario">Nombre de Usuario:</label>
                <input type="text" id="nombre_usuario" name="usuario[nombre_usuario]"
                       class="form-control" value="<?= $usuario->getNombreUsuario(); ?>" required>
                <div id="nombre-usuario-feedback" class="invalid-feedback"></div>
                <small id="nombre-usuario-contador" class="form-text text-muted">0/30 caracteres</small>
            </div>

            <!-- Campo para el email -->
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="usuario[email]"
                       class="form-control" value="<?= $usuario->getEmail(); ?>" required>
                <div id="email-feedback" class="invalid-feedback"></div>
                <small id="email-helper" class="form-text text-muted">Formato: usuario@dominio.com</small>
            </div>

            <!-- Campo para la contraseña (no es un input, sino un párrafo) -->
            <div class="form-group">
                <label for="contrasena">Contraseña:</label>
                <p id="contrasena" class="form-control-plaintext"><?= $usuario->getContra(); ?></p>
                <input type="hidden" name="usuario[contra]" value="<?= $usuario->getContra(); ?>">
            </div>

            <!-- Mostrar el campo para el rol solo si el usuario no es jefe -->
            <?php if ($usuario->getRol()->name != 'jefe'): ?>
                <!-- Campo para el rol -->
                <div class="form-group">
                    <label for="rol">Rol:</label>
                    <select id="rol" name="usuario[rol]" class="form-control" required>
                        <option value="">Seleccione un rol</option>
                        <option value="administrador" <?= ($usuario->getRol()->name == 'administrador') ? 'selected' : ''; ?>>Administrador</option>
                        <option value="visitante" <?= ($usuario->getRol()->name == 'visitante') ? 'selected' : ''; ?>>Visitante</option>
                    </select>
                    <div id="rol-feedback" class="invalid-feedback"></div>
                </div>
            <?php else : ?>
                <input type="hidden" name="usuario[rol]" value="<?= ($usuario->getRol()->name); ?>">
                <div class="form-group">
                    <label>Rol:</label>
                    <p class="form-control-plaintext">
                        <span class="badge bg-primary">Jefe</span>
                        <small class="text-muted">(No modificable)</small>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Fecha de última modificación -->
            <div class="form-group">
                <label>Última modificación:</label>
                <div id="fecha-modificacion" class="form-control-plaintext"></div>
            </div>

            <!-- Botones de acción -->
            <div class="d-flex justify-content-between mt-4">
                <button type="button" id="btn-cancelar" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </button>
                <button type="submit" id="btn-actualizar" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Usuario
                </button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // 1. Variables jQuery - Referencias cacheadas
            const $form = $('#form-actualizar-usuario');
            const $nombreUsuarioInput = $('#nombre_usuario');
            const $nombreUsuarioFeedback = $('#nombre-usuario-feedback');
            const $nombreUsuarioContador = $('#nombre-usuario-contador');
            const $emailInput = $('#email');
            const $emailFeedback = $('#email-feedback');
            const $emailHelper = $('#email-helper');
            const $rolSelect = $('#rol');
            const $rolFeedback = $('#rol-feedback');
            const $btnCancelar = $('#btn-cancelar');
            const $fechaModificacion = $('#fecha-modificacion');

            // 2. Funciones auxiliares para validación
            function setInvalid($input, $feedback, message) {
                $input.addClass('is-invalid').removeClass('is-valid');
                $feedback.text(message);
            }

            function setValid($input, $feedback) {
                $input.removeClass('is-invalid').addClass('is-valid');
                $feedback.text('');
            }

            function isFormValid() {
                return $('.is-invalid').length === 0;
            }

            // 3. Mostrar fecha de modificación actual
            function mostrarFechaModificacion() {
                const fechaActual = new Date();
                const opciones = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                $fechaModificacion.text(fechaActual.toLocaleDateString('es-ES', opciones));
            }

            // 4. Validación del nombre de usuario
            $nombreUsuarioInput.on('input', function() {
                const valor = $(this).val().trim();
                const longitud = valor.length;

                // Actualizar contador
                $nombreUsuarioContador.text(`${longitud}/30 caracteres`);

                // Cambiar color del contador según longitud
                $nombreUsuarioContador.removeClass('text-muted text-success text-warning text-danger');

                if (longitud > 25) {
                    $nombreUsuarioContador.addClass('text-warning');
                } else if (longitud > 0) {
                    $nombreUsuarioContador.addClass('text-success');
                } else {
                    $nombreUsuarioContador.addClass('text-muted');
                }

                // Validaciones
                if (longitud === 0) {
                    setInvalid($nombreUsuarioInput, $nombreUsuarioFeedback, 'El nombre de usuario es obligatorio');
                } else if (longitud < 3) {
                    setInvalid($nombreUsuarioInput, $nombreUsuarioFeedback, 'El nombre debe tener al menos 3 caracteres');
                } else if (longitud > 30) {
                    setInvalid($nombreUsuarioInput, $nombreUsuarioFeedback, 'El nombre no puede exceder los 30 caracteres');
                } else if (!/^[a-zA-Z0-9_]+$/.test(valor)) {
                    setInvalid($nombreUsuarioInput, $nombreUsuarioFeedback, 'Solo se permiten letras, números y guiones bajos');
                } else {
                    setValid($nombreUsuarioInput, $nombreUsuarioFeedback);
                }
            });

            // 5. Validación del email
            $emailInput.on('input blur', function() {
                const valor = $(this).val().trim();
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                // Cambiar color del helper según validez
                $emailHelper.removeClass('text-muted text-success text-danger');

                if (valor === '') {
                    setInvalid($emailInput, $emailFeedback, 'El email es obligatorio');
                    $emailHelper.addClass('text-muted');
                } else if (!emailRegex.test(valor)) {
                    setInvalid($emailInput, $emailFeedback, 'Por favor, introduce un email válido');
                    $emailHelper.addClass('text-danger');
                } else if (valor.length > 100) {
                    setInvalid($emailInput, $emailFeedback, 'El email no puede exceder los 100 caracteres');
                    $emailHelper.addClass('text-danger');
                } else {
                    setValid($emailInput, $emailFeedback);
                    $emailHelper.addClass('text-success');
                }
            });

            // 6. Validación del rol (solo si existe el select)
            if ($rolSelect.length > 0) {
                $rolSelect.on('change', function() {
                    const valor = $(this).val();

                    if (valor === '') {
                        setInvalid($rolSelect, $rolFeedback, 'Debe seleccionar un rol');
                    } else if (!['administrador', 'visitante'].includes(valor)) {
                        setInvalid($rolSelect, $rolFeedback, 'Rol no válido');
                    } else {
                        setValid($rolSelect, $rolFeedback);
                    }
                });
            }

            // 7. Validación del formulario al enviar
            $form.on('submit', function(event) {
                // Disparar todas las validaciones
                $nombreUsuarioInput.trigger('input');
                $emailInput.trigger('blur');
                if ($rolSelect.length > 0) {
                    $rolSelect.trigger('change');
                }

                // Verificar si hay errores
                if (!isFormValid()) {
                    event.preventDefault();

                    // Mostrar mensaje de error
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            text: 'Por favor, corrija los errores antes de continuar',
                            confirmButtonText: 'Entendido'
                        });
                    } else {
                        alert('Por favor, corrija los errores antes de continuar');
                    }

                    // Hacer scroll al primer error
                    const $firstError = $('.is-invalid').first();
                    if ($firstError.length) {
                        $('html, body').animate({
                            scrollTop: $firstError.offset().top - 100
                        }, 500);
                    }
                } else {
                    // Mostrar indicador de carga
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Actualizando usuario',
                            text: 'Procesando los cambios...',
                            didOpen: () => {
                                Swal.showLoading();
                            },
                            allowOutsideClick: false,
                            allowEscapeKey: false
                        });
                    }
                }
            });

            // 8. Botón cancelar con confirmación
            $btnCancelar.on('click', function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '¿Estás seguro?',
                        text: "Los cambios no guardados se perderán",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, salir',
                        cancelButtonText: 'No, continuar editando'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../Controlador/ControladorUsuario.php';
                        }
                    });
                } else {
                    if (confirm('¿Estás seguro? Los cambios no guardados se perderán')) {
                        window.location.href = '../Controlador/ControladorUsuario.php';
                    }
                }
            });

            // 9. Validación en tiempo real mientras escribe (debounced)
            let emailTimeout;
            $emailInput.on('input', function() {
                clearTimeout(emailTimeout);
                emailTimeout = setTimeout(() => {
                    $(this).trigger('blur');
                }, 500);
            });

            // 10. Función de inicialización
            function inicializar() {
                // Mostrar fecha actual
                mostrarFechaModificacion();

                // Disparar validaciones iniciales
                $nombreUsuarioInput.trigger('input');
                $emailInput.trigger('blur');
                if ($rolSelect.length > 0) {
                    $rolSelect.trigger('change');
                }

                // Focus en el primer campo
                $nombreUsuarioInput.focus();
            }

            // 11. Ejecutar inicialización
            inicializar();

        });
    </script>

    </body>
<?php
else:
    // Si $usuario no es una instancia de Usuario, mostrar un mensaje de error o redirigir
    echo "El usuario no es válido.";
endif;
?>

<?php
include '../Compartido/footer.php';
?>