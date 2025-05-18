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

            <form id="form-contacto" action="../correo/gestion.php" method="post">
                <!-- Campo Nombre -->
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" id="nombre" name="nombre" class="form-control"
                           placeholder="Tu nombre" maxlength="50" required>
                    <div id="nombre-feedback" class="invalid-feedback"></div>
                    <small id="nombre-contador" class="form-text text-muted">0/50 caracteres</small>
                </div>

                <!-- Campo Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control"
                           placeholder="tu@correo.com" required>
                    <div id="email-feedback" class="invalid-feedback"></div>
                    <small id="email-helper" class="form-text text-muted">
                        <i class="fas fa-envelope"></i> Introduce un email válido
                    </small>
                </div>

                <!-- Campo Teléfono -->
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono:</label>
                    <input type="tel" id="telefono" name="telefono" class="form-control"
                           placeholder="+34 123 456 789" required>
                    <div id="telefono-feedback" class="invalid-feedback"></div>
                    <small id="telefono-helper" class="form-text text-muted">
                        <i class="fas fa-phone"></i> Formato español: +34 123 456 789
                    </small>
                </div>

                <!-- Campo de Consulta -->
                <div class="mb-3">
                    <label for="consulta" class="form-label">Consulta:</label>
                    <textarea id="consulta" name="consulta" class="form-control"
                              placeholder="Escribe aquí tu consulta..." rows="5"
                              maxlength="1000" required></textarea>
                    <div id="consulta-feedback" class="invalid-feedback"></div>
                    <div class="d-flex justify-content-between">
                        <small id="consulta-contador" class="form-text text-muted">0/1000 caracteres</small>
                        <small id="palabras-contador" class="form-text text-muted">0 palabras</small>
                    </div>
                </div>

                <!-- Checkbox de Aceptación de Política -->
                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="politica" name="politica" required>
                    <label class="form-check-label" for="politica">
                        <i class="fas fa-shield-alt"></i>
                        Acepto recibir información comercial por email
                    </label>
                    <div id="politica-feedback" class="invalid-feedback"></div>
                </div>

                <!-- Indicador de Estado del Formulario -->
                <div id="form-status" class="mb-3" style="display: none;">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        <span id="status-text">Validando formulario...</span>
                    </div>
                </div>

                <!-- Botón de Envío -->
                <div class="d-flex justify-content-between">
                    <button type="button" id="btn-limpiar" class="btn btn-outline-secondary">
                        <i class="fas fa-eraser"></i> Limpiar Formulario
                    </button>
                    <button type="submit" id="btn-enviar" class="btn btn-primary submit-btn">
                        <i class="fas fa-paper-plane"></i> Enviar Consulta
                    </button>
                </div>
            </form>
        </section>

        <script>
            $(document).ready(function() {
                // 1. Variables jQuery - Referencias cacheadas para mejor rendimiento
                const $form = $('#form-contacto');
                const $nombreInput = $('#nombre');
                const $nombreFeedback = $('#nombre-feedback');
                const $nombreContador = $('#nombre-contador');
                const $emailInput = $('#email');
                const $emailFeedback = $('#email-feedback');
                const $emailHelper = $('#email-helper');
                const $telefonoInput = $('#telefono');
                const $telefonoFeedback = $('#telefono-feedback');
                const $telefonoHelper = $('#telefono-helper');
                const $consultaInput = $('#consulta');
                const $consultaFeedback = $('#consulta-feedback');
                const $consultaContador = $('#consulta-contador');
                const $palabrasContador = $('#palabras-contador');
                const $politicaInput = $('#politica');
                const $politicaFeedback = $('#politica-feedback');
                const $btnLimpiar = $('#btn-limpiar');
                const $btnEnviar = $('#btn-enviar');
                const $formStatus = $('#form-status');
                const $statusText = $('#status-text');

                // 2. Funciones auxiliares para validación
                function setInvalid($input, $feedback, message) {
                    $input.addClass('is-invalid').removeClass('is-valid');
                    $feedback.text(message);
                }

                function setValid($input, $feedback) {
                    $input.removeClass('is-invalid').addClass('is-valid');
                    $feedback.text('');
                }

                function isFormValid() {
                    return $('.is-invalid').length === 0 && $('.form-control:invalid').length === 0;
                }

                function updateFormStatus() {
                    const totalCampos = 5;
                    const camposValidos = $('.is-valid').length;
                    const porcentaje = Math.round((camposValidos / totalCampos) * 100);

                    if (camposValidos === 0) {
                        $formStatus.hide();
                    } else {
                        $formStatus.show();
                        if (porcentaje === 100) {
                            $formStatus.find('.alert')
                                .removeClass('alert-info alert-warning')
                                .addClass('alert-success');
                            $statusText.html('<i class="fas fa-check-circle"></i> ¡Formulario completo y válido!');
                        } else if (porcentaje >= 50) {
                            $formStatus.find('.alert')
                                .removeClass('alert-info alert-success')
                                .addClass('alert-warning');
                            $statusText.html(`<i class="fas fa-clock"></i> Progreso: ${porcentaje}% completado`);
                        } else {
                            $formStatus.find('.alert')
                                .removeClass('alert-success alert-warning')
                                .addClass('alert-info');
                            $statusText.html(`<i class="fas fa-edit"></i> Completando formulario... ${porcentaje}%`);
                        }
                    }
                }

                // 3. Validación del nombre
                $nombreInput.on('input', function() {
                    const valor = $(this).val().trim();
                    const longitud = valor.length;

                    // Actualizar contador
                    $nombreContador.text(`${longitud}/50 caracteres`);

                    // Cambiar color del contador según longitud
                    $nombreContador.removeClass('text-muted text-success text-warning text-danger');

                    if (longitud > 40) {
                        $nombreContador.addClass('text-warning');
                    } else if (longitud > 0) {
                        $nombreContador.addClass('text-success');
                    } else {
                        $nombreContador.addClass('text-muted');
                    }

                    // Validaciones
                    if (longitud === 0) {
                        setInvalid($nombreInput, $nombreFeedback, 'El nombre es obligatorio');
                    } else if (longitud < 2) {
                        setInvalid($nombreInput, $nombreFeedback, 'El nombre debe tener al menos 2 caracteres');
                    } else if (longitud > 50) {
                        setInvalid($nombreInput, $nombreFeedback, 'El nombre no puede exceder los 50 caracteres');
                    } else if (!/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/.test(valor)) {
                        setInvalid($nombreInput, $nombreFeedback, 'El nombre solo puede contener letras y espacios');
                    } else {
                        setValid($nombreInput, $nombreFeedback);
                    }

                    updateFormStatus();
                });

                // 4. Validación del email
                $emailInput.on('input blur', function() {
                    const valor = $(this).val().trim();
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                    // Cambiar color del helper según validez
                    $emailHelper.removeClass('text-muted text-success text-danger');

                    if (valor === '') {
                        setInvalid($emailInput, $emailFeedback, 'El email es obligatorio');
                        $emailHelper.addClass('text-muted');
                    } else if (!emailRegex.test(valor)) {
                        setInvalid($emailInput, $emailFeedback, 'Por favor, introduce un email válido');
                        $emailHelper.addClass('text-danger');
                    } else if (valor.length > 100) {
                        setInvalid($emailInput, $emailFeedback, 'El email no puede exceder los 100 caracteres');
                        $emailHelper.addClass('text-danger');
                    } else {
                        setValid($emailInput, $emailFeedback);
                        $emailHelper.addClass('text-success');
                    }

                    updateFormStatus();
                });

                // 5. Validación del teléfono
                $telefonoInput.on('input', function() {
                    let valor = $(this).val().trim();

                    // Auto-formatear teléfono español
                    valor = valor.replace(/\D/g, ''); // Eliminar todo excepto números
                    if (valor.startsWith('34')) {
                        valor = '+' + valor;
                    } else if (valor.length === 9 && valor.startsWith('6,7,8,9'.split(',').some(d => valor.startsWith(d)))) {
                        valor = '+34 ' + valor;
                    }

                    $(this).val(valor);

                    // Validaciones
                    const telefonoRegex = /^(\+34\s?)?[67890]\d{8}$/;

                    $telefonoHelper.removeClass('text-muted text-success text-danger');

                    if (valor === '') {
                        setInvalid($telefonoInput, $telefonoFeedback, 'El teléfono es obligatorio');
                        $telefonoHelper.addClass('text-muted');
                    } else if (!telefonoRegex.test(valor.replace(/\s/g, ''))) {
                        setInvalid($telefonoInput, $telefonoFeedback, 'Introduce un teléfono español válido');
                        $telefonoHelper.addClass('text-danger')
                            .html('<i class="fas fa-exclamation-triangle"></i> Formato: +34 123 456 789');
                    } else {
                        setValid($telefonoInput, $telefonoFeedback);
                        $telefonoHelper.addClass('text-success')
                            .html('<i class="fas fa-check"></i> Teléfono válido');
                    }

                    updateFormStatus();
                });

                // 6. Validación de la consulta
                $consultaInput.on('input', function() {
                    const valor = $(this).val().trim();
                    const longitud = valor.length;
                    const palabras = valor.split(/\s+/).filter(Boolean).length;

                    // Actualizar contadores
                    $consultaContador.text(`${longitud}/1000 caracteres`);
                    $palabrasContador.text(`${palabras} palabras`);

                    // Cambiar colores de contadores
                    $consultaContador.removeClass('text-muted text-success text-warning text-danger');
                    $palabrasContador.removeClass('text-muted text-success text-warning');

                    if (longitud > 800) {
                        $consultaContador.addClass('text-warning');
                    } else if (longitud > 0) {
                        $consultaContador.addClass('text-success');
                    } else {
                        $consultaContador.addClass('text-muted');
                    }

                    if (palabras > 150) {
                        $palabrasContador.addClass('text-warning');
                    } else if (palabras > 0) {
                        $palabrasContador.addClass('text-success');
                    } else {
                        $palabrasContador.addClass('text-muted');
                    }

                    // Validaciones
                    if (longitud === 0) {
                        setInvalid($consultaInput, $consultaFeedback, 'La consulta es obligatoria');
                    } else if (longitud < 10) {
                        setInvalid($consultaInput, $consultaFeedback, 'La consulta debe tener al menos 10 caracteres');
                    } else if (longitud > 1000) {
                        setInvalid($consultaInput, $consultaFeedback, 'La consulta no puede exceder los 1000 caracteres');
                    } else if (palabras < 3) {
                        setInvalid($consultaInput, $consultaFeedback, 'La consulta debe tener al menos 3 palabras');
                    } else {
                        setValid($consultaInput, $consultaFeedback);
                    }

                    updateFormStatus();
                });

                // 7. Validación del checkbox de información comercial
                $politicaInput.on('change', function() {
                    if (!$(this).is(':checked')) {
                        setInvalid($politicaInput, $politicaFeedback, 'Debes aceptar recibir información comercial');
                    } else {
                        setValid($politicaInput, $politicaFeedback);
                    }

                    updateFormStatus();
                });

                // 8. Botón limpiar formulario
                $btnLimpiar.on('click', function() {
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            title: '¿Limpiar formulario?',
                            text: "Se perderán todos los datos introducidos",
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Sí, limpiar',
                            cancelButtonText: 'Cancelar'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                limpiarFormulario();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Formulario limpiado',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            }
                        });
                    } else {
                        if (confirm('¿Estás seguro de que quieres limpiar el formulario?')) {
                            limpiarFormulario();
                        }
                    }
                });

                function limpiarFormulario() {
                    $form[0].reset();
                    $('.form-control, .form-check-input').removeClass('is-valid is-invalid');
                    $('.invalid-feedback').text('');
                    $nombreContador.text('0/50 caracteres').attr('class', 'form-text text-muted');
                    $consultaContador.text('0/1000 caracteres').attr('class', 'form-text text-muted');
                    $palabrasContador.text('0 palabras').attr('class', 'form-text text-muted');
                    $emailHelper.attr('class', 'form-text text-muted').html('<i class="fas fa-envelope"></i> Introduce un email válido');
                    $telefonoHelper.attr('class', 'form-text text-muted').html('<i class="fas fa-phone"></i> Formato español: +34 123 456 789');
                    $formStatus.hide();
                    $nombreInput.focus();
                }

                // 9. Validación del formulario al enviar
                $form.on('submit', function(event) {
                    // Disparar todas las validaciones
                    $nombreInput.trigger('input');
                    $emailInput.trigger('blur');
                    $telefonoInput.trigger('input');
                    $consultaInput.trigger('input');
                    $politicaInput.trigger('change');

                    // Verificar si hay errores
                    if (!isFormValid()) {
                        event.preventDefault();

                        // Mostrar mensaje de error
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Formulario incompleto',
                                text: 'Por favor, corrija los errores señalados antes de enviar',
                                confirmButtonText: 'Revisar formulario'
                            });
                        } else {
                            alert('Por favor, corrija los errores antes de enviar');
                        }

                        // Hacer scroll al primer error
                        const $firstError = $('.is-invalid').first();
                        if ($firstError.length) {
                            $('html, body').animate({
                                scrollTop: $firstError.offset().top - 100
                            }, 500);
                        }
                    } else {
                        // Mostrar indicador de envío
                        if (typeof Swal !== 'undefined') {
                            Swal.fire({
                                title: 'Enviando consulta',
                                text: 'Por favor, espere...',
                                didOpen: () => {
                                    Swal.showLoading();
                                },
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });
                        }

                        // Deshabilitar botón para evitar envíos múltiples
                        $btnEnviar.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Enviando...');
                    }
                });

                // 10. Función de inicialización
                function inicializar() {
                    // Focus en el primer campo
                    $nombreInput.focus();
                }

                // 11. Ejecutar inicialización
                inicializar();

            });
        </script>

    </main>
<?php include_once '../Compartido/footer.php'?>