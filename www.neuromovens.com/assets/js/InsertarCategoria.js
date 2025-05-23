/**
 * Archivo: InsertarCategoria.js
 * Descripción: Maneja la funcionalidad del formulario de inserción de categorías
 *
 * Funcionalidades principales:
 * - Validación del campo nombre de categoría (obligatorio)
 * - Limpieza automática de errores al escribir
 * - Focus automático en el campo al cargar
 * - Indicador de carga durante el envío
 * - Prevención de envío con campos vacíos
 *
 * Dependencias:
 * - jQuery (requerido)
 * - SweetAlert2 (opcional, para mejores notificaciones)
 * - Bootstrap (para clases CSS y validación visual)
 * - FontAwesome (para iconos - opcional)
 */

$(document).ready(function() {
    /**
     * ===============================================
     * SECCIÓN 1: CONFIGURACIÓN Y VARIABLES GLOBALES
     * ===============================================
     */

        // Selectores jQuery cacheados para mejor rendimiento
    const $form = $('#form-categoria');
    const $nombreInput = $('#nombre_categoria');
    const $feedback = $('#categoria-feedback');
    const $btnInsertar = $('#btn-insertar');

    // Configuración de validaciones
    const VALIDACION_CONFIG = {
        nombre: {
            minLength: 1,
            maxLength: 255,
            pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ0-9\s\-_]+$/, // Letras, números, espacios, guiones y guiones bajos
            patternMessage: 'Solo se permiten letras, números, espacios, guiones y guiones bajos'
        }
    };

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
     * @returns {boolean} - true si el campo nombre es válido
     */
    function isFormValid() {
        const nombre = $nombreInput.val().trim();
        return nombre !== '' && nombre.length >= VALIDACION_CONFIG.nombre.minLength;
    }

    /**
     * ===============================================
     * SECCIÓN 3: FUNCIONES DE VALIDACIÓN
     * ===============================================
     */

    /**
     * Valida el campo nombre de categoría
     * @returns {boolean} - true si es válido
     */
    function validarNombreCategoria() {
        const nombre = $nombreInput.val().trim();

        if (nombre === '') {
            setInvalid($nombreInput, $feedback, 'El nombre de la categoría es obligatorio');
            return false;
        } else if (nombre.length < VALIDACION_CONFIG.nombre.minLength) {
            setInvalid($nombreInput, $feedback, `El nombre debe tener al menos ${VALIDACION_CONFIG.nombre.minLength} carácter`);
            return false;
        } else if (nombre.length > VALIDACION_CONFIG.nombre.maxLength) {
            setInvalid($nombreInput, $feedback, `El nombre no puede exceder los ${VALIDACION_CONFIG.nombre.maxLength} caracteres`);
            return false;
        } else if (!VALIDACION_CONFIG.nombre.pattern.test(nombre)) {
            setInvalid($nombreInput, $feedback, VALIDACION_CONFIG.nombre.patternMessage);
            return false;
        } else {
            setValid($nombreInput, $feedback);
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
        const esValido = validarNombreCategoria();

        // Si hay errores, prevenir envío
        if (!esValido) {
            event.preventDefault();

            // Enfocar el campo con error
            $nombreInput.focus();

            // Mostrar alerta si SweetAlert2 está disponible
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campo requerido',
                    text: 'Por favor, ingrese un nombre válido para la categoría',
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
                $btnInsertar.prop('disabled', true).val('Insertando...');

                // Mostrar indicador de carga
                Swal.fire({
                    title: 'Insertando categoría...',
                    html: 'Procesando solicitud...',
                    didOpen: () => {
                        Swal.showLoading();
                    },
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false
                });
            } else {
                // Fallback sin SweetAlert2
                $btnInsertar.prop('disabled', true).val('Insertando...');
            }
        }
    });

    /**
     * Evento para limpiar validación cuando el usuario comience a escribir
     */
    $nombreInput.on('input', function() {
        if ($(this).hasClass('is-invalid') && $(this).val().trim() !== '') {
            clearValidation($(this), $feedback);
        }
    });

    /**
     * Validación en tiempo real (opcional)
     */
    $nombreInput.on('blur', function() {
        const nombre = $(this).val().trim();
        if (nombre !== '') {
            validarNombreCategoria();
        }
    });

    /**
     * Eventos adicionales para mejorar la experiencia
     */

    // Envío con Enter
    $nombreInput.on('keypress', function(event) {
        if (event.which === 13) { // Enter key
            event.preventDefault();
            $form.submit();
        }
    });

    // Limpiar espacios al perder el foco
    $nombreInput.on('blur', function() {
        $(this).val($(this).val().trim());
    });

    /**
     * ===============================================
     * SECCIÓN 5: FUNCIONES DE INICIALIZACIÓN
     * ===============================================
     */

    /**
     * Inicializa el formulario de inserción de categoría
     */
    function inicializarFormulario() {
        try {
            // Focus inicial en el campo
            $nombreInput.focus();

            // Log para debugging (remover en producción)
            console.log('Formulario de inserción de categoría inicializado correctamente');
            console.log('Configuración de validación:', VALIDACION_CONFIG);

        } catch (error) {
            console.error('Error al inicializar el formulario de inserción de categoría:', error);
        }
    }

    /**
     * ===============================================
     * SECCIÓN 6: FUNCIONES AUXILIARES
     * ===============================================
     */

    /**
     * Restaura el botón de inserción a su estado original
     */
    function restaurarBotonInsertar() {
        $btnInsertar.prop('disabled', false).val('Insertar Categoría');
    }

    /**
     * Limpia completamente el formulario
     */
    function limpiarFormulario() {
        $form[0].reset();
        clearValidation($nombreInput, $feedback);
        $nombreInput.focus();
    }

    /**
     * Maneja errores de conexión o servidor
     */
    function manejarErrorConexion() {
        restaurarBotonInsertar();

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo insertar la categoría. Inténtelo de nuevo.',
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