<?php use Entidades\PostInvestigacion; include '../Compartido/header.php'; ?>

<?php if (isset($post) && $post instanceof PostInvestigacion): ?>
    <body>
    <div class="form-container">
        <h2>Actualizar Post de Investigación</h2>

        <form id="form-actualizar-post" action="../Controlador/ControladorPostInvestigacion.php" method="post" enctype="multipart/form-data">
            <!-- Campos ocultos -->
            <input type="hidden" name="accion" value="actualizar">
            <input type="hidden" name="post[id]" value="<?= $post->getId(); ?>">

            <!-- Campo para el título -->
            <div class="form-group mb-3">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="post[titulo]" class="form-control"
                       value="<?= htmlspecialchars($post->getTitulo()); ?>" required>
                <div id="titulo-feedback" class="invalid-feedback"></div>
                <small id="titulo-contador" class="form-text text-muted">0/100 caracteres</small>
            </div>

            <!-- Campo para la descripción -->
            <div class="form-group mb-3">
                <label for="descripcion">Contenido:</label>
                <textarea id="descripcion" name="post[descripcion]" class="form-control"
                          rows="8" required><?= htmlspecialchars($post->getContenido()); ?></textarea>
                <div id="descripcion-feedback" class="invalid-feedback"></div>
                <div class="d-flex justify-content-between">
                    <small id="descripcion-contador" class="form-text text-muted">0/2000 caracteres</small>
                    <small id="tiempo-lectura" class="form-text text-muted">Tiempo de lectura: 0 min</small>
                </div>
            </div>

            <!-- Mostrar la imagen actual -->
            <div class="form-group mb-3">
                <label>Imagen Actual:</label>
                <div class="image-container position-relative">
                    <img id="imagen-actual" src="../images/<?= basename($post->getImagenUrl()); ?>"
                         alt="Imagen Actual" class="img-fluid" style="max-height: 200px;">
                    <input type="hidden" name="imagenAntigua" value="../images/<?= basename($post->getImagenUrl()); ?>">
                </div>
            </div>

            <!-- Campo para subir una nueva imagen -->
            <div class="form-group mb-3">
                <label for="imagen_url">Seleccionar nueva imagen:</label>
                <input type="file" id="imagen_url" name="imagen_url" class="form-control"
                       accept="image/jpeg, image/png">
                <div id="imagen-feedback" class="invalid-feedback"></div>

                <!-- Contenedor para vista previa de la nueva imagen -->
                <div id="nueva-imagen-preview" class="mt-3" style="display: none;">
                    <h6>Vista previa:</h6>
                    <div class="position-relative">
                        <img id="preview-img" src="#" alt="Vista previa" class="img-fluid" style="max-height: 200px;">
                        <button type="button" id="cancelar-imagen" class="btn btn-sm btn-danger position-absolute top-0 end-0">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Fecha de última actualización -->
            <div class="form-group mb-4">
                <label>Última actualización:</label>
                <div id="fecha-actualizacion" class="form-control-plaintext"></div>
            </div>

            <!-- Botones de acción -->
            <div class="d-flex justify-content-between">
                <button type="button" id="btn-cancelar" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Cancelar
                </button>
                <button type="submit" id="btn-actualizar" class="btn btn-primary">
                    <i class="fas fa-save"></i> Actualizar Post
                </button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // 1. Variables jQuery - Referencias cacheadas para mejor rendimiento
            const $form = $('#form-actualizar-post');
            const $tituloInput = $('#titulo');
            const $tituloFeedback = $('#titulo-feedback');
            const $tituloContador = $('#titulo-contador');
            const $descripcionInput = $('#descripcion');
            const $descripcionFeedback = $('#descripcion-feedback');
            const $descripcionContador = $('#descripcion-contador');
            const $tiempoLectura = $('#tiempo-lectura');
            const $imagenInput = $('#imagen_url');
            const $imagenFeedback = $('#imagen-feedback');
            const $nuevaImagenPreview = $('#nueva-imagen-preview');
            const $previewImg = $('#preview-img');
            const $cancelarImagen = $('#cancelar-imagen');
            const $btnCancelar = $('#btn-cancelar');
            const $fechaActualizacion = $('#fecha-actualizacion');

            // 2. Funciones auxiliares para la validación
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

            // 3. Mostrar fecha actual usando jQuery y Date
            function mostrarFechaActualizacion() {
                const fechaActual = new Date();
                const opciones = {
                    weekday: 'long',
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                };
                $fechaActualizacion.text(fechaActual.toLocaleDateString('es-ES', opciones));
            }

            // 4. Validación del título con jQuery
            $tituloInput.on('input', function() {
                const valor = $(this).val().trim();
                const longitud = valor.length;

                // Actualizar contador usando jQuery
                $tituloContador.text(`${longitud}/100 caracteres`);

                // Cambiar clases CSS usando jQuery según longitud
                $tituloContador.removeClass('form-text text-muted text-success text-warning');

                if (longitud > 80) {
                    $tituloContador.addClass('form-text text-warning');
                } else if (longitud > 0) {
                    $tituloContador.addClass('form-text text-success');
                } else {
                    $tituloContador.addClass('form-text text-muted');
                }

                // Validación usando las funciones auxiliares
                if (longitud === 0) {
                    setInvalid($tituloInput, $tituloFeedback, 'El título es obligatorio');
                } else if (longitud < 5) {
                    setInvalid($tituloInput, $tituloFeedback, 'El título debe tener al menos 5 caracteres');
                } else if (longitud > 100) {
                    setInvalid($tituloInput, $tituloFeedback, 'El título no puede exceder los 100 caracteres');
                } else {
                    setValid($tituloInput, $tituloFeedback);
                }
            });

            // 5. Validación de la descripción/contenido con jQuery
            $descripcionInput.on('input', function() {
                const valor = $(this).val().trim();
                const longitud = valor.length;

                // Calcular palabras usando split y filter con jQuery
                const palabras = valor.split(/\s+/).filter(Boolean).length;

                // Actualizar contador de caracteres
                $descripcionContador.text(`${longitud}/2000 caracteres`);

                // Cambiar color según longitud usando jQuery
                $descripcionContador.removeClass('form-text text-muted text-success text-warning');

                if (longitud > 1500) {
                    $descripcionContador.addClass('form-text text-warning');
                } else if (longitud > 0) {
                    $descripcionContador.addClass('form-text text-success');
                } else {
                    $descripcionContador.addClass('form-text text-muted');
                }

                // Calcular tiempo de lectura (promedio de 200 palabras por minuto)
                const minutos = Math.max(1, Math.ceil(palabras / 200));
                $tiempoLectura.text(`Tiempo de lectura: ${minutos} min`);

                // Validación
                if (longitud === 0) {
                    setInvalid($descripcionInput, $descripcionFeedback, 'El contenido es obligatorio');
                } else if (longitud < 20) {
                    setInvalid($descripcionInput, $descripcionFeedback, 'El contenido debe tener al menos 20 caracteres');
                } else if (longitud > 2000) {
                    setInvalid($descripcionInput, $descripcionFeedback, 'El contenido no puede exceder los 2000 caracteres');
                } else {
                    setValid($descripcionInput, $descripcionFeedback);
                }
            });

            // 6. Vista previa de imagen con FileReader API (jQuery)
            $imagenInput.on('change', function() {
                const file = this.files[0];

                if (file) {
                    // Validar tipo de archivo
                    const fileType = file.type;
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];

                    if (!validTypes.includes(fileType)) {
                        setInvalid($imagenInput, $imagenFeedback, 'Solo se permiten imágenes en formato JPG o PNG');
                        $(this).val(''); // Limpiar input con jQuery
                        return;
                    }

                    // Validar tamaño (5MB máximo)
                    if (file.size > 5 * 1024 * 1024) {
                        setInvalid($imagenInput, $imagenFeedback, 'La imagen no puede superar los 5MB');
                        $(this).val(''); // Limpiar input con jQuery
                        return;
                    }

                    // Mostrar vista previa usando FileReader
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $previewImg.attr('src', e.target.result);
                        $nuevaImagenPreview.show(); // jQuery show() instead of style.display
                        setValid($imagenInput, $imagenFeedback);
                    };
                    reader.readAsDataURL(file);
                } else {
                    $nuevaImagenPreview.hide(); // jQuery hide() instead of style.display
                }
            });

            // 7. Botón para cancelar la selección de imagen (jQuery)
            $cancelarImagen.on('click', function() {
                $imagenInput.val('');
                $nuevaImagenPreview.hide();
                setValid($imagenInput, $imagenFeedback);
            });

            // 8. Botón cancelar con confirmación (jQuery)
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
                            window.location.href = '../Controlador/ControladorPostInvestigacion.php';
                        }
                    });
                } else {
                    if (confirm('¿Estás seguro? Los cambios no guardados se perderán')) {
                        window.location.href = '../Controlador/ControladorPostInvestigacion.php';
                    }
                }
            });

            // 9. Validación del formulario al enviar (jQuery)
            $form.on('submit', function(event) {
                // Disparar validación para todos los campos usando jQuery
                $tituloInput.trigger('input');
                $descripcionInput.trigger('input');

                // Verificar si hay errores
                if (!isFormValid()) {
                    event.preventDefault();

                    // Mostrar mensaje de error con SweetAlert2
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            text: 'Por favor, corrija los errores antes de continuar'
                        });
                    } else {
                        alert('Por favor, corrija los errores antes de continuar');
                    }

                    // Hacer scroll al primer error usando jQuery
                    const $firstError = $('.is-invalid').first();
                    if ($firstError.length) {
                        $('html, body').animate({
                            scrollTop: $firstError.offset().top - 100
                        }, 500);
                    }
                } else {
                    // Mostrar indicador de carga si SweetAlert2 está disponible
                    if (typeof Swal !== 'undefined') {
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
                }
            });

            // 10. Función de inicialización
            function inicializar() {
                // Mostrar fecha actual
                mostrarFechaActualizacion();

                // Disparar validaciones iniciales para mostrar contadores
                $tituloInput.trigger('input');
                $descripcionInput.trigger('input');
            }

            // 11. Ejecutar inicialización
            inicializar();

        });
    </script>
    </body>
<?php endif; ?>

<?php include '../Compartido/footer.php'; ?>