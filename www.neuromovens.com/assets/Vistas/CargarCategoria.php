<?php
use Entidades\Categoria;
include '../Compartido/header.php';
?>

<?php if (isset($categoria) && $categoria instanceof Categoria) : ?>
    <body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h2 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Actualizar Categoría
                        </h2>
                    </div>

                    <div class="card-body p-4">
                        <form id="form-actualizar-categoria" action="../Controlador/ControladorCategoria.php" method="post">
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="id" value="<?= $categoria->getIdCategoria(); ?>">

                            <!-- Campo Nombre de la Categoría -->
                            <div class="mb-4">
                                <label for="nombre" class="form-label fw-semibold">
                                    <i class="fas fa-tag me-1"></i>
                                    Nombre de la categoría
                                </label>
                                <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-folder"></i>
                                        </span>
                                    <input type="text"
                                           id="nombre"
                                           name="nombre"
                                           class="form-control form-control-lg"
                                           value="<?= htmlspecialchars($categoria->getNombreCategoria()); ?>"
                                           placeholder="Ingrese el nombre de la categoría..."
                                           required
                                           autocomplete="off">
                                </div>
                                <div id="nombre-feedback" class="invalid-feedback"></div>

                            </div>

                            <!-- Información de Modificación -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-clock me-1"></i>
                                    Fecha de modificación
                                </label>
                                <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    <div id="fecha-modificacion" class="form-control bg-light text-muted">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        Cargando fecha...
                                    </div>
                                </div>

                            </div>

                            <!-- Información Adicional (Solo lectura) -->
                            <div class="mb-4">
                                <div class="row">
                                    <div class="col-sm-6">
                                        <label class="form-label fw-semibold text-muted">
                                            <i class="fas fa-key me-1"></i>
                                            ID de Categoría
                                        </label>
                                        <div class="form-control-plaintext bg-light px-3 py-2 rounded border">
                                            #<?= $categoria->getIdCategoria(); ?>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
                                <button type="button"
                                        id="btn-cancelar"
                                        class="btn btn-outline-secondary btn-lg me-md-2">
                                    <i class="fas fa-times me-2"></i>
                                    Cancelar
                                </button>
                                <button type="submit"
                                        id="btn-actualizar"
                                        class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Actualizar Categoría
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Incluir archivo JavaScript separado -->
    <script src="../js/CargarCategoria.js"></script>
    </body>
<?php endif; ?>

<?php include '../Compartido/footer.php'; ?>