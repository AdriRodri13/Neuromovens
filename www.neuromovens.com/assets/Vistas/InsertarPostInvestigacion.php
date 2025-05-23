<?php include '../Compartido/header.php'; ?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-8">
                <div class="card shadow border-0">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">
                            <i class="fas fa-microscope text-primary me-2"></i>
                            Insertar Nuevo Post de Investigación
                        </h2>

                        <!-- Contenedor para mensajes de respuesta -->
                        <div id="mensaje-respuesta" class="alert" style="display: none;"></div>

                        <form id="form-insertar-post" action="../Controlador/ControladorPostInvestigacion.php" method="post" enctype="multipart/form-data">
                            <!-- Campo oculto para indicar la acción de inserción -->
                            <input type="hidden" name="accion" value="insertar">

                            <!-- Campo para el título del post -->
                            <div class="form-group mb-4">
                                <label for="titulo" class="form-label fw-semibold">Título:</label>
                                <input type="text" id="titulo" name="post[titulo]" class="form-control form-control-lg" required>
                                <div id="titulo-feedback" class="invalid-feedback"></div>
                                <small id="contador-titulo" class="form-text text-muted">0/100 caracteres</small>
                            </div>

                            <!-- Campo para la descripción del post -->
                            <div class="form-group mb-4">
                                <label for="descripcion" class="form-label fw-semibold">Contenido de la Investigación:</label>
                                <textarea id="descripcion" name="post[descripcion]" rows="6" class="form-control" required></textarea>
                                <div id="descripcion-feedback" class="invalid-feedback"></div>
                                <div class="d-flex justify-content-between">
                                    <small id="contador-descripcion" class="form-text text-muted">0/1000 caracteres</small>
                                    <small id="tiempo-lectura" class="form-text text-muted">Tiempo de lectura: 0 min</small>
                                </div>
                            </div>

                            <!-- Campo para la imagen -->
                            <div class="form-group mb-4">
                                <label for="imagen_url" class="form-label fw-semibold">Imagen de la investigación:</label>
                                <input type="file" id="imagen_url" name="imagen_url" class="form-control" accept="image/jpeg, image/png" required>
                                <div id="imagen-feedback" class="invalid-feedback"></div>
                            </div>

                            <!-- Vista previa de imagen -->
                            <div id="imagen-preview" class="mb-4" style="display: none;">
                                <h5 class="fw-semibold">Vista previa:</h5>
                                <img id="preview-img" src="#" alt="Vista previa" class="img-fluid rounded shadow-sm" style="max-width: 400px; max-height: 300px;">
                            </div>

                            <!-- Botones de acción -->
                            <div class="d-flex gap-3 justify-content-end">
                                <button type="button" id="btn-cancelar" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i> Cancelar
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Publicar investigación
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir archivo JavaScript separado -->
    <script src="../js/InsertarPostInvestigacion.js"></script>

<?php include '../Compartido/footer.php'; ?>