/**
 * Archivo: iniciarSesion.js
 * Descripción: Maneja la funcionalidad del formulario de inicio de sesión
 *
 * Funcionalidades principales:
 * - Validación de campos obligatorios (usuario y contraseña)
 * - Limpieza automática de errores al escribir
 * - Focus automático en el primer campo
 * - Auto-ocultamiento de mensajes de éxito
 * - Indicador de carga durante el envío
 * - Prevención de envío con campos vacíos
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
    const $form = $('#form-login');
    const $usernameInput = $('#username');
    const $passwordInput = $('#password');
    const $usernameFeedback = $('#username-feedback');
    const $passwordFeedback = $('#password-feedback');
    const $btnLogin = $('#btn-login');
    const $alertSuccess = $('.alert-success');

    // Configuración de timeouts
    const SUCCESS_MESSAGE_TIMEOUT = 5000; // 5 segundos

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
    function setInvalid($input, $feedback, message) {
        $input.addClass('is-invalid').removeClass('is-valid');
        $feedback.text(message);
    }

    /**
     * Marca un campo como válido y limpia errores
     * @param {jQuery} $input - Campo a marcar como válido
     * @param {jQuery} $feedback - Elemento de error a limpiar
     */
    function setValid($input, $feedback) {
        $input.removeClass('is-invalid').addClass('is-valid');
        $feedback.text('');
    }

    /**
     * Limpia la validación de un campo (quita clases válido/inválido)
     * @param {jQuery} $input - Campo a limpiar
     * @param {jQuery} $feedback - Elemento de feedback a limpiar
     */
    function clearValidation($input, $feedback) {
        $input.removeClass('is-valid is-invalid');
        $feedback.text('');
    }

    /**
     * Verifica si el formulario es válido
     * @returns {boolean} - true si todos los campos requeridos están completos
     */
    function isFormValid() {
        const username = $usernameInput.val().trim();
        const password = $passwordInput.val().trim();

        return username !== '' && password !== '';
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
    function validarUsername() {
        const username = $usernameInput.val().trim();

        if (username === '') {
            setInvalid($usernameInput, $usernameFeedback, 'El nombre de usuario es obligatorio');
            return false;
        } else {
            setValid($usernameInput, $usernameFeedback);
            return true;
        }
    }

    /**
     * Valida el campo contraseña
     * @returns {boolean} - true si es válido
     */
    function validarPassword() {
        const password = $passwordInput.val().trim();

        if (password === '') {
            setInvalid($passwordInput, $passwordFeedback, 'La contraseña es obligatoria');
            return false;
        } else {
            setValid($passwordInput, $passwordFeedback);
            return true;
        }
    }

    /**
     * ===============================================
     * SECCIÓN 4: MANEJADORES DE EVENTOS
     * ===============================================
     */

    /**
     * Evento de envío del formulario con validación
     */
    $form.on('submit', function(event) {
        // Ejecutar validaciones
        const usernameValido = validarUsername();
        const passwordValido = validarPassword();
        const esValido = usernameValido && passwordValido;

        // Si hay errores, prevenir envío
        if (!esValido) {
            event.preventDefault();

            // Enfocar primer campo con error
            const $firstError = $('.is-invalid').first();
            if ($firstError.length) {
                $firstError.focus();
            }

            // Mostrar alerta si SweetAlert2 está disponible
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos requeridos',
                    text: 'Por favor, complete todos los campos',
                    confirmButtonText: 'Entendido',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    }
                });
            }
        } else {
            // Formulario válido, mostrar indicador de carga
            if (typeof Swal !== 'undefined') {
                // Deshabilitar botón y cambiar texto
                $btnLogin.prop('disabled', true).text('Entrando...');

                // Mostrar indicador de carga
                Swal.fire({
                    title: 'Iniciando sesión...',
                    html: 'Verificando credenciales...',
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false
                });
            } else {
                // Fallback sin SweetAlert2
                $btnLogin.prop('disabled', true).text('Entrando...');
            }
        }
    });

    /**
     * Eventos para limpiar validación cuando el usuario comience a escribir
     */
    $usernameInput.on('input', function() {
        if ($(this).hasClass('is-invalid') && $(this).val().trim() !== '') {
            clearValidation($(this), $usernameFeedback);
        }
    });

    $passwordInput.on('input', function() {
        if ($(this).hasClass('is-invalid') && $(this).val().trim() !== '') {
            clearValidation($(this), $passwordFeedback);
        }
    });

    /**
     * Eventos adicionales para mejorar la experiencia
     */

    // Envío con Enter desde cualquier campo
    $usernameInput.add($passwordInput).on('keypress', function(event) {
        if (event.which === 13) { // Enter key
            event.preventDefault();
            $form.submit();
        }
    });

    // Limpiar espacios al perder el foco
    $usernameInput.on('blur', function() {
        $(this).val($(this).val().trim());
    });

    /**
     * ===============================================
     * SECCIÓN 5: FUNCIONES DE INICIALIZACIÓN
     * ===============================================
     */

    /**
     * Inicializa el formulario de login
     */
    function inicializarFormulario() {
        try {
            // Focus inicial en el primer campo
            $usernameInput.focus();

            // Auto-ocultar mensaje de éxito después del timeout
            if ($alertSuccess.length > 0) {
                setTimeout(function() {
                    $alertSuccess.fadeOut('slow');
                }, SUCCESS_MESSAGE_TIMEOUT);
            }

            // Log para debugging (remover en producción)
            console.log('Formulario de login inicializado correctamente');

        } catch (error) {
            console.error('Error al inicializar el formulario de login:', error);
        }
    }

    /**
     * ===============================================
     * SECCIÓN 6: FUNCIONES AUXILIARES
     * ===============================================
     */

    /**
     * Restaura el botón de login a su estado original
     */
    function restaurarBotonLogin() {
        $btnLogin.prop('disabled', false).text('Entrar');
    }

    /**
     * Maneja errores de conexión o servidor
     */
    function manejarErrorConexion() {
        restaurarBotonLogin();

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor. Inténtelo de nuevo.',
                confirmButtonText: 'Reintentar',
                customClass: {
                    confirmButton: 'btn btn-primary'
                }
            });
        } else {
            alert('Error de conexión. Por favor, inténtelo de nuevo.');
        }
    }



    /**
     * ===============================================
     * SECCIÓN 8: EJECUCIÓN DE INICIALIZACIÓN
     * ===============================================
     */

    // Ejecutar inicialización cuando el DOM esté completamente listo
    inicializarFormulario();



});