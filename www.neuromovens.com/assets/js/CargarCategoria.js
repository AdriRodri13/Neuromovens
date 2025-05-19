/**
 * Archivo: CargarCategoria.js
 * Descripción: Maneja la funcionalidad del formulario de actualización de categorías
 * Funcionalidades:
 * - Validación en tiempo real del nombre de la categoría
 * - Contador de caracteres dinámico
 * - Validación al enviar el formulario
 * - Confirmación antes de cancelar (con pérdida de datos)
 * - Mostrar fecha de modificación actual
 *
 * Dependencias:
 * - jQuery (requerido)
 * - SweetAlert2 (opcional, para mejores notificaciones)
 * - Bootstrap (para clases CSS)
 */

$(document).ready(function() {
    /**
     * ===============================================
     * SECCIÓN 1: DECLARACIÓN DE VARIABLES Y SELECTORES
     * ===============================================
     */

        // Selectores jQuery para elementos del formulario
    const $form = $('#form-actualizar-categoria');
    const $nombreInput = $('#nombre');
    const $nombreFeedback = $('#nombre-feedback');
    const $nombreContador = $('#nombre-contador');
    const $btnCancelar = $('#btn-cancelar');
    const $fechaModificacion = $('#fecha-modificacion');

    // Constantes para validación
    const NOMBRE_MIN_LENGTH = 3;
    const NOMBRE_MAX_LENGTH = 50;

    /**
     * ===============================================
     * SECCIÓN 2: FUNCIONES UTILITARIAS
     * ===============================================
     */

    /**
     * Muestra la fecha y hora actual en formato español
     * Actualiza el elemento de fecha de modificación en el DOM
     */
    function mostrarFechaActual() {
        const fechaActual = new Date();

        // Configuración para formatear la fecha en español
        const opciones = {
            weekday: 'long',    // Día de la semana completo (ej: lunes)
            year: 'numeric',    // Año en 4 dígitos
            month: 'long',      // Mes completo (ej: enero)
            day: 'numeric',     // Día del mes
            hour: '2-digit',    // Hora en formato 24h
            minute: '2-digit'   // Minutos
        };

        // Formatear fecha y mostrarla en el elemento correspondiente
        const fechaFormateada = fechaActual.toLocaleDateString('es-ES', opciones);
        $fechaModificacion.text(fechaFormateada);
    }

    /**
     * Marca un campo como inválido y muestra mensaje de error
     * @param {jQuery} $input - Elemento input a marcar como inválido
     * @param {jQuery} $feedback - Elemento para mostrar el mensaje de error
     * @param {string} message - Mensaje de error a mostrar
     */
    function setInvalid($input, $feedback, message) {
        $input.addClass('is-invalid').removeClass('is-valid');
        $feedback.text(message);
    }

    /**
     * Marca un campo como válido y limpia mensajes de error
     * @param {jQuery} $input - Elemento input a marcar como válido
     * @param {jQuery} $feedback - Elemento de feedback a limpiar
     */
    function setValid($input, $feedback) {
        $input.removeClass('is-invalid').addClass('is-valid');
        $feedback.text('');
    }

    /**
     * Verifica si el formulario completo es válido
     * @returns {boolean} - true si no hay elementos con clase 'is-invalid'
     */
    function isFormValid() {
        return $('.is-invalid').length === 0;
    }

    /**
     * Actualiza el contador de caracteres y sus estilos visuales
     * @param {number} longitud - Número actual de caracteres
     */
    function actualizarContadorCaracteres(longitud) {
        // Actualizar texto del contador
        $nombreContador.text(`${longitud}/${NOMBRE_MAX_LENGTH} caracteres`);

        // Remover todas las clases de color previas
        $nombreContador.removeClass('text-muted text-success text-danger');

        // Aplicar color según la longitud
        if (longitud > 40) {
            // Rojo: cerca del límite (peligro)
            $nombreContador.addClass('text-danger');
        } else if (longitud > 0) {
            // Verde: longitud aceptable
            $nombreContador.addClass('text-success');
        } else {
            // Gris: campo vacío
            $nombreContador.addClass('text-muted');
        }
    }

    /**
     * ===============================================
     * SECCIÓN 3: FUNCIONES DE VALIDACIÓN
     * ===============================================
     */

    /**
     * Valida el campo nombre de categoría
     * Aplica todas las reglas de negocio para el nombre
     */
    function validarNombreCategoria() {
        const valor = $nombreInput.val().trim();
        const longitud = valor.length;

        // Actualizar contador de caracteres
        actualizarContadorCaracteres(longitud);

        // Validar según las reglas de negocio
        if (longitud === 0) {
            setInvalid($nombreInput, $nombreFeedback, 'El nombre de la categoría es obligatorio');
        } else if (longitud < NOMBRE_MIN_LENGTH) {
            setInvalid($nombreInput, $nombreFeedback, `El nombre debe tener al menos ${NOMBRE_MIN_LENGTH} caracteres`);
        } else if (longitud > NOMBRE_MAX_LENGTH) {
            setInvalid($nombreInput, $nombreFeedback, `El nombre no puede exceder los ${NOMBRE_MAX_LENGTH} caracteres`);
        } else {
            setValid($nombreInput, $nombreFeedback);
        }
    }

    /**
     * ===============================================
     * SECCIÓN 4: MANEJADORES DE EVENTOS
     * ===============================================
     */

    /**
     * Manejador para validación en tiempo real del nombre
     * Se ejecuta cada vez que el usuario escribe en el campo
     */
    $nombreInput.on('input', function() {
        validarNombreCategoria();
    });

    /**
     * Manejador para el envío del formulario
     * Valida todos los campos antes de enviar
     */
    $form.on('submit', function(event) {
        // Ejecutar todas las validaciones
        validarNombreCategoria();

        // Si hay errores, prevenir el envío
        if (!isFormValid()) {
            event.preventDefault();

            // Mostrar mensaje de error apropiado
            if (typeof Swal !== 'undefined') {
                // Usar SweetAlert2 si está disponible (más elegante)
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: 'Por favor, corrija los errores antes de continuar',
                    confirmButtonText: 'Entendido'
                });
            } else {
                // Fallback a alert nativo
                alert('Por favor, corrija los errores antes de continuar');
            }
        } else if (typeof Swal !== 'undefined') {
            // Si todo es válido, mostrar indicador de carga
            Swal.fire({
                title: 'Guardando cambios',
                text: 'Procesando su solicitud...',
                didOpen: () => {
                    Swal.showLoading();
                },
                allowOutsideClick: false,  // No permitir cerrar haciendo clic afuera
                allowEscapeKey: false      // No permitir cerrar con ESC
            });
        }
    });

    /**
     * Manejador para el botón cancelar
     * Muestra confirmación antes de salir para evitar pérdida de datos
     */
    $btnCancelar.on('click', function() {
        if (typeof Swal !== 'undefined') {
            // Usar SweetAlert2 para confirmación elegante
            Swal.fire({
                title: '¿Está seguro?',
                text: "Los cambios no guardados se perderán",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, salir',
                cancelButtonText: 'No, continuar editando'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Redirigir al controlador principal
                    window.location.href = '../Controlador/ControladorCategoria.php';
                }
            });
        } else {
            // Fallback a confirm nativo
            if (confirm('¿Está seguro? Los cambios no guardados se perderán')) {
                window.location.href = '../Controlador/ControladorCategoria.php';
            }
        }
    });

    /**
     * ===============================================
     * SECCIÓN 5: INICIALIZACIÓN
     * ===============================================
     */

    /**
     * Función de inicialización que se ejecuta al cargar la página
     * Configura el estado inicial de todos los elementos
     */
    function inicializar() {
        // Mostrar la fecha actual de modificación
        mostrarFechaActual();

        // Ejecutar validación inicial para establecer el contador y estado
        validarNombreCategoria();

        // Log para debugging (se puede remover en producción)
        console.log('Formulario de actualización de categoría inicializado correctamente');
    }

    /**
     * Ejecutar inicialización cuando el DOM esté listo
     * jQuery garantiza que el DOM está completamente cargado
     */
    inicializar();

    /**
     * ===============================================
     * SECCIÓN 6: MANEJO DE ERRORES GLOBALES (OPCIONAL)
     * ===============================================
     */

    /**
     * Manejador opcional para errores no capturados
     * Útil para debugging en desarrollo
     */
    $(window).on('error', function(event) {
        console.error('Error en CargarCategoria.js:', event.originalEvent.error);

        // En producción, esto podría enviar el error a un servicio de logging
        // Por ejemplo: enviarErrorAServidor(event.originalEvent.error);
    });

});