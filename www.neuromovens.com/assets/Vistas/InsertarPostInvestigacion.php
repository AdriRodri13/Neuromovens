<?php include '../Compartido/header.php'; ?>

    <div class="form-container">
        <h2>Insertar Nuevo Post de Investigación</h2>

        <!-- Contenedor para mensajes de respuesta -->
        <div id="mensaje-respuesta" class="alert" style="display: none;"></div>

        <form id="form-insertar-post" action="../Controlador/ControladorPostInvestigacion.php" method="post" enctype="multipart/form-data">
            <!-- Campo oculto para indicar la acción de inserción -->
            <input type="hidden" name="accion" value="insertar">

            <!-- Campo para el título del post -->
            <div class="form-group mb-3">
                <label for="titulo">Título:</label>
                <input type="text" id="titulo" name="post[titulo]" class="form-control" required>
                <div id="titulo-feedback" class="invalid-feedback"></div>
                <small id="contador-titulo" class="form-text text-muted">0/100 caracteres</small>
            </div>

            <!-- Campo para la descripción del post -->
            <div class="form-group mb-3">
                <label for="descripcion">Contenido de la Investigación:</label>
                <textarea id="descripcion" name="post[descripcion]" rows="6" class="form-control" required></textarea>
                <div id="descripcion-feedback" class="invalid-feedback"></div>
                <div class="d-flex justify-content-between">
                    <small id="contador-descripcion" class="form-text text-muted">0/1000 caracteres</small>
                    <small id="tiempo-lectura" class="form-text text-muted">Tiempo de lectura: 0 min</small>
                </div>
            </div>

            <!-- Campo para la imagen -->
            <div class="form-group mb-3">
                <label for="imagen_url">Imagen de la investigación:</label>
                <input type="file" id="imagen_url" name="imagen_url" class="form-control" accept="image/jpeg, image/png" required>
                <div id="imagen-feedback" class="invalid-feedback"></div>
            </div>

            <!-- Vista previa de imagen -->
            <div id="imagen-preview" class="mb-4" style="display: none;">
                <h5>Vista previa:</h5>
                <img id="preview-img" src="#" alt="Vista previa" class="img-fluid rounded" style="max-width: 400px; max-height: 300px;">
            </div>

            <!-- Botones de acción -->
            <div class="d-flex justify-content-between">
                <button type="button" id="btn-cancelar" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Publicar investigación
                </button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Referencias a elementos del DOM usando jQuery
            const $form = $('#form-insertar-post');
            const $tituloInput = $('#titulo');
            const $descripcionInput = $('#descripcion');
            const $imagenInput = $('#imagen_url');
            const $imagenPreview = $('#imagen-preview');
            const $previewImg = $('#preview-img');
            const $btnCancelar = $('#btn-cancelar');
            const $contadorTitulo = $('#contador-titulo');
            const $contadorDescripcion = $('#contador-descripcion');
            const $tiempoLectura = $('#tiempo-lectura');
            const $mensajeRespuesta = $('#mensaje-respuesta');

            // Contador de caracteres para el título
            $tituloInput.on('input', function() {
                const longitud = $(this).val().length;
                $contadorTitulo.text(longitud + '/100 caracteres');

                // Cambiar color según longitud
                $contadorTitulo.removeClass('text-warning text-success text-muted').addClass('form-text');
                if (longitud > 80) {
                    $contadorTitulo.addClass('text-warning');
                } else if (longitud > 0) {
                    $contadorTitulo.addClass('text-success');
                } else {
                    $contadorTitulo.addClass('text-muted');
                }
            });

            // Contador de caracteres y tiempo de lectura para la descripción
            $descripcionInput.on('input', function() {
                const texto = $(this).val();
                const longitud = texto.length;
                const palabras = texto.split(/\s+/).filter(Boolean).length;

                // Actualizar contador
                $contadorDescripcion.text(longitud + '/1000 caracteres');

                // Estimar tiempo de lectura (200 palabras por minuto en promedio)
                const minutos = Math.max(1, Math.ceil(palabras / 200));
                $tiempoLectura.text('Tiempo de lectura: ' + minutos + ' min');

                // Cambiar color según longitud
                $contadorDescripcion.removeClass('text-warning text-success text-muted').addClass('form-text');
                if (longitud > 800) {
                    $contadorDescripcion.addClass('text-warning');
                } else if (longitud > 0) {
                    $contadorDescripcion.addClass('text-success');
                } else {
                    $contadorDescripcion.addClass('text-muted');
                }
            });

            // Vista previa de imagen
            $imagenInput.on('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $previewImg.attr('src', e.target.result);
                        $imagenPreview.show();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $imagenPreview.hide();
                }
            });

            // Botón cancelar
            $btnCancelar.on('click', function() {
                if (confirm('¿Estás seguro de cancelar? Los datos no se guardarán.')) {
                    window.location.href = '../Controlador/ControladorPostInvestigacion.php';
                }
            });

            // Validación y mejora UX del formulario
            $form.on('submit', function(e) {
                // Validar título
                let isValid = true;
                const titulo = $tituloInput.val().trim();
                if (titulo === '') {
                    mostrarError($tituloInput, $('#titulo-feedback'), 'El título es obligatorio');
                    isValid = false;
                } else if (titulo.length < 5) {
                    mostrarError($tituloInput, $('#titulo-feedback'), 'El título debe tener al menos 5 caracteres');
                    isValid = false;
                } else if (titulo.length > 100) {
                    mostrarError($tituloInput, $('#titulo-feedback'), 'El título no puede exceder los 100 caracteres');
                    isValid = false;
                } else {
                    quitarError($tituloInput, $('#titulo-feedback'));
                }

                // Validar descripción
                const descripcion = $descripcionInput.val().trim();
                if (descripcion === '') {
                    mostrarError($descripcionInput, $('#descripcion-feedback'), 'El contenido es obligatorio');
                    isValid = false;
                } else if (descripcion.length < 20) {
                    mostrarError($descripcionInput, $('#descripcion-feedback'), 'El contenido debe tener al menos 20 caracteres');
                    isValid = false;
                } else if (descripcion.length > 1000) {
                    mostrarError($descripcionInput, $('#descripcion-feedback'), 'El contenido no puede exceder los 1000 caracteres');
                    isValid = false;
                } else {
                    quitarError($descripcionInput, $('#descripcion-feedback'));
                }

                // Validar imagen
                const imagen = $imagenInput[0].files[0];
                if (!imagen) {
                    mostrarError($imagenInput, $('#imagen-feedback'), 'La imagen es obligatoria');
                    isValid = false;
                } else {
                    const formatosPermitidos = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!formatosPermitidos.includes(imagen.type)) {
                        mostrarError($imagenInput, $('#imagen-feedback'), 'Solo se permiten imágenes en formato JPG o PNG');
                        isValid = false;
                    } else if (imagen.size > 5 * 1024 * 1024) { // 5MB máximo
                        mostrarError($imagenInput, $('#imagen-feedback'), 'La imagen no puede superar los 5MB');
                        isValid = false;
                    } else {
                        quitarError($imagenInput, $('#imagen-feedback'));
                    }
                }

                // Si hay errores, detener envío
                if (!isValid) {
                    e.preventDefault();
                    mostrarMensaje('danger', 'Por favor, corrige los errores antes de continuar.');
                    return false;
                }

                // Mostrar mensaje de carga mientras se envía el formulario
                mostrarMensaje('info', 'Enviando información... Por favor espere.');

                // No prevenimos el envío del formulario, dejamos que se procese normalmente
                // pero sí añadimos un indicador visual
                $('button[type="submit"]').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Publicando...');

                // El formulario se enviará normalmente (no AJAX)
                return true;
            });

            // Funciones auxiliares
            function mostrarError($campo, $feedback, mensaje) {
                $campo.addClass('is-invalid');
                $feedback.text(mensaje).show();
            }

            function quitarError($campo, $feedback) {
                $campo.removeClass('is-invalid');
                $feedback.text('').hide();
            }

            function mostrarMensaje(tipo, mensaje) {
                $mensajeRespuesta.removeClass().addClass('alert alert-' + tipo);
                $mensajeRespuesta.text(mensaje).show();
                $('html, body').animate({
                    scrollTop: $mensajeRespuesta.offset().top - 100
                }, 500);
            }
        });
    </script>

<?php include '../Compartido/footer.php'; ?>