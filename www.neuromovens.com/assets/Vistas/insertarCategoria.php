<?php include '../Compartido/header.php'; ?>

    <body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white text-center">
                        <h2 class="card-title mb-0">
                            <i class="fas fa-plus-circle me-2"></i>
                            Insertar Nueva Categoría
                        </h2>
                    </div>

                    <div class="card-body p-4">
                        <form id="form-categoria" action="../Controlador/ControladorCategoria.php" method="post">
                            <!-- Campo oculto para indicar la acción de inserción -->
                            <input type="hidden" name="accion" value="insertar">

                            <!-- Campo para el nombre de la categoría -->
                            <div class="mb-4">
                                <label for="nombre_categoria" class="form-label fw-semibold">
                                    <i class="fas fa-folder me-1"></i>
                                    Nombre de la Categoría
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-tag"></i>
                                    </span>
                                    <input type="text"
                                           id="nombre_categoria"
                                           name="nombreCategoria"
                                           class="form-control form-control-lg"
                                           placeholder="Ingrese el nombre de la categoría..."
                                           required
                                           autocomplete="off">
                                </div>
                                <div id="categoria-feedback" class="invalid-feedback"></div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Ejemplo: Electrónicos, Ropa, Hogar, etc.
                                </small>
                            </div>

                            <!-- Botón de envío -->
                            <div class="d-grid">
                                <button type="submit" id="btn-insertar" class="btn btn-success btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Insertar Categoría
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Footer del Card -->
                    <div class="card-footer bg-light text-center text-muted">
                        <small>
                            <i class="fas fa-lightbulb me-1"></i>
                            Las categorías organizan mejor sus productos
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir archivo JavaScript separado -->
    <script src="../js/InsertarCategoria.js"></script>
    </body>

<?php include '../Compartido/footer.php'; ?>