<?php include '../Compartido/header.php'?>

    <h1 class="title">Contacto</h1>

    <main>

        <!-- Sección de Contacto -->
    <section class="contact-section">
        <h2>Información de la Empresa</h2>

        <div class="contact-content">
            <!-- Información de Contacto -->
            <div class="contact-info">
                <div class="contact-details">
                    <strong>Email:</strong> contacto@empresa.com
                </div>
                <div class="contact-details">
                    <strong>Teléfono:</strong> +34 123 456 789
                </div>
            </div>

            <!-- Mapa de Google Maps -->
            <div class="contact-map">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3128.7306727469527!2d-0.4787971235426834!3d38.35521527851216!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd6237a7ab6f87c7%3A0xf9b9ab59e57e5c2b!2sC.%20San%20Ignacio%20Loyola%2C%2030%2C%2003013%20Alicante%20(Alacant)%2C%20Alicante!5e0!3m2!1ses!2ses!4v1731237872474!5m2!1ses!2ses" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <section class="contact-form">
        <h2>Formulario de Contacto</h2>
        
        <form action="../correo/gestion.php" method="post">
            <!-- Campo Nombre -->
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Tu nombre" maxlength="30" pattern="[A-Za-z0-9]+" required>
            </div>

            <!-- Campo Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-control" placeholder="Tu correo electrónico" required>
            </div>

            <!-- Campo Teléfono -->
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono:</label>
                <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="Tu número de teléfono" required>
            </div>

            <!-- Campo de Consulta -->
            <div class="mb-3">
                <label for="consulta" class="form-label">Consulta:</label>
                <textarea id="consulta" name="consulta" class="form-control" placeholder="Escribe aquí tu consulta" rows="4" required></textarea>
            </div>

            <!-- Checkbox de Aceptación de Política -->
            <div class="form-check mb-3">
                <input type="checkbox" class="form-check-input" id="politica" name="politica" required>
                <label class="form-check-label" for="politica">Acepto la política de privacidad y cookies</label>
            </div>

            <!-- Botón de Envío -->
            <button type="submit" class="btn btn-primary submit-btn">Enviar</button>
        </form>
    </section>
    </main>
    <?php include_once '../Compartido/footer.php'?>
