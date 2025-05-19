/**
 * Archivo: ActualizarUsuario.js
 * Descripción: Maneja la funcionalidad del formulario de actualización de usuarios
 *
 * Funcionalidades principales:
 * - Validación en tiempo real del nombre de usuario y email
 * - Contador de caracteres dinámico para el nombre de usuario
 * - Validación de formato de email con expresiones regulares
 * - Validación de roles (si aplica)
 * - Confirmación antes de cancelar cambios
 * - Debouncing para validación de email en tiempo real
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
    const $form = $('#form-actualizar-usuario');
    const $nombreUsuarioInput = $('#nombre_usuario');
    const $nombreUsuarioFeedback = $('#nombre-usuario-feedback');
    const $nombreUsuarioContador = $('#nombre-usuario-contador');
    const $emailInput = $('#email');
    const $emailFeedback = $('#email-feedback');
    const $emailHelper = $('#email-helper');
    const $rolSelect = $('#rol');
    const $rolFeedback = $('#rol-feedback');
    const $btnCancelar = $('#btn-cancelar');
    const $btnActualizar = $('#btn-actualizar');
    const $fechaModificacion = $('#fecha-modificacion');

    // Configuración de validaciones
    const VALIDACION_CONFIG = {
        nombreUsuario: {
            min: 3,
            max: 30,
            warningThreshold: 25,
            pattern: /^[a-zA-Z0-9_]+$/, // Solo letras, números y guiones bajos
            patternMessage: 'Solo se permiten letras, números y guiones bajos'
        },
        email: {
            max: 100,
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/, // Patrón básico de email
            advancedPattern: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/ // Patrón más estricto
        },
        rol: {
            allowedValues: ['administrador', 'visitante']
        }
    };

    // Variables para debouncing
    let emailTimeout;
    const DEBOUNCE_DELAY = 500; // 500ms de retraso

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
        return $('.is-invalid').length === 0;
    }

    /**
     * Actualiza el color del contador según la longitud
     * @param {number} length - Longitud actual
     * @param {number} max - Longitud máxima
     * @param {number} warningThreshold - Umbral de advertencia
     */
    function updateCounterColor(length, max, warningThreshold) {
        $nombreUsuarioContador.removeClass('text-muted text-success text-warning text-danger');

        if (length > max) {
            $nombreUsuarioContador.addClass('text-danger');
        } else if (length > warningThreshold) {
            $nombreUsuarioContador.addClass('text-warning');
        } else if (length > 0) {
            $nombreUsuarioContador.addClass('text-success');
        } else {
            $nombreUsuarioContador.addClass('text-muted');
        }
    }

    /**
     * Actualiza el color del helper de email según el estado de validación
     * @param {string} state - Estado: 'valid', 'invalid', 'neutral'
     */
    function updateEmailHelperColor(state) {
        $emailHelper.removeClass('text-muted text-success text-danger');

        switch (state) {
            case 'valid':
                $emailHelper.addClass('text-success');
                break;
            case 'invalid':
                $emailHelper.addClass('text-danger');
                break;
            default:
                $emailHelper.addClass('text-muted');
                break;
        }
    }

    /**
     * ===============================================
     * SECCIÓN 3: FUNCIONES DE VALIDACIÓN
     * ===============================================
     */

    /**
     * Valida el campo nombre de usuario
     * @returns {boolean} - true si es válido
     */
    function validarNombreUsuario() {
        const valor = $nombreUsuarioInput.val().trim();
        const longitud = valor.length;

        // Actualizar contador
        $nombreUsuarioContador.text(`${longitud}/${VALIDACION_CONFIG.nombreUsuario.max} caracteres`);
        updateCounterColor(longitud, VALIDACION_CONFIG.nombreUsuario.max, VALIDACION_CONFIG.nombreUsuario.warningThreshold);

        // Validaciones
        if (longitud === 0) {
            setFieldInvalid($nombreUsuarioInput, $nombreUsuarioFeedback, 'El nombre de usuario es obligatorio');
            return false;
        } else if (longitud < VALIDACION_CONFIG.nombreUsuario.min) {
            setFieldInvalid($nombreUsuarioInput, $nombreUsuarioFeedback, `El nombre debe tener al menos ${VALIDACION_CONFIG.nombreUsuario.min} caracteres`);
            return false;
        } else if (longitud > VALIDACION_CONFIG.nombreUsuario.max) {
            setFieldInvalid($nombreUsuarioInput, $nombreUsuarioFeedback, `El nombre no puede exceder los ${VALIDACION_CONFIG.nombreUsuario.max} caracteres`);
            return false;
        } else if (!VALIDACION_CONFIG.nombreUsuario.pattern.test(valor)) {
            setFieldInvalid($nombreUsuarioInput, $nombreUsuarioFeedback, VALIDACION_CONFIG.nombreUsuario.patternMessage);
            return false;
        } else {
            setFieldValid($nombreUsuarioInput, $nombreUsuarioFeedback);
            return true;
        }
    }

    /**
     * Valida el campo email
     * @returns {boolean} - true si es válido
     */
    function validarEmail() {
        const valor = $emailInput.val().trim();

        if (valor === '') {
            setFieldInvalid($emailInput, $emailFeedback, 'El email es obligatorio');
            updateEmailHelperColor('neutral');
            return false;
        } else if (!VALIDACION_CONFIG.email.pattern.test(valor)) {
            setFieldInvalid($emailInput, $emailFeedback, 'Por favor, introduce un email válido');
            updateEmailHelperColor('invalid');
            return false;
        } else if (valor.length > VALIDACION_CONFIG.email.max) {
            setFieldInvalid($emailInput, $emailFeedback, `El email no puede exceder los ${VALIDACION_CONFIG.email.max} caracteres`);
            updateEmailHelperColor('invalid');
            return false;
        } else if (!VALIDACION_CONFIG.email.advancedPattern.test(valor)) {
            setFieldInvalid($emailInput, $emailFeedback, 'El formato del email no es correcto');
            updateEmailHelperColor('invalid');
            return false;
        } else {
            setFieldValid($emailInput, $emailFeedback);
            updateEmailHelperColor('valid');
            return true;
        }
    }

    /**
     * Valida el campo rol (solo si existe)
     * @returns {boolean} - true si es válido o no existe
     */
    function validarRol() {
        if ($rolSelect.length === 0) {
            return true; // No hay campo de rol, es válido por defecto
        }

        const valor = $rolSelect.val();

        if (valor === '' || valor === null) {
            setFieldInvalid($rolSelect, $rolFeedback, 'Debe seleccionar un rol');
            return false;
        } else if (!VALIDACION_CONFIG.rol.allowedValues.includes(valor)) {
            setFieldInvalid($rolSelect, $rolFeedback, 'Rol no válido');
            return false;
        } else {
            setFieldValid($rolSelect, $rolFeedback);
            return true;
        }
    }

    /**
     * ===============================================
     * SECCIÓN 4: FUNCIONES DE FECHA
     * ===============================================
     */

    /**
     * Muestra la fecha y hora actual de modificación
     */
    function mostrarFechaModificacion() {
        try {
            const fechaActual = new Date();
            const opciones = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                timeZoneName: 'short'
            };

            const fechaFormateada = fechaActual.toLocaleDateString('es-ES', opciones);
            $fechaModificacion.html(`<i class="fas fa-check-circle me-2 text-success"></i>${fechaFormateada}`);
        } catch (error) {
            console.error('Error al formatear fecha:', error);
            // Fallback más simple
            const fechaSimple = new Date().toLocaleString('es-ES');
            $fechaModificacion.html(`<i class="fas fa-exclamation-circle me-2 text-warning"></i>${fechaSimple}`);
        }
    }

    /**
     * ===============================================
     * SECCIÓN 5: MANEJADORES DE EVENTOS
     * ===============================================
     */

    /**
     * Evento de validación en tiempo real para nombre de usuario
     */
    $nombreUsuarioInput.on('input', function() {
        validarNombreUsuario();
    });

    /**
     * Evento de validación inmediata para email (al perder el foco)
     */
    $emailInput.on('blur', function() {
        validarEmail();
    });

    /**
     * Evento de validación con debouncing para email (mientras escribe)
     */
    $emailInput.on('input', function() {
        clearTimeout(emailTimeout);
        emailTimeout = setTimeout(() => {
            validarEmail();
        }, DEBOUNCE_DELAY);
    });

    /**
     * Evento de validación para el campo rol (si existe)
     */
    if ($rolSelect.length > 0) {
        $rolSelect.on('change', function() {
            validarRol();
        });
    }

    /**
     * Evento de envío del formulario con validación completa
     */
    $form.on('submit', function(event) {
        // Ejecutar todas las validaciones
        const nombreValido = validarNombreUsuario();
        const emailValido = validarEmail();
        const rolValido = validarRol();

        // Si hay errores, prevenir envío
        if (!nombreValido || !emailValido || !rolValido) {
            event.preventDefault();

            // Mostrar mensaje de error
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    html: 'Por favor, corrija los errores antes de continuar.<br><small>Los campos con errores están marcados en rojo.</small>',
                    confirmButtonText: 'Entendido',
                    footer: '<i class="fas fa-lightbulb"></i> Revise los campos marcados para continuar'
                });
            } else {
                alert('Por favor, corrija los errores antes de continuar');
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
            // Todo válido, mostrar indicador de carga
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '<i class="fas fa-user-edit"></i> Actualizando usuario',
                    html: 'Procesando los cambios...<br><small>Por favor, no cierre esta ventana</small>',
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false
                });
            }
        }
    });

    /**
     * Evento del botón cancelar con confirmación
     */
    $btnCancelar.on('click', function() {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Estás seguro?',
                text: "Los cambios no guardados se perderán",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sign-out-alt me-1"></i>Sí, salir',
                cancelButtonText: '<i class="fas fa-edit me-1"></i>Continuar editando',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Controlador/ControladorUsuario.php';
                }
            });
        } else {
            if (confirm('¿Estás seguro? Los cambios no guardados se perderán')) {
                window.location.href = '../Controlador/ControladorUsuario.php';
            }
        }
    });

    /**
     * ===============================================
     * SECCIÓN 6: FUNCIONES DE INICIALIZACIÓN
     * ===============================================
     */

    /**
     * Inicializa todos los componentes del formulario
     */
    function inicializarFormulario() {
        try {
            // Mostrar fecha actual
            mostrarFechaModificacion();

            // Ejecutar validaciones iniciales para establecer contadores y estados
            validarNombreUsuario();
            validarEmail();
            if ($rolSelect.length > 0) {
                validarRol();
            }

            // Enfocar el primer campo editable
            $nombreUsuarioInput.focus();

            // Log para debugging (remover en producción)
            console.log('Formulario de actualización de usuario inicializado correctamente');
            console.log('Configuración de validación:', VALIDACION_CONFIG);
            console.log('Rol select presente:', $rolSelect.length > 0);

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
     * ===============================================
     * SECCIÓN 7: MANEJO DE ERRORES GLOBALES
     * ===============================================
     */

    /**
     * Manejador de errores globales no capturados
     */
    $(window).on('error', function(event) {
        const error = event.originalEvent.error;
        console.error('Error no manejado en ActualizarUsuario.js:', error);

        // En desarrollo, mostrar más detalles
        if (window.location.hostname === 'localhost') {
            console.error('Stack trace:', error.stack);
            console.error('Información del evento:', event.originalEvent);
        }

        // En producción, enviar error a servicio de logging
        // enviarErrorAServidor(error);
    });

    /**
     * ===============================================
     * SECCIÓN 8: FUNCIONES AUXILIARES AVANZADAS
     * ===============================================
     */

    /**
     * Detecta si hay cambios en el formulario comparando valores iniciales
     * @returns {boolean} - true si hay cambios pendientes
     */
    function detectarCambios() {
        const valoresIniciales = {
            nombreUsuario: $nombreUsuarioInput.data('initial-value') || '',
            email: $emailInput.data('initial-value') || '',
            rol: $rolSelect.length > 0 ? ($rolSelect.data('initial-value') || '') : ''
        };

        const valoresActuales = {
            nombreUsuario: $nombreUsuarioInput.val().trim(),
            email: $emailInput.val().trim(),
            rol: $rolSelect.length > 0 ? $rolSelect.val() : ''
        };

        return Object.keys(valoresIniciales).some(key =>
            valoresIniciales[key] !== valoresActuales[key]
        );
    }

    /**
     * Guarda los valores iniciales para detectar cambios
     */
    function guardarValoresIniciales() {
        $nombreUsuarioInput.data('initial-value', $nombreUsuarioInput.val().trim());
        $emailInput.data('initial-value', $emailInput.val().trim());
        if ($rolSelect.length > 0) {
            $rolSelect.data('initial-value', $rolSelect.val());
        }
    }

    /**
     * Aplica estilos adicionales para mejorar la experiencia de usuario
     */
    function aplicarMejorasVisuales() {
        // Efecto de focus mejorado para inputs
        $('input[type="text"], input[type="email"], select').on('focus', function() {
            $(this).closest('.input-group').addClass('shadow-sm');
        }).on('blur', function() {
            $(this).closest('.input-group').removeClass('shadow-sm');
        });

        // Animación suave para mensajes de error
        $('.invalid-feedback').hide().css('opacity', 0);

        // Mejorar accesibilidad con tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    }

    /**
     * ===============================================
     * SECCIÓN 9: EJECUCIÓN DE INICIALIZACIÓN
     * ===============================================
     */

    // Ejecutar inicialización cuando el DOM esté completamente listo
    inicializarFormulario();

    // Guardar valores iniciales para detectar cambios
    guardarValoresIniciales();

    // Aplicar mejoras visuales
    aplicarMejorasVisuales();

    /**
     * ===============================================
     * SECCIÓN 10: API PÚBLICA PARA TESTING (OPCIONAL)
     * ===============================================
     */

    // Exponer algunas funciones al scope global para testing
    window.UsuarioFormValidator = {
        validarNombreUsuario: validarNombreUsuario,
        validarEmail: validarEmail,
        validarRol: validarRol,
        isFormValid: isFormValid,
        detectarCambios: detectarCambios,
        mostrarFechaModificacion: mostrarFechaModificacion,
        config: VALIDACION_CONFIG
    };

});