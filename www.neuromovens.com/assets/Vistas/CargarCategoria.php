<?php use Entidades\Categoria; include '../Compartido/header.php'; ?>

<?php if (isset($categoria) && $categoria instanceof Categoria) : ?>
    <body>
    <div class="form-container">
        <h2>Actualizar Categoría</h2>

        <form id="form-actualizar-categoria" action="../Controlador/ControladorCategoria.php" method="post">
            <input type="hidden" name="accion" value="actualizar">
            <input type="hidden" name="id" value="<?= $categoria->getIdCategoria(); ?>">

            <div class="form-group mb-3">
                <label for="nombre">Nombre de la categoría:</label>
                <input type="text" id="nombre" name="nombre" class="form-control"
                       value="<?= $categoria->getNombreCategoria(); ?>" required>
                <div id="nombre-feedback" class="invalid-feedback"></div>
                <small id="nombre-contador" class="form-text text-muted">0/50 caracteres</small>
            </div>

            <div class="form-group mb-3">
                <label>Fecha de modificación:</label>
                <div id="fecha-modificacion" class="form-control-plaintext">
                    <!-- Se rellenará con jQuery -->
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" id="btn-cancelar" class="btn btn-secondary">Cancelar</button>
                <button type="submit" id="btn-actualizar" class="btn btn-primary">Actualizar Categoría</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // 1. Configuración inicial y variables jQuery
            const $form = $('#form-actualizar-categoria');
            const $nombreInput = $('#nombre');
            const $nombreFeedback = $('#nombre-feedback');
            const $nombreContador = $('#nombre-contador');
            const $btnCancelar = $('#btn-cancelar');
            const $fechaModificacion = $('#fecha-modificacion');

            // 2. Mostrar fecha actual usando jQuery y Date
            function mostrarFechaActual() {
                const fechaActual = new Date();
                const opciones = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };

                // Usar jQuery para establecer el texto
                $fechaModificacion.text(fechaActual.toLocaleDateString('es-ES', opciones));
            }

            // 3. Funciones auxiliares para la validación
            function setInvalid($input, $feedback, message) {
                $input.addClass('is-invalid').removeClass('is-valid');
                $feedback.text(message);
            }

            function setValid($input, $feedback) {
                $input.removeClass('is-invalid').addClass('is-valid');
                $feedback.text('');
            }

            function isFormValid() {
                // Usar jQuery para buscar elementos inválidos
                return $('.is-invalid').length === 0;
            }

            // 4. Validación en tiempo real con evento input (jQuery)
            $nombreInput.on('input', function() {
                const valor = $(this).val().trim();
                const longitud = valor.length;

                // Actualizar contador usando jQuery
                $nombreContador.text(`${longitud}/50 caracteres`);

                // Cambiar clases CSS usando jQuery para el color según longitud
                $nombreContador.removeClass('text-muted text-success text-danger');

                if (longitud > 40) {
                    $nombreContador.addClass('text-danger');
                } else if (longitud > 0) {
                    $nombreContador.addClass('text-success');
                } else {
                    $nombreContador.addClass('text-muted');
                }

                // Validar longitud usando las funciones auxiliares
                if (longitud === 0) {
                    setInvalid($nombreInput, $nombreFeedback, 'El nombre de la categoría es obligatorio');
                } else if (longitud < 3) {
                    setInvalid($nombreInput, $nombreFeedback, 'El nombre debe tener al menos 3 caracteres');
                } else if (longitud > 50) {
                    setInvalid($nombreInput, $nombreFeedback, 'El nombre no puede exceder los 50 caracteres');
                } else {
                    setValid($nombreInput, $nombreFeedback);
                }
            });

            // 5. Validación al enviar el formulario (jQuery)
            $form.on('submit', function(event) {
                // Disparar validación usando jQuery
                $nombreInput.trigger('input');

                // Comprobar si hay errores
                if (!isFormValid()) {
                    event.preventDefault();

                    // Usar SweetAlert2 con jQuery si está disponible
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            text: 'Por favor, corrija los errores antes de continuar'
                        });
                    } else {
                        alert('Por favor, corrija los errores antes de continuar');
                    }
                } else if (typeof Swal !== 'undefined') {
                    // Mostrar mensaje de éxito/carga con SweetAlert2
                    Swal.fire({
                        title: 'Guardando cambios',
                        text: 'Procesando su solicitud...',
                        didOpen: () => {
                            Swal.showLoading();
                        },
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    });
                }
            });

            // 6. Botón cancelar con confirmación (jQuery)
            $btnCancelar.on('click', function() {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: '¿Está seguro?',
                        text: "Los cambios no guardados se perderán",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Sí, salir',
                        cancelButtonText: 'No, continuar editando'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Usar location de JavaScript (no hay equivalente jQuery directo)
                            window.location.href = '../Controlador/ControladorCategoria.php';
                        }
                    });
                } else {
                    if (confirm('¿Está seguro? Los cambios no guardados se perderán')) {
                        window.location.href = '../Controlador/ControladorCategoria.php';
                    }
                }
            });

            // 7. Inicialización al cargar la página
            function inicializar() {
                // Mostrar fecha actual
                mostrarFechaActual();

                // Disparar validación inicial para actualizar el contador
                $nombreInput.trigger('input');
            }

            // 8. Ejecutar inicialización
            inicializar();


        });
    </script>
    </body>
<?php endif; ?>

<?php include '../Compartido/footer.php'; ?>