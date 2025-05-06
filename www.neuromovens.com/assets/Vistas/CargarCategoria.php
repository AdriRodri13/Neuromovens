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
                    <!-- Se rellenará con JavaScript -->
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="button" id="btn-cancelar" class="btn btn-secondary">Cancelar</button>
                <button type="submit" id="btn-actualizar" class="btn btn-primary">Actualizar Categoría</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // 1. Uso del objeto Date para mostrar la fecha actual
            const fechaActual = new Date();
            const opciones = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            document.getElementById('fecha-modificacion').textContent = fechaActual.toLocaleDateString('es-ES', opciones);

            // 2. Validación del formulario y eventos
            const form = document.getElementById('form-actualizar-categoria');
            const nombreInput = document.getElementById('nombre');
            const nombreFeedback = document.getElementById('nombre-feedback');
            const nombreContador = document.getElementById('nombre-contador');
            const btnCancelar = document.getElementById('btn-cancelar');

            // 3. Validación en tiempo real con evento input
            nombreInput.addEventListener('input', function() {
                const valor = this.value.trim();
                const longitud = valor.length;

                // Actualizar contador
                nombreContador.textContent = `${longitud}/50 caracteres`;

                // Cambiar color según longitud
                if (longitud > 40) {
                    nombreContador.classList.remove('text-muted', 'text-success');
                    nombreContador.classList.add('text-danger');
                } else if (longitud > 0) {
                    nombreContador.classList.remove('text-muted', 'text-danger');
                    nombreContador.classList.add('text-success');
                } else {
                    nombreContador.classList.remove('text-success', 'text-danger');
                    nombreContador.classList.add('text-muted');
                }

                // Validar longitud
                if (longitud === 0) {
                    setInvalid(nombreInput, nombreFeedback, 'El nombre de la categoría es obligatorio');
                } else if (longitud < 3) {
                    setInvalid(nombreInput, nombreFeedback, 'El nombre debe tener al menos 3 caracteres');
                } else if (longitud > 50) {
                    setInvalid(nombreInput, nombreFeedback, 'El nombre no puede exceder los 50 caracteres');
                } else {
                    setValid(nombreInput, nombreFeedback);
                }
            });

            // 4. Validación al enviar el formulario
            form.addEventListener('submit', function(event) {
                // Trigger de la validación
                const inputEvent = new Event('input', {
                    bubbles: true,
                    cancelable: true,
                });
                nombreInput.dispatchEvent(inputEvent);

                // Comprobar si hay errores
                if (!isFormValid()) {
                    event.preventDefault();

                    // Usar SweetAlert2 si está disponible
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
                    // Mostrar mensaje de éxito/carga
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

            // 5. Botón cancelar con confirmación
            btnCancelar.addEventListener('click', function() {
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
                            window.location.href = '../Controlador/ControladorCategoria.php';
                        }
                    });
                } else {
                    if (confirm('¿Está seguro? Los cambios no guardados se perderán')) {
                        window.location.href = '../Controlador/ControladorCategoria.php';
                    }
                }
            });

            // 6. Trigger inicial para actualizar el contador
            nombreInput.dispatchEvent(new Event('input'));

            // Funciones auxiliares para la validación
            function setInvalid(input, feedback, message) {
                input.classList.add('is-invalid');
                input.classList.remove('is-valid');
                feedback.textContent = message;
            }

            function setValid(input, feedback) {
                input.classList.remove('is-invalid');
                input.classList.add('is-valid');
                feedback.textContent = '';
            }

            function isFormValid() {
                return !document.querySelectorAll('.is-invalid').length;
            }
        });
    </script>
    </body>
<?php endif; ?>

<?php include '../Compartido/footer.php'; ?>