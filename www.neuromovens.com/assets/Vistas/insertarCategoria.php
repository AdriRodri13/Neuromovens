<?php include '../Compartido/header.php'; ?>

    <div class="form-container">
        <h2>Insertar Nueva Categoría</h2>
        <form id="form-categoria" action="../Controlador/ControladorCategoria.php" method="post">
            <!-- Campo oculto para indicar la acción de inserción -->
            <input type="hidden" name="accion" value="insertar">

            <!-- Campo para el nombre de la categoría -->
            <div class="form-group">
                <label for="nombre_categoria">Nombre de la Categoría:</label>
                <input type="text" id="nombre_categoria" name="nombreCategoria"
                       class="form-control" required>
                <div id="categoria-feedback" class="invalid-feedback"></div>
            </div>

            <!-- Botón de envío -->
            <input type="submit" id="btn-insertar" value="Insertar Categoría" class="btn">
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Referencias a los elementos
            const $form = $('#form-categoria');
            const $nombreInput = $('#nombre_categoria');
            const $feedback = $('#categoria-feedback');
            const $btnInsertar = $('#btn-insertar');

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
                const nombre = $nombreInput.val().trim();

                // Comprobar que el campo no esté vacío
                if (nombre === '') {
                    event.preventDefault();
                    setInvalid($nombreInput, $feedback, 'El nombre de la categoría es obligatorio');
                    $nombreInput.focus();

                    // Opcional: mostrar alerta si SweetAlert2 está disponible
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Campo requerido',
                            text: 'Por favor, ingrese el nombre de la categoría',
                            confirmButtonText: 'Entendido'
                        });
                    }
                } else {
                    setValid($nombreInput, $feedback);

                    // Opcional: mostrar carga si SweetAlert2 está disponible
                    if (typeof Swal !== 'undefined') {
                        $btnInsertar.prop('disabled', true).val('Insertando...');
                        Swal.fire({
                            title: 'Insertando categoría...',
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
            $nombreInput.on('input', function() {
                if ($(this).hasClass('is-invalid') && $(this).val().trim() !== '') {
                    clearValidation($(this), $feedback);
                }
            });

            // Focus inicial en el campo
            $nombreInput.focus();
        });
    </script>

<?php include '../Compartido/footer.php'; ?>