<?php require '../Entidades/Entidad.php'; require '../Entidades/Categoria.php'; use Entidades\Categoria; include '../Compartido/header.php';  ?>

    <div class="form-container">
        <h2>Insertar Nuevo Producto</h2>

        <!-- Contenedor para mostrar mensajes de éxito o error -->
        <div id="mensaje-respuesta" class="alert" style="display: none;"></div>

        <form id="form-insertar-producto" action="../Controlador/ControladorProductos.php" method="post" enctype="multipart/form-data">
            <!-- Campo oculto para indicar la acción de inserción -->
            <input type="hidden" name="accion" value="insertar">

            <!-- Campo para el nombre del producto -->
            <div class="form-group mb-3">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="producto[nombre]" class="form-control" required>
                <div id="nombre-feedback" class="invalid-feedback"></div>
            </div>

            <!-- Campo para la descripción del producto -->
            <div class="form-group mb-3">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="producto[descripcion]" rows="4" class="form-control" required></textarea>
                <div id="descripcion-feedback" class="invalid-feedback"></div>
                <small id="contador-caracteres" class="form-text text-muted">0/500 caracteres</small>
            </div>

            <!-- Campo para el precio del producto -->
            <div class="form-group mb-3">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="producto[precio]" class="form-control" required step="0.01">
                <div id="precio-feedback" class="invalid-feedback"></div>
            </div>

            <!-- Campo para la categoría -->
            <div class="form-group mb-3">
                <label for="categoria_id">Categoría:</label>
                <select id="categoria_id" name="producto[categoria_id]" class="form-control" required>
                    <option value="" disabled selected>Selecciona una categoría</option>
                    <?php if (isset($_SESSION['categorias'])): ?>
                        <?php
                        // Deserializa las categorías de la sesión
                        $categorias = unserialize($_SESSION['categorias']);
                        ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <?php if($categoria instanceof Categoria) :?>
                                <option value="<?= htmlspecialchars($categoria->getIdCategoria()); ?>">
                                    <?= htmlspecialchars($categoria->getNombreCategoria()); ?> - ID:<?= htmlspecialchars($categoria->getIdCategoria()); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No hay categorías disponibles</option>
                    <?php endif; ?>
                </select>
                <div id="categoria-feedback" class="invalid-feedback"></div>
            </div>

            <!-- Campo para la imagen del producto -->
            <div class="form-group mb-3">
                <label for="imagen_url">Selecciona una imagen:</label>
                <input type="file" id="imagen_url" name="imagen_url" class="form-control" accept="image/jpeg, image/png">
                <div id="imagen-feedback" class="invalid-feedback"></div>
            </div>

            <!-- Vista previa de imagen -->
            <div id="imagen-preview" class="mb-3" style="display: none;">
                <h5>Vista previa:</h5>
                <img id="preview-img" src="#" alt="Vista previa" style="max-width: 200px; max-height: 200px;">
            </div>

            <!-- Botones de acción -->
            <div class="d-flex justify-content-between">
                <button type="button" id="btn-cancelar" class="btn btn-secondary">Cancelar</button>
                <button type="submit" class="btn btn-primary">Insertar Producto</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            // Referencias a elementos del DOM usando jQuery
            const $form = $('#form-insertar-producto');
            const $nombreInput = $('#nombre');
            const $descripcionInput = $('#descripcion');
            const $precioInput = $('#precio');
            const $categoriaSelect = $('#categoria_id');
            const $imagenInput = $('#imagen_url');
            const $imagenPreview = $('#imagen-preview');
            const $previewImg = $('#preview-img');
            const $btnCancelar = $('#btn-cancelar');
            const $contadorCaracteres = $('#contador-caracteres');
            const $mensajeRespuesta = $('#mensaje-respuesta');

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

            // Contador de caracteres para la descripción
            $descripcionInput.on('input', function() {
                const longitud = $(this).val().length;
                $contadorCaracteres.text(longitud + '/500 caracteres');

                // Cambiar color según longitud
                $contadorCaracteres.removeClass('text-warning text-success text-muted').addClass('form-text');
                if (longitud > 400) {
                    $contadorCaracteres.addClass('text-warning');
                } else if (longitud > 0) {
                    $contadorCaracteres.addClass('text-success');
                } else {
                    $contadorCaracteres.addClass('text-muted');
                }
            });

            // Botón cancelar
            $btnCancelar.on('click', function() {
                if (confirm('¿Estás seguro de cancelar? Los datos no se guardarán.')) {
                    window.location.href = '../Controlador/ControladorProductos.php';
                }
            });

            // Comprobar disponibilidad de nombre producto con fetch (compatible con jQuery Slim)
            $nombreInput.on('blur', function() {
                const nombre = $(this).val().trim();
                if (nombre.length >= 3) {
                    fetch('../Controlador/ajax_productos.php?accion=comprobar_nombre&nombre=' + encodeURIComponent(nombre))
                        .then(response => response.json())
                        .then(data => {
                            if (data.disponible === false) {
                                mostrarError($nombreInput, $('#nombre-feedback'), 'Este nombre de producto ya existe');
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            });

            // Validación del formulario con jQuery AJAX
            $form.on('submit', function(e) {
                e.preventDefault();

                // Validación cliente
                let isValid = true;

                // Validar nombre
                const nombre = $nombreInput.val().trim();
                if (nombre === '') {
                    mostrarError($nombreInput, $('#nombre-feedback'), 'El nombre es obligatorio');
                    isValid = false;
                } else if (nombre.length < 3) {
                    mostrarError($nombreInput, $('#nombre-feedback'), 'El nombre debe tener al menos 3 caracteres');
                    isValid = false;
                } else {
                    quitarError($nombreInput, $('#nombre-feedback'));
                }

                // Validar descripción
                const descripcion = $descripcionInput.val().trim();
                if (descripcion === '') {
                    mostrarError($descripcionInput, $('#descripcion-feedback'), 'La descripción es obligatoria');
                    isValid = false;
                } else if (descripcion.length < 10) {
                    mostrarError($descripcionInput, $('#descripcion-feedback'), 'La descripción debe tener al menos 10 caracteres');
                    isValid = false;
                } else {
                    quitarError($descripcionInput, $('#descripcion-feedback'));
                }

                // Validar precio
                const precio = $precioInput.val();
                if (precio === '') {
                    mostrarError($precioInput, $('#precio-feedback'), 'El precio es obligatorio');
                    isValid = false;
                } else if (parseFloat(precio) <= 0) {
                    mostrarError($precioInput, $('#precio-feedback'), 'El precio debe ser mayor que 0');
                    isValid = false;
                } else {
                    quitarError($precioInput, $('#precio-feedback'));
                }

                // Validar categoría
                const categoria = $categoriaSelect.val();
                if (!categoria) {
                    mostrarError($categoriaSelect, $('#categoria-feedback'), 'Selecciona una categoría');
                    isValid = false;
                } else {
                    quitarError($categoriaSelect, $('#categoria-feedback'));
                }

                // Validar imagen
                const imagen = $imagenInput[0].files[0];
                if (!imagen) {
                    mostrarError($imagenInput, $('#imagen-feedback'), 'Selecciona una imagen');
                    isValid = false;
                } else {
                    const tipoPermitido = ['image/jpeg', 'image/png', 'image/jpg'];
                    if (!tipoPermitido.includes(imagen.type)) {
                        mostrarError($imagenInput, $('#imagen-feedback'), 'Solo se permiten imágenes JPG y PNG');
                        isValid = false;
                    } else if (imagen.size > 5 * 1024 * 1024) { // 5MB max
                        mostrarError($imagenInput, $('#imagen-feedback'), 'La imagen no puede superar 5MB');
                        isValid = false;
                    } else {
                        quitarError($imagenInput, $('#imagen-feedback'));
                    }
                }

                // Si hay errores, detener envío
                if (!isValid) {
                    return false;
                }

                // Mostrar indicador de carga
                mostrarMensaje('info', 'Procesando...');

                // Preparar datos para envío con fetch (compatible con jQuery Slim)
                const formData = new FormData(this);

                fetch('../Controlador/ajax_productos.php', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.exito) {
                            mostrarMensaje('success', 'Producto creado correctamente');

                            setTimeout(function() {
                                $form[0].reset();
                                $imagenPreview.hide();
                                window.location.href = '../Controlador/ControladorProductos.php';
                            }, 1500);
                        } else {
                            mostrarMensaje('danger', data.mensaje || 'Error al crear el producto');
                        }
                    })
                    .catch(error => {
                        mostrarMensaje('danger', 'Error en la conexión: ' + error.message);
                    });
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
            }
        });
    </script>

<?php include '../Compartido/footer.php'; ?>