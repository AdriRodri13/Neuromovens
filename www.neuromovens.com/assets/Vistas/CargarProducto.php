<?php use Entidades\Producto; use Entidades\Categoria; include '../Compartido/header.php';  ?>
<?php if (isset($producto) && $producto instanceof Producto): ?>

    <body>
    <div class="form-container">
        <h2>Actualizar Producto</h2>
        <form id="form-actualizar-producto" action="../Controlador/ControladorProductos.php" method="post" enctype="multipart/form-data">
            <!-- Campo oculto para indicar la acción de actualización -->
            <input type="hidden" name="accion" value="actualizar">

            <!-- Campo oculto para el ID del producto -->
            <input type="hidden" name="producto[id]" value="<?= $producto->getId(); ?>">

            <!-- Campo para el nombre del producto -->
            <div class="form-group">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="producto[nombre]" value="<?= $producto->getNombre(); ?>" required>
                <div class="error-message" id="nombre-error"></div>
            </div>

            <!-- Campo para la descripción del producto -->
            <div class="form-group">
                <label for="descripcion">Descripción:</label>
                <textarea id="descripcion" name="producto[descripcion]" rows="4" required><?= $producto->getDescripcion(); ?></textarea>
                <div class="error-message" id="descripcion-error"></div>
                <div class="contador-caracteres">0 caracteres</div>
            </div>

            <!-- Campo para el precio del producto -->
            <div class="form-group">
                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="producto[precio]" value="<?= $producto->getPrecio(); ?>" required step="0.01">
                <div class="error-message" id="precio-error"></div>
            </div>

            <!-- Campo para la categoría del producto -->
            <div class="form-group">
                <label for="categoria_id">Categoría:</label>
                <select id="categoria_id" name="producto[categoria_id]" required>
                    <option value="" disabled>Selecciona una categoría</option>
                    <?php if (isset($_SESSION['categorias'])): ?>
                        <?php
                        // Deserializa las categorías de la sesión
                        $categorias = unserialize($_SESSION['categorias']);
                        ?>
                        <?php foreach ($categorias as $categoria): ?>
                            <?php if($categoria instanceof Categoria) :?>
                                <option value="<?= htmlspecialchars($categoria->getIdCategoria()); ?>"
                                    <?= ($categoria->getIdCategoria() == $producto->getCategoriaId()) ? 'selected' : ''; ?>>
                                    <?= htmlspecialchars($categoria->getNombreCategoria()); ?> - ID:<?= htmlspecialchars($categoria->getIdCategoria()); ?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <option value="" disabled>No hay categorías disponibles</option>
                    <?php endif; ?>
                </select>
                <div class="error-message" id="categoria-error"></div>
            </div>

            <!-- Mostrar la imagen actual -->
            <div class="form-group image-preview">
                <label>Imagen Actual:</label><br>
                <img id="imagen-actual" src="../images/<?= basename($producto->getImagenUrl()); ?>" alt="Imagen Actual" style="max-width: 200px;">
                <?php echo basename($producto->getImagenUrl());?>
                <input type="hidden" name="imagenAntigua" value="../images/<?= basename($producto->getImagenUrl()); ?>">
            </div>

            <!-- Campo para subir una nueva imagen -->
            <div class="form-group">
                <label for="imagen_url">Seleccionar nueva imagen (si desea cambiarla):</label>
                <input type="file" id="imagen_url" name="imagen_url" accept="image/jpeg, image/png">
                <div id="nueva-imagen-preview" class="mt-2" style="display: none;">
                    <h5>Vista previa:</h5>
                    <img id="preview-img" src="#" alt="Vista previa" style="max-width: 200px; max-height: 200px;">
                </div>
            </div>

            <!-- Fecha de última actualización (ejemplo de uso de Date) -->
            <div class="form-group">
                <label for="fecha_actualizacion">Fecha de actualización:</label>
                <input type="text" id="fecha_actualizacion" class="form-control" readonly>
                <small class="form-text text-muted">Esta fecha es solo informativa y no se guarda</small>
            </div>

            <!-- Botón de envío -->
            <div class="form-group">
                <input type="submit" id="btn-actualizar" value="Actualizar Producto" class="btn btn-primary">
                <button type="button" id="btn-cancelar" class="btn btn-secondary ms-2">Cancelar</button>
            </div>
        </form>
    </div>

    <!-- Script para el formulario -->
    <script>
        $(document).ready(function() {
            // Establecer la fecha actual usando JavaScript nativo
            const fechaActual = new Date();
            const dia = String(fechaActual.getDate()).padStart(2, '0');
            const mes = String(fechaActual.getMonth() + 1).padStart(2, '0'); // Enero es 0
            const anio = fechaActual.getFullYear();
            const fechaFormateada = dia + '/' + mes + '/' + anio;
            $("#fecha_actualizacion").val(fechaFormateada);

            // Contador de caracteres para la descripción
            $("#descripcion").on('input', function() {
                const caracteresActuales = $(this).val().length;
                $(".contador-caracteres").text(caracteresActuales + ' caracteres');

                // Cambiar color según la longitud
                if (caracteresActuales > 500) {
                    $(".contador-caracteres").css('color', 'red');
                } else if (caracteresActuales > 300) {
                    $(".contador-caracteres").css('color', 'orange');
                } else {
                    $(".contador-caracteres").css('color', 'green');
                }
            });

            // Trigger para contar los caracteres iniciales
            $("#descripcion").trigger('input');

            // Vista previa de imagen
            $("#imagen_url").change(function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $("#preview-img").attr('src', e.target.result);
                        $("#nueva-imagen-preview").fadeIn();
                    }
                    reader.readAsDataURL(file);
                } else {
                    $("#nueva-imagen-preview").hide();
                }
            });

            // Botón cancelar con confirmación usando SweetAlert2
            $("#btn-cancelar").click(function() {
                // Si SweetAlert2 está cargado correctamente
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
                            window.location.href = '../Controlador/ControladorProductos.php';
                        }
                    });
                } else {
                    // Fallback para navegadores sin SweetAlert2
                    if (confirm('¿Estás seguro? Los cambios no guardados se perderán')) {
                        window.location.href = '../Controlador/ControladorProductos.php';
                    }
                }
            });

            // Validación del formulario usando JavaScript puro
            $("#form-actualizar-producto").on('submit', function(e) {
                let isValid = true;

                // Validar nombre
                const nombre = $("#nombre").val();
                if (nombre.length < 3) {
                    $("#nombre-error").text("El nombre debe tener al menos 3 caracteres");
                    isValid = false;
                } else if (nombre.length > 100) {
                    $("#nombre-error").text("El nombre no puede tener más de 100 caracteres");
                    isValid = false;
                } else {
                    $("#nombre-error").text("");
                }

                // Validar descripción
                const descripcion = $("#descripcion").val();
                if (descripcion.length < 10) {
                    $("#descripcion-error").text("La descripción debe tener al menos 10 caracteres");
                    isValid = false;
                } else {
                    $("#descripcion-error").text("");
                }

                // Validar precio
                const precio = $("#precio").val();
                if (precio === "" || precio <= 0) {
                    $("#precio-error").text("El precio debe ser mayor que 0");
                    isValid = false;
                } else {
                    $("#precio-error").text("");
                }

                // Validar categoría
                const categoria = $("#categoria_id").val();
                if (!categoria) {
                    $("#categoria-error").text("Por favor, selecciona una categoría");
                    isValid = false;
                } else {
                    $("#categoria-error").text("");
                }

                // Validar extensión de imagen
                const imagenInput = $("#imagen_url")[0];
                if (imagenInput.files.length > 0) {
                    const archivo = imagenInput.files[0].name;
                    const extension = archivo.split('.').pop().toLowerCase();
                    const extensionesPermitidas = ['jpg', 'jpeg', 'png'];

                    if (!extensionesPermitidas.includes(extension)) {
                        alert("Solo se permiten archivos JPG, JPEG o PNG");
                        isValid = false;
                    }
                }

                // Si hay errores, prevenir el envío del formulario
                if (!isValid) {
                    e.preventDefault();
                } else {
                    // Mostrar mensaje de carga si SweetAlert2 está disponible
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: 'Guardando cambios',
                            text: 'Por favor, espera...',
                            allowOutsideClick: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    }
                }
            });
        });
    </script>

<?php endif; ?>
<?php include '../Compartido/footer.php'; ?>