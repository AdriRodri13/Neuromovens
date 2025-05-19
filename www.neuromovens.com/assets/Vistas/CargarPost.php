
<?php
use Entidades\PostInvestigacion;
include '../Compartido/header.php';
?>

<?php if (isset($post) && $post instanceof PostInvestigacion): ?>
    <body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-info text-white text-center">
                        <h2 class="card-title mb-0">
                            <i class="fas fa-pen-nib me-2"></i>
                            Actualizar Post de Investigación
                        </h2>
                    </div>

                    <div class="card-body p-4">
                        <form id="form-actualizar-post" action="../Controlador/ControladorPostInvestigacion.php" method="post" enctype="multipart/form-data">
                            <!-- Campos ocultos -->
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="post[id]" value="<?= $post->getId(); ?>">

                            <!-- Campo para el título -->
                            <div class="mb-4">
                                <label for="titulo" class="form-label fw-semibold">
                                    <i class="fas fa-heading me-1"></i>
                                    Título del Post
                                </label>
                                <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-quote-left"></i>
                                        </span>
                                    <input type="text"
                                           id="titulo"
                                           name="post[titulo]"
                                           class="form-control form-control-lg"
                                           value="<?= htmlspecialchars($post->getTitulo()); ?>"
                                           placeholder="Ingrese un título atractivo para su investigación..."
                                           required
                                           autocomplete="off">
                                </div>
                                <div id="titulo-feedback" class="invalid-feedback"></div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Entre 5 y 100 caracteres
                                    </small>
                                    <small id="titulo-contador" class="form-text text-muted fw-bold">
                                        0/100 caracteres
                                    </small>
                                </div>
                            </div>

                            <!-- Campo para la descripción/contenido -->
                            <div class="mb-4">
                                <label for="descripcion" class="form-label fw-semibold">
                                    <i class="fas fa-align-left me-1"></i>
                                    Contenido del Post
                                </label>
                                <textarea id="descripcion"
                                          name="post[descripcion]"
                                          class="form-control"
                                          rows="10"
                                          placeholder="Escriba aquí el contenido detallado de su investigación..."
                                          required><?= htmlspecialchars($post->getContenido()); ?></textarea>
                                <div id="descripcion-feedback" class="invalid-feedback"></div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div>
                                        <small id="descripcion-contador" class="form-text text-muted fw-bold">
                                            0/2000 caracteres
                                        </small>
                                        <span class="mx-2">•</span>
                                        <small id="tiempo-lectura" class="form-text text-info">
                                            <i class="fas fa-clock me-1"></i>
                                            Tiempo de lectura: 0 min
                                        </small>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Mín. 20 caracteres
                                    </small>
                                </div>
                            </div>

                            <!-- Sección de gestión de imágenes -->
                            <div class="row mb-4">
                                <!-- Imagen actual -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-image me-1"></i>
                                        Imagen Actual
                                    </label>
                                    <div class="border rounded p-3 bg-light">
                                        <div class="text-center">
                                            <img id="imagen-actual"
                                                 src="../images/<?= basename($post->getImagenUrl()); ?>"
                                                 alt="Imagen Actual"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="max-height: 200px;">
                                            <input type="hidden" name="imagenAntigua" value="../images/<?= basename($post->getImagenUrl()); ?>">
                                        </div>
                                        <div class="text-center mt-2">
                                            <small class="text-muted">
                                                <i class="fas fa-file-image me-1"></i>
                                                <?= basename($post->getImagenUrl()); ?>
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nueva imagen -->
                                <div class="col-md-6 mb-3">
                                    <label for="imagen_url" class="form-label fw-semibold">
                                        <i class="fas fa-upload me-1"></i>
                                        Nueva Imagen (Opcional)
                                    </label>
                                    <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-paperclip"></i>
                                            </span>
                                        <input type="file"
                                               id="imagen_url"
                                               name="imagen_url"
                                               class="form-control"
                                               accept="image/jpeg,image/png,image/jpg">
                                    </div>
                                    <div id="imagen-feedback" class="invalid-feedback"></div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        JPG, PNG • Máx. 5MB
                                    </small>

                                    <!-- Vista previa de nueva imagen -->
                                    <div id="nueva-imagen-preview" class="mt-3 border rounded p-3 bg-light" style="display: none;">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">
                                                <i class="fas fa-eye me-1"></i>
                                                Vista Previa
                                            </h6>
                                            <button type="button"
                                                    id="cancelar-imagen"
                                                    class="btn btn-sm btn-outline-danger">
                                                <i class="fas fa-times"></i>
                                                Quitar
                                            </button>
                                        </div>
                                        <div class="text-center">
                                            <img id="preview-img"
                                                 src="#"
                                                 alt="Vista previa"
                                                 class="img-fluid rounded shadow-sm"
                                                 style="max-height: 200px;">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-clock me-1"></i>
                                        Última Actualización
                                    </label>
                                    <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-calendar-alt"></i>
                                            </span>
                                        <div id="fecha-actualizacion" class="form-control bg-light text-muted">
                                            <i class="fas fa-spinner fa-spin me-2"></i>
                                            Actualizando fecha...
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold text-muted">
                                        <i class="fas fa-key me-1"></i>
                                        ID del Post
                                    </label>
                                    <div class="form-control-plaintext bg-light px-3 py-2 rounded border">
                                        #<?= $post->getId(); ?>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones de acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                                <button type="button"
                                        id="btn-cancelar"
                                        class="btn btn-outline-secondary btn-lg me-md-2">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Cancelar
                                </button>
                                <button type="submit"
                                        id="btn-actualizar"
                                        class="btn btn-info btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Actualizar Post
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Footer del Card -->
                    <div class="card-footer bg-light text-center text-muted">
                        <small>
                            <i class="fas fa-shield-alt me-1"></i>
                            Sus cambios serán guardados de forma segura
                            <span class="mx-2">•</span>
                            <i class="fas fa-search me-1"></i>
                            El post estará disponible inmediatamente después de actualizar
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir archivo JavaScript separado -->
    <script src="../js/CargarPost.js"></script>
    </body>
<?php endif; ?>

<?php include '../Compartido/footer.php'; ?>