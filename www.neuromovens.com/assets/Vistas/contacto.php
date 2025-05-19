<?php include '../Compartido/header.php'?>

    <body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12">
                <!-- Título Principal -->
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-primary">
                        <i class="fas fa-headset me-3"></i>
                        Contacto
                    </h1>
                    <p class="lead text-muted">
                        Estamos aquí para ayudarte. Ponte en contacto con nosotros.
                    </p>
                </div>
            </div>
        </div>

        <!-- Sección de Información de la Empresa -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-info text-white text-center">
                        <h2 class="card-title mb-0">
                            <i class="fas fa-building me-2"></i>
                            Información de la Empresa
                        </h2>
                    </div>
                    <div class="card-body p-4">
                        <div class="row align-items-center">
                            <!-- Información de Contacto -->
                            <div class="col-lg-4 mb-4 mb-lg-0">
                                <div class="contact-info">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="bg-info rounded-circle p-3 me-3">
                                            <i class="fas fa-envelope text-white fs-4"></i>
                                        </div>
                                        <div>
                                            <strong class="text-info">Email:</strong>
                                            <br>
                                            <a href="mailto:contacto@empresa.com" class="text-decoration-none">
                                                contacto@empresa.com
                                            </a>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-info rounded-circle p-3 me-3">
                                            <i class="fas fa-phone text-white fs-4"></i>
                                        </div>
                                        <div>
                                            <strong class="text-info">Teléfono:</strong>
                                            <br>
                                            <a href="tel:+34123456789" class="text-decoration-none">
                                                +34 123 456 789
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mapa de Google Maps -->
                            <div class="col-lg-8">
                                <div class="ratio ratio-16x9 rounded overflow-hidden shadow-sm">
                                    <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3128.7306727469527!2d-0.4787971235426834!3d38.35521527851216!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6237a7ab6f87c7%3A0xf9b9ab59e57e5c2b!2sC.%20San%20Ignacio%20Loyola%2C%2030%2C%2003013%20Alicante%20(Alacant)%2C%20Alicante!5e0!3m2!1ses!2ses!4v1731237872474!5m2!1ses!2ses"
                                            loading="lazy"
                                            referrerpolicy="no-referrer-when-downgrade"
                                            style="border: 0;"
                                            allowfullscreen
                                            aria-hidden="false"
                                            tabindex="0">
                                    </iframe>
                                </div>
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        C. San Ignacio Loyola, 30, 03013 Alicante, España
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sección del Formulario de Contacto -->
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white text-center">
                        <h2 class="card-title mb-0">
                            <i class="fas fa-edit me-2"></i>
                            Formulario de Contacto
                        </h2>
                        <p class="mb-0 mt-2">
                            <small>Complete el formulario y nos pondremos en contacto con usted</small>
                        </p>
                    </div>

                    <div class="card-body p-4">
                        <form id="form-contacto" action="../correo/gestion.php" method="post">

                            <!-- Campo Nombre -->
                            <div class="mb-4">
                                <label for="nombre" class="form-label fw-semibold">
                                    <i class="fas fa-user me-1"></i>
                                    Nombre Completo
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-user-tag"></i>
                                    </span>
                                    <input type="text"
                                           id="nombre"
                                           name="nombre"
                                           class="form-control form-control-sm form-control-lg-lg"
                                           placeholder="Ingrese su nombre completo..."
                                           maxlength="50"
                                           required
                                           autocomplete="name">
                                </div>
                                <div id="nombre-feedback" class="invalid-feedback"></div>
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Solo letras y espacios
                                    </small>
                                    <small id="nombre-contador" class="form-text text-muted fw-bold">
                                        0/50 caracteres
                                    </small>
                                </div>
                            </div>

                            <!-- Campo Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-1"></i>
                                    Correo Electrónico
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <input type="email"
                                           id="email"
                                           name="email"
                                           class="form-control form-control-sm form-control-lg-lg"
                                           placeholder="su-email@ejemplo.com"
                                           required
                                           autocomplete="email">
                                </div>
                                <div id="email-feedback" class="invalid-feedback"></div>
                                <small id="email-helper" class="form-text text-muted">
                                    <i class="fas fa-envelope me-1"></i>
                                    Introduce un email válido para recibir respuesta
                                </small>
                            </div>

                            <!-- Campo Teléfono -->
                            <div class="mb-4">
                                <label for="telefono" class="form-label fw-semibold">
                                    <i class="fas fa-phone me-1"></i>
                                    Teléfono de Contacto
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-mobile-alt"></i>
                                    </span>
                                    <input type="tel"
                                           id="telefono"
                                           name="telefono"
                                           class="form-control form-control-sm form-control-lg-lg"
                                           placeholder="+34 123 456 789"
                                           required
                                           autocomplete="tel">
                                </div>
                                <div id="telefono-feedback" class="invalid-feedback"></div>
                                <small id="telefono-helper" class="form-text text-muted">
                                    <i class="fas fa-phone me-1"></i>
                                    Formato español: +34 123 456 789
                                </small>
                            </div>

                            <!-- Campo de Consulta -->
                            <div class="mb-4">
                                <label for="consulta" class="form-label fw-semibold">
                                    <i class="fas fa-comment-dots me-1"></i>
                                    Su Consulta
                                </label>
                                <textarea id="consulta"
                                          name="consulta"
                                          class="form-control"
                                          placeholder="Describa detalladamente su consulta o necesidad..."
                                          rows="6"
                                          maxlength="1000"
                                          required></textarea>
                                <div id="consulta-feedback" class="invalid-feedback"></div>
                                <div class="d-flex justify-content-between mt-2">
                                    <div>
                                        <small id="consulta-contador" class="form-text text-muted fw-bold">
                                            0/1000 caracteres
                                        </small>
                                        <span class="mx-2">•</span>
                                        <small id="palabras-contador" class="form-text text-info">
                                            0 palabras
                                        </small>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-lightbulb me-1"></i>
                                        Mínimo 10 caracteres, 3 palabras
                                    </small>
                                </div>
                            </div>

                            <!-- Checkbox de Política -->
                            <div class="mb-4">
                                <div class="form-check">
                                    <input type="checkbox"
                                           class="form-check-input"
                                           id="politica"
                                           name="politica"
                                           required>
                                    <label class="form-check-label" for="politica">
                                        <i class="fas fa-shield-alt text-success me-1"></i>
                                        Acepto recibir información comercial por email
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div id="politica-feedback" class="invalid-feedback"></div>
                                </div>
                                <small class="form-text text-muted ms-4">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Necesario para enviar respuesta y actualizaciones relacionadas
                                </small>
                            </div>

                            <!-- Indicador de Estado del Formulario -->
                            <div id="form-status" class="mb-4" style="display: none;">
                                <div class="alert alert-info mb-0">
                                    <span id="status-text">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Validando formulario...
                                    </span>
                                </div>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-between mt-5">
                                <button type="button"
                                        id="btn-limpiar"
                                        class="btn btn-outline-secondary btn-lg">
                                    <i class="fas fa-eraser me-2"></i>
                                    Limpiar Formulario
                                </button>
                                <button type="submit"
                                        id="btn-enviar"
                                        class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane me-2"></i>
                                    Enviar Consulta
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Footer del Formulario -->
                    <div class="card-footer bg-light text-center text-muted">
                        <small>
                            <i class="fas fa-clock me-1"></i>
                            Tiempo de respuesta aproximado: 24-48 horas
                            <span class="mx-2">•</span>
                            <i class="fas fa-shield-alt me-1"></i>
                            Sus datos están protegidos
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Información Adicional -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="card border-0 bg-transparent">
                    <div class="card-body text-center">
                        <h3 class="h5 text-muted mb-3">
                            <i class="fas fa-question-circle me-2"></i>
                            ¿Necesita ayuda inmediata?
                        </h3>
                        <div class="row justify-content-center">
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-phone-alt text-primary me-2"></i>
                                    <span>Llámenos: <strong>+34 123 456 789</strong></span>
                                </div>
                            </div>
                            <div class="col-md-6 col-lg-4 mb-3">
                                <div class="d-flex align-items-center justify-content-center">
                                    <i class="fas fa-envelope text-primary me-2"></i>
                                    <span>Email: <strong>contacto@empresa.com</strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir archivo JavaScript separado -->
    <script src="../js/contacto.js"></script>
    </body>

<?php include_once '../Compartido/footer.php'?>