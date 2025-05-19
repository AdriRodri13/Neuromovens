<?php include '../Compartido/header.php'?>

    <div class="container py-4">
        <!-- Título Principal -->
        <div class="text-center mb-5">
            <h1 class="title">Contacto</h1>
            <p class="lead text-muted">Estamos aquí para ayudarte</p>
        </div>

        <!-- Información de Contacto -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="card shadow-sm">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Información básica -->
                            <div class="col-12 col-md-4">
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">Email</h6>
                                    <a href="mailto:contacto@empresa.com" class="text-decoration-none">
                                        contacto@empresa.com
                                    </a>
                                </div>
                                <div class="mb-3">
                                    <h6 class="text-muted mb-2">Teléfono</h6>
                                    <a href="tel:+34123456789" class="text-decoration-none">
                                        +34 123 456 789
                                    </a>
                                </div>
                                <div>
                                    <h6 class="text-muted mb-2">Dirección</h6>
                                    <p class="mb-0">C. San Ignacio Loyola, 30<br>03013 Alicante, España</p>
                                </div>
                            </div>

                            <!-- Mapa -->
                            <div class="col-12 col-md-8">
                                <div class="ratio ratio-16x9 rounded overflow-hidden">
                                    <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3128.7306727469527!2d-0.4787971235426834!3d38.35521527851216!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6237a7ab6f87c7%3A0xf9b9ab59e57e5c2b!2sC.%20San%20Ignacio%20Loyola%2C%2030%2C%2003013%20Alicante%20(Alacant)%2C%20Alicante!5e0!3m2!1ses!2ses!4v1731237872474!5m2!1ses!2ses"
                                            loading="lazy"
                                            style="border: 0;"
                                            allowfullscreen
                                            aria-hidden="false"
                                            tabindex="0">
                                    </iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulario de Contacto -->
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="card-title mb-0">Envíanos tu consulta</h3>
                    </div>

                    <div class="card-body p-4">
                        <form id="form-contacto" action="../correo/gestion.php" method="post">

                            <!-- Campo Nombre -->
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre completo</label>
                                <input type="text"
                                       id="nombre"
                                       name="nombre"
                                       class="form-control"
                                       placeholder="Tu nombre completo"
                                       required>
                            </div>

                            <!-- Campo Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email"
                                       id="email"
                                       name="email"
                                       class="form-control"
                                       placeholder="tu@email.com"
                                       required>
                            </div>

                            <!-- Campo Teléfono -->
                            <div class="mb-3">
                                <label for="telefono" class="form-label">Teléfono</label>
                                <input type="tel"
                                       id="telefono"
                                       name="telefono"
                                       class="form-control"
                                       placeholder="+34 123 456 789"
                                       required>
                            </div>

                            <!-- Campo de Consulta -->
                            <div class="mb-4">
                                <label for="consulta" class="form-label">Tu consulta</label>
                                <textarea id="consulta"
                                          name="consulta"
                                          class="form-control"
                                          placeholder="Cuéntanos en qué podemos ayudarte..."
                                          rows="5"
                                          required></textarea>
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
                                        Acepto recibir información por email
                                    </label>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button"
                                        id="btn-limpiar"
                                        class="btn btn-outline-secondary">
                                    Limpiar
                                </button>
                                <button type="submit"
                                        id="btn-enviar"
                                        class="btn btn-primary">
                                    Enviar consulta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../js/contacto.js"></script>
<?php include_once '../Compartido/footer.php'?>