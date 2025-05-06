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
        document.addEventListener('DOMContentLoaded', function() {
            // Referencias a elementos del DOM
            const form = document.getElementById('form-actualizar-post');
            const tituloInput = document.getElementById('titulo');
            const tituloFeedback = document.getElementById('titulo-feedback');
            const tituloContador = document.getElementById('titulo-contador');
            const descripcionInput = document.getElementById('descripcion');
            const descripcionFeedback = document.getElementById('descripcion-feedback');
            const descripcionContador = document.getElementById('descripcion-contador');
            const tiempoLectura = document.getElementById('tiempo-lectura');
            const imagenInput = document.getElementById('imagen_url');
            const imagenFeedback = document.getElementById('imagen-feedback');
            const nuevaImagenPreview = document.getElementById('nueva-imagen-preview');
            const previewImg = document.getElementById('preview-img');
            const cancelarImagen = document.getElementById('cancelar-imagen');
            const btnCancelar = document.getElementById('btn-cancelar');
            const fechaActualizacion = document.getElementById('fecha-actualizacion');

            // 1. Uso del objeto Date para mostrar la fecha actual formateada
            const fechaActual = new Date();
            const opciones = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            fechaActualizacion.textContent = fechaActual.toLocaleDateString('es-ES', opciones);

            // 2. Validación del título con eventos
            tituloInput.addEventListener('input', function() {
                const valor = this.value.trim();
                const longitud = valor.length;

                // Actualizar contador
                tituloContador.textContent = `${longitud}/100 caracteres`;

                // Cambiar color según longitud
                if (longitud > 80) {
                    tituloContador.className = 'form-text text-warning';
                } else if (longitud > 0) {
                    tituloContador.className = 'form-text text-success';
                } else {
                    tituloContador.className = 'form-text text-muted';
                }

                // Validar
                if (longitud === 0) {
                    setInvalid(tituloInput, tituloFeedback, 'El título es obligatorio');
                } else if (longitud < 5) {
                    setInvalid(tituloInput, tituloFeedback, 'El título debe tener al menos 5 caracteres');
                } else if (longitud > 100) {
                    setInvalid(tituloInput, tituloFeedback, 'El título no puede exceder los 100 caracteres');
                } else {
                    setValid(tituloInput, tituloFeedback);
                }
            });

            // 3. Validación de la descripción/contenido
            descripcionInput.addEventListener('input', function() {
                const valor = this.value.trim();
                const longitud = valor.length;
                const palabras = valor.split(/\s+/).filter(Boolean).length;

                // Actualizar contador de caracteres
                descripcionContador.textContent = `${longitud}/2000 caracteres`;

                // Cambiar color según longitud
                if (longitud > 1500) {
                    descripcionContador.className = 'form-text text-warning';
                } else if (longitud > 0) {
                    descripcionContador.className = 'form-text text-success';
                } else {
                    descripcionContador.className = 'form-text text-muted';
                }

                // Calcular tiempo de lectura (promedio de 200 palabras por minuto)
                const minutos = Math.max(1, Math.ceil(palabras / 200));
                tiempoLectura.textContent = `Tiempo de lectura: ${minutos} min`;

                // Validar
                if (longitud === 0) {
                    setInvalid(descripcionInput, descripcionFeedback, 'El contenido es obligatorio');
                } else if (longitud < 20) {
                    setInvalid(descripcionInput, descripcionFeedback, 'El contenido debe tener al menos 20 caracteres');
                } else if (longitud > 2000) {
                    setInvalid(descripcionInput, descripcionFeedback, 'El contenido no puede exceder los 2000 caracteres');
                } else {
                    setValid(descripcionInput, descripcionFeedback);
                }
            });

            // 4. Vista previa de imagen con FileReader API
            imagenInput.addEventListener('change', function() {
                const file = this.files[0];

                if (file) {
                    // Validar tipo de archivo
                    const fileType = file.type;
                    const validTypes = ['image/jpeg', 'image/png', 'image/jpg'];

                    if (!validTypes.includes(fileType)) {
                        setInvalid(imagenInput, imagenFeedback, 'Solo se permiten imágenes en formato JPG o PNG');
                        this.value = ''; // Limpiar input
                        return;
                    }

                    // Validar tamaño (5MB máximo)
                    if (file.size > 5 * 1024 * 1024) {
                        setInvalid(imagenInput, imagenFeedback, 'La imagen no puede superar los 5MB');
                        this.value = ''; // Limpiar input
                        return;
                    }

                    // Mostrar vista previa
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        nuevaImagenPreview.style.display = 'block';
                        setValid(imagenInput, imagenFeedback);
                    };
                    reader.readAsDataURL(file);
                } else {
                    nuevaImagenPreview.style.display = 'none';
                }
            });

            // 5. Botón para cancelar la selección de imagen
            cancelarImagen.addEventListener('click', function() {
                imagenInput.value = '';
                nuevaImagenPreview.style.display = 'none';
                setValid(imagenInput, imagenFeedback);
            });

            // 6. Botón cancelar con confirmación
            btnCancelar.addEventListener('click', function() {
                // Usar SweetAlert2 si está disponible
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
                    // Alternativa con confirm básico
                    if (confirm('¿Estás seguro? Los cambios no guardados se perderán')) {
                        window.location.href = '../Controlador/ControladorPostInvestigacion.php';
                    }
                }
            });

            // 7. Validación del formulario al enviar
            form.addEventListener('submit', function(event) {
                // Trigger de validación para todos los campos
                tituloInput.dispatchEvent(new Event('input'));
                descripcionInput.dispatchEvent(new Event('input'));

                // Verificar si hay errores
                if (document.querySelectorAll('.is-invalid').length > 0) {
                    event.preventDefault();

                    // Mostrar mensaje de error
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error de validación',
                            text: 'Por favor, corrija los errores antes de continuar'
                        });
                    } else {
                        alert('Por favor, corrija los errores antes de continuar');
                    }

                    // Hacer scroll al primer error
                    document.querySelector('.is-invalid').scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
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

            // 8. Trigger inicial para mostrar los contadores y validaciones iniciales
            tituloInput.dispatchEvent(new Event('input'));
            descripcionInput.dispatchEvent(new Event('input'));

            // Funciones auxiliares
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
        });
    </script>
    </body>
<?php endif; ?>

<?php include '../Compartido/footer.php'; ?>