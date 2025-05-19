/**
 * Archivo: ContactoFormulario.js
 * Descripción: Maneja la funcionalidad del formulario de contacto
 *
 * Funcionalidades principales:
 * - Validación completa en tiempo real de todos los campos
 * - Contador de caracteres dinámico para nombre y consulta
 * - Contador de palabras para la consulta
 * - Formateo automático de teléfono español
 * - Indicador de progreso del formulario
 * - Limpieza del formulario con confirmación
 * - Prevención de envíos múltiples
 *
 * Dependencias:
 * - jQuery (requerido)
 * - SweetAlert2 (opcional, para mejores notificaciones)
 * - Bootstrap (para clases CSS y validación visual)
 * - FontAwesome (para iconos)
 */

$(document).ready(function() {
    /**
     * ===============================================
     * SECCIÓN 1: CONFIGURACIÓN Y VARIABLES GLOBALES
     * ===============================================
     */

        // Selectores jQuery cacheados para mejor rendimiento
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

    // Configuración de validaciones
    const VALIDACION_CONFIG = {
        nombre: {
            min: 2,
            max: 50,
            warningThreshold: 40,
            pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/, // Solo letras y espacios
            patternMessage: 'El nombre solo puede contener letras y espacios'
        },
        email: {
            max: 100,
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, // Patrón básico de email
            advancedPattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/ // Patrón más estricto
        },
        telefono: {
            pattern: /^(\+34\s?)?[67890]\d{8}$/, // Patrón para teléfonos españoles
            formatPattern: /^(\+34\s?)?\d{9}$/ // Para formateo automático
        },
        consulta: {
            min: 10,
            max: 1000,
            minPalabras: 3,
            warningThreshold: 800,
            warningPalabras: 150
        }
    };

    // Variables para control de estado
    let formSubmitted = false;
    const TOTAL_CAMPOS = 5; // Nombre, Email, Teléfono, Consulta, Política

    /**
     * ===============================================
     * SECCIÓN 2: FUNCIONES UTILITARIAS
     * ===============================================
     */

    /**
     * Marca un campo como inválido y muestra mensaje de error
     * @param {jQuery} $input - Campo a marcar como inválido
     * @param {jQuery} $feedback - Elemento para mostrar el error
     * @param {string} message - Mensaje de error
     */
    function setFieldInvalid($input, $feedback, message) {
        $input.addClass('is-invalid').removeClass('is-valid');
        $feedback.text(message).show();
    }

    /**
     * Marca un campo como válido y limpia errores
     * @param {jQuery} $input - Campo a marcar como válido
     * @param {jQuery} $feedback - Elemento de error a limpiar
     */
    function setFieldValid($input, $feedback) {
        $input.removeClass('is-invalid').addClass('is-valid');
        $feedback.text('').hide();
    }

    /**
     * Verifica si el formulario completo es válido
     * @returns {boolean} - true si no hay campos inválidos
     */
    function isFormValid() {
        return $('.is-invalid').length === 0 && $('.form-control:invalid').length === 0;
    }

    /**
     * Cuenta las palabras en un texto
     * @param {string} text - Texto a analizar
     * @returns {number} - Número de palabras
     */
    function contarPalabras(text) {
        return text.trim().split(/\s+/).filter(Boolean).length;
    }

    /**
     * Formatea un número de teléfono español
     * @param {string} telefono - Número de teléfono sin formatear
     * @returns {string} - Número formateado
     */
    function formatearTelefonoEspanol(telefono) {
        // Eliminar todo excepto números
        let numeros = telefono.replace(/\D/g, '');

        // Si ya tiene el prefijo 34, agregar +
        if (numeros.startsWith('34') && numeros.length === 11) {
            return '+' + numeros.substring(0, 2) + ' ' + numeros.substring(2);
        }

        // Si es un número de 9 dígitos español válido, agregar +34
        if (numeros.length === 9 && /^[67890]/.test(numeros)) {
            return '+34 ' + numeros;
        }

        return telefono; // Devolver original si no se puede formatear
    }

    /**
     * ===============================================
     * SECCIÓN 3: FUNCIONES DE VALIDACIÓN
     * ===============================================
     */

    /**
     * Valida el campo nombre
     * @returns {boolean} - true si es válido
     */
    function validarNombre() {
        const valor = $nombreInput.val().trim();
        const longitud = valor.length;

        // Actualizar contador
        $nombreContador.text(`${longitud}/${VALIDACION_CONFIG.nombre.max} caracteres`);

        // Actualizar color del contador
        $nombreContador.removeClass('text-muted text-success text-warning text-danger');
        if (longitud > VALIDACION_CONFIG.nombre.warningThreshold) {
            $nombreContador.addClass('text-warning');
        } else if (longitud > 0) {
            $nombreContador.addClass('text-success');
        } else {
            $nombreContador.addClass('text-muted');
        }

        // Validaciones
        if (longitud === 0) {
            setFieldInvalid($nombreInput, $nombreFeedback, 'El nombre es obligatorio');
            return false;
        } else if (longitud < VALIDACION_CONFIG.nombre.min) {
            setFieldInvalid($nombreInput, $nombreFeedback, `El nombre debe tener al menos ${VALIDACION_CONFIG.nombre.min} caracteres`);
            return false;
        } else if (longitud > VALIDACION_CONFIG.nombre.max) {
            setFieldInvalid($nombreInput, $nombreFeedback, `El nombre no puede exceder los ${VALIDACION_CONFIG.nombre.max} caracteres`);
            return false;
        } else if (!VALIDACION_CONFIG.nombre.pattern.test(valor)) {
            setFieldInvalid($nombreInput, $nombreFeedback, VALIDACION_CONFIG.nombre.patternMessage);
            return false;
        } else {
            setFieldValid($nombreInput, $nombreFeedback);
            return true;
        }
    }

    /**
     * Valida el campo email
     * @returns {boolean} - true si es válido
     */
    function validarEmail() {
        const valor = $emailInput.val().trim();

        // Actualizar helper visual
        $emailHelper.removeClass('text-muted text-success text-danger');

        if (valor === '') {
            setFieldInvalid($emailInput, $emailFeedback, 'El email es obligatorio');
            $emailHelper.addClass('text-muted');
            return false;
        } else if (!VALIDACION_CONFIG.email.pattern.test(valor)) {
            setFieldInvalid($emailInput, $emailFeedback, 'Por favor, introduce un email válido');
            $emailHelper.addClass('text-danger')
                .html('<i class="fas fa-exclamation-triangle me-1"></i>Formato incorrecto');
            return false;
        } else if (valor.length > VALIDACION_CONFIG.email.max) {
            setFieldInvalid($emailInput, $emailFeedback, `El email no puede exceder los ${VALIDACION_CONFIG.email.max} caracteres`);
            $emailHelper.addClass('text-danger');
            return false;
        } else if (!VALIDACION_CONFIG.email.advancedPattern.test(valor)) {
            setFieldInvalid($emailInput, $emailFeedback, 'El formato del email no es correcto');
            $emailHelper.addClass('text-danger')
                .html('<i class="fas fa-exclamation-triangle me-1"></i>Verifique el formato');
            return false;
        } else {
            setFieldValid($emailInput, $emailFeedback);
            $emailHelper.addClass('text-success')
                .html('<i class="fas fa-check me-1"></i>Email válido');
            return true;
        }
    }

    /**
     * Valida el campo teléfono
     * @returns {boolean} - true si es válido
     */
    function validarTelefono() {
        const valor = $telefonoInput.val().trim();

        // Formateo automático al escribir
        if (valor && !formSubmitted) {
            const valorFormateado = formatearTelefonoEspanol(valor);
            if (valorFormateado !== valor) {
                $telefonoInput.val(valorFormateado);
            }
        }

        // Actualizar helper visual
        $telefonoHelper.removeClass('text-muted text-success text-danger');

        if (valor === '') {
            setFieldInvalid($telefonoInput, $telefonoFeedback, 'El teléfono es obligatorio');
            $telefonoHelper.addClass('text-muted');
            return false;
        } else if (!VALIDACION_CONFIG.telefono.pattern.test(valor.replace(/\s/g, ''))) {
            setFieldInvalid($telefonoInput, $telefonoFeedback, 'Introduce un teléfono español válido');
            $telefonoHelper.addClass('text-danger')
                .html('<i class="fas fa-exclamation-triangle me-1"></i>Formato: +34 123 456 789');
            return false;
        } else {
            setFieldValid($telefonoInput, $telefonoFeedback);
            $telefonoHelper.addClass('text-success')
                .html('<i class="fas fa-check me-1"></i>Teléfono válido');
            return true;
        }
    }

    /**
     * Valida el campo consulta
     * @returns {boolean} - true si es válido
     */
    function validarConsulta() {
        const valor = $consultaInput.val().trim();
        const longitud = valor.length;
        const palabras = contarPalabras(valor);

        // Actualizar contadores
        $consultaContador.text(`${longitud}/${VALIDACION_CONFIG.consulta.max} caracteres`);
        $palabrasContador.text(`${palabras} palabras`);

        // Actualizar colores de contadores
        $consultaContador.removeClass('text-muted text-success text-warning text-danger');
        $palabrasContador.removeClass('text-muted text-success text-warning');

        if (longitud > VALIDACION_CONFIG.consulta.warningThreshold) {
            $consultaContador.addClass('text-warning');
        } else if (longitud > 0) {
            $consultaContador.addClass('text-success');
        } else {
            $consultaContador.addClass('text-muted');
        }

        if (palabras > VALIDACION_CONFIG.consulta.warningPalabras) {
            $palabrasContador.addClass('text-warning');
        } else if (palabras > 0) {
            $palabrasContador.addClass('text-success');
        } else {
            $palabrasContador.addClass('text-muted');
        }

        // Validaciones
        if (longitud === 0) {
            setFieldInvalid($consultaInput, $consultaFeedback, 'La consulta es obligatoria');
            return false;
        } else if (longitud < VALIDACION_CONFIG.consulta.min) {
            setFieldInvalid($consultaInput, $consultaFeedback, `La consulta debe tener al menos ${VALIDACION_CONFIG.consulta.min} caracteres`);
            return false;
        } else if (longitud > VALIDACION_CONFIG.consulta.max) {
            setFieldInvalid($consultaInput, $consultaFeedback, `La consulta no puede exceder los ${VALIDACION_CONFIG.consulta.max} caracteres`);
            return false;
        } else if (palabras < VALIDACION_CONFIG.consulta.minPalabras) {
            setFieldInvalid($consultaInput, $consultaFeedback, `La consulta debe tener al menos ${VALIDACION_CONFIG.consulta.minPalabras} palabras`);
            return false;
        } else {
            setFieldValid($consultaInput, $consultaFeedback);
            return true;
        }
    }

    /**
     * Valida el checkbox de política
     * @returns {boolean} - true si es válido
     */
    function validarPolitica() {
        if (!$politicaInput.is(':checked')) {
            setFieldInvalid($politicaInput, $politicaFeedback, 'Debe aceptar recibir información comercial');
            return false;
        } else {
            setFieldValid($politicaInput, $politicaFeedback);
            return true;
        }
    }

    /**
     * ===============================================
     * SECCIÓN 4: INDICADOR DE PROGRESO
     * ===============================================
     */

    /**
     * Actualiza el indicador de progreso del formulario
     */
    function updateFormStatus() {
        const camposValidos = $('.is-valid').length;
        const porcentaje = Math.round((camposValidos / TOTAL_CAMPOS) * 100);

        if (camposValidos === 0) {
            $formStatus.hide();
        } else {
            $formStatus.show();
            const $alert = $formStatus.find('.alert');

            if (porcentaje === 100) {
                $alert.removeClass('alert-info alert-warning')
                    .addClass('alert-success');
                $statusText.html('<i class="fas fa-check-circle me-2"></i>¡Formulario completo y válido! Listo para enviar.');
            } else if (porcentaje >= 60) {
                $alert.removeClass('alert-info alert-success')
                    .addClass('alert-warning');
                $statusText.html(`<i class="fas fa-clock me-2"></i>Progreso: ${porcentaje}% completado. ¡Ya casi está!`);
            } else {
                $alert.removeClass('alert-success alert-warning')
                    .addClass('alert-info');
                $statusText.html(`<i class="fas fa-edit me-2"></i>Completando formulario... ${porcentaje}%`);
            }
        }
    }

    /**
     * ===============================================
     * SECCIÓN 5: FUNCIONES DE LIMPIEZA
     * ===============================================
     */

    /**
     * Limpia completamente el formulario y restaura el estado inicial
     */
    function limpiarFormulario() {
        // Resetear formulario
        $form[0].reset();

        // Limpiar clases de validación
        $('.form-control, .form-check-input').removeClass('is-valid is-invalid');

        // Limpiar mensajes de error
        $('.invalid-feedback').text('').hide();

        // Restaurar contadores a estado inicial
        $nombreContador.text('0/50 caracteres').attr('class', 'form-text text-muted fw-bold');
        $consultaContador.text('0/1000 caracteres').attr('class', 'form-text text-muted fw-bold');
        $palabrasContador.text('0 palabras').attr('class', 'form-text text-info');

        // Restaurar helpers a estado inicial
        $emailHelper.attr('class', 'form-text text-muted')
            .html('<i class="fas fa-envelope me-1"></i>Introduce un email válido para recibir respuesta');
        $telefonoHelper.attr('class', 'form-text text-muted')
            .html('<i class="fas fa-phone me-1"></i>Formato español: +34 123 456 789');

        // Ocultar indicador de progreso
        $formStatus.hide();

        // Restaurar botón de envío
        $btnEnviar.prop('disabled', false)
            .html('<i class="fas fa-paper-plane me-2"></i>Enviar Consulta');

        // Reset de variables de estado
        formSubmitted = false;

        // Enfocar primer campo
        $nombreInput.focus();
    }

    /**
     * ===============================================
     * SECCIÓN 6: MANEJADORES DE EVENTOS
     * ===============================================
     */

    /**
     * Eventos de validación en tiempo real
     */
    $nombreInput.on('input', function() {
        validarNombre();
        updateFormStatus();
    });

    $emailInput.on('input blur', function() {
        validarEmail();
        updateFormStatus();
    });

    $telefonoInput.on('input', function() {
        validarTelefono();
        updateFormStatus();
    });

    $consultaInput.on('input', function() {
        validarConsulta();
        updateFormStatus();
    });

    $politicaInput.on('change', function() {
        validarPolitica();
        updateFormStatus();
    });

    /**
     * Evento del botón limpiar con confirmación
     */
    $btnLimpiar.on('click', function() {
        // Detectar si hay datos en el formulario
        const hayDatos = $nombreInput.val().trim() ||
            $emailInput.val().trim() ||
            $telefonoInput.val().trim() ||
            $consultaInput.val().trim() ||
            $politicaInput.is(':checked');

        if (!hayDatos) {
            // Si no hay datos, simplemente enfocar el primer campo
            $nombreInput.focus();
            return;
        }

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Limpiar formulario?',
                text: "Se perderán todos los datos introducidos",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-eraser me-1"></i>Sí, limpiar',
                cancelButtonText: '<i class="fas fa-times me-1"></i>Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    limpiarFormulario();
                    Swal.fire({
                        icon: 'success',
                        title: 'Formulario limpiado',
                        text: 'Puede comenzar a completar el formulario nuevamente',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        } else {
            if (confirm('¿Estás seguro de que quieres limpiar el formulario? Se perderán todos los datos.')) {
                limpiarFormulario();
            }
        }
    });

    /**
     * Evento de envío del formulario con validación completa
     */
    $form.on('submit', function(event) {
        // Marcar que se está intentando enviar
        formSubmitted = true;

        // Ejecutar todas las validaciones
        const nombreValido = validarNombre();
        const emailValido = validarEmail();
        const telefonoValido = validarTelefono();
        const consultaValida = validarConsulta();
        const politicaValida = validarPolitica();

        // Si hay errores, prevenir envío
        if (!nombreValido || !emailValido || !telefonoValido || !consultaValida || !politicaValida) {
            event.preventDefault();
            formSubmitted = false;

            // Mostrar mensaje de error
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Formulario incompleto',
                    html: 'Por favor, corrija los errores señalados antes de enviar.<br><small>Los campos marcados en rojo necesitan atención.</small>',
                    confirmButtonText: 'Revisar formulario',
                    footer: '<i class="fas fa-lightbulb"></i> Todos los campos son obligatorios'
                });
            } else {
                alert('Por favor, corrija los errores antes de enviar');
            }

            // Scroll suave al primer error
            const $firstError = $('.is-invalid').first();
            if ($firstError.length) {
                $('html, body').animate({
                    scrollTop: $firstError.offset().top - 100
                }, 500);
                $firstError.focus();
            }
        } else {
            // Todo válido, mostrar indicador de envío
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '<i class="fas fa-paper-plane"></i> Enviando consulta',
                    html: 'Por favor, espere mientras procesamos su solicitud...<br><small>No cierre esta ventana</small>',
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false
                });
            }

            // Deshabilitar botón para evitar envíos múltiples
            $btnEnviar.prop('disabled', true)
                .html('<i class="fas fa-spinner fa-spin me-2"></i>Enviando...');
        }
    });

    /**
     * ===============================================
     * SECCIÓN 7: FUNCIONES DE INICIALIZACIÓN
     * ===============================================
     */

    /**
     * Inicializa el formulario y todos sus componentes
     */
    function inicializarFormulario() {
        try {
            // Enfocar el primer campo
            $nombreInput.focus();

            // Aplicar mejoras visuales
            aplicarMejorasVisuales();

            // Log para debugging (remover en producción)
            console.log('Formulario de contacto inicializado correctamente');
            console.log('Configuración de validación:', VALIDACION_CONFIG);

        } catch (error) {
            console.error('Error al inicializar el formulario:', error);

            // Mostrar error al usuario si es crítico
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de inicialización',
                    text: 'Hubo un problema al cargar el formulario. Por favor, recargue la página.',
                    confirmButtonText: 'Recargar página',
                    allowOutsideClick: false
                }).then(() => {
                    window.location.reload();
                });
            }
        }
    }

    /**
     * Aplica mejoras visuales al formulario
     */
    function aplicarMejorasVisuales() {
        // Efecto de focus mejorado para inputs
        $('input, textarea, select').on('focus', function() {
            $(this).closest('.input-group, .form-control').addClass('shadow-sm');
        }).on('blur', function() {
            $(this).closest('.input-group, .form-control').removeClass('shadow-sm');
        });

        // Animación para mensajes de feedback
        $('.invalid-feedback').hide();

        // Mejorar accesibilidad
        $('[data-bs-toggle="tooltip"]').tooltip();

        // Placeholder dinámico para el campo consulta
        let placeholderIndex = 0;
        const placeholders = [
            "Describa detalladamente su consulta o necesidad...",
            "¿En qué podemos ayudarle?",
            "Cuéntenos sobre su proyecto...",
            "¿Tiene alguna pregunta específica?"
        ];

        setInterval(() => {
            if (!$consultaInput.is(':focus') && $consultaInput.val() === '') {
                $consultaInput.attr('placeholder', placeholders[placeholderIndex]);
                placeholderIndex = (placeholderIndex + 1) % placeholders.length;
            }
        }, 4000);
    }

    /**
     * ===============================================
     * SECCIÓN 8: MANEJO DE ERRORES GLOBALES
     * ===============================================
     */

    /**
     * Manejador de errores globales no capturados
     */
    $(window).on('error', function(event) {
        const error = event.originalEvent.error;
        console.error('Error no manejado en ContactoFormulario.js:', error);

        // En desarrollo, mostrar más detalles
        if (window.location.hostname === 'localhost') {
            console.error('Stack trace:', error.stack);
        }

        // En producción, enviar error a servicio de logging
        // enviarErrorAServidor(error);
    });

    /**
     * ===============================================
     * SECCIÓN 9: EJECUCIÓN DE INICIALIZACIÓN
     * ===============================================
     */

    // Ejecutar inicialización cuando el DOM esté completamente listo
    inicializarFormulario();

    /**
     * ===============================================
     * SECCIÓN 10: API PÚBLICA PARA TESTING (OPCIONAL)
     * ===============================================
     */

    // Exponer algunas funciones al scope global para testing
    window.ContactoFormValidator = {
        validarNombre: validarNombre,
        validarEmail: validarEmail,
        validarTelefono: validarTelefono,
        validarConsulta: validarConsulta,
        validarPolitica: validarPolitica,
        limpiarFormulario: limpiarFormulario,
        formatearTelefonoEspanol: formatearTelefonoEspanol,
        contarPalabras: contarPalabras,
        isFormValid: isFormValid,
        config: VALIDACION_CONFIG
    };

});