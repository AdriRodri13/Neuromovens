/**
 * Archivo: InsertarPostInvestigacion.js
 * Descripción: Maneja la funcionalidad del formulario de inserción de posts de investigación
 *
 * Funcionalidades principales:
 * - Validación completa de título, contenido e imagen
 * - Contador de caracteres dinámico para título y descripción
 * - Cálculo de tiempo de lectura estimado
 * - Vista previa de imagen seleccionada
 * - Confirmación antes de cancelar
 * - Mensajes de error y éxito
 * - Indicador de carga durante el envío
 *
 * Dependencias:
 * - jQuery (requerido)
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
    const $form = $('#form-insertar-post');
    const $tituloInput = $('#titulo');
    const $descripcionInput = $('#descripcion');
    const $imagenInput = $('#imagen_url');
    const $imagenPreview = $('#imagen-preview');
    const $previewImg = $('#preview-img');
    const $btnCancelar = $('#btn-cancelar');
    const $btnSubmit = $('button[type="submit"]');
    const $contadorTitulo = $('#contador-titulo');
    const $contadorDescripcion = $('#contador-descripcion');
    const $tiempoLectura = $('#tiempo-lectura');
    const $mensajeRespuesta = $('#mensaje-respuesta');
    const $tituloFeedback = $('#titulo-feedback');
    const $descripcionFeedback = $('#descripcion-feedback');
    const $imagenFeedback = $('#imagen-feedback');

    // Configuración de validaciones
    const VALIDACION_CONFIG = {
        titulo: {
            min: 5,
            max: 100,
            warningThreshold: 80
        },
        descripcion: {
            min: 20,
            max: 1000,
            warningThreshold: 800,
            wordsPerMinute: 200 // Para cálculo de tiempo de lectura
        },
        imagen: {
            maxSize: 5 * 1024 * 1024, // 5MB
            allowedTypes: ['image/jpeg', 'image/png', 'image/jpg'],
            allowedMimeTypes: ['image/jpeg', 'image/png']
        }
    };

    /**
     * ===============================================
     * SECCIÓN 2: FUNCIONES UTILITARIAS
     * ===============================================
     */

    /**
     * Muestra error en un campo
     * @param {jQuery} $campo - Campo a marcar como inválido
     * @param {jQuery} $feedback - Elemento para mostrar el error
     * @param {string} mensaje - Mensaje de error
     */
    function mostrarError($campo, $feedback, mensaje) {
        $campo.addClass('is-invalid');
        $feedback.text(mensaje).show();
    }

    /**
     * Quita error de un campo
     * @param {jQuery} $campo - Campo a limpiar
     * @param {jQuery} $feedback - Elemento de feedback a limpiar
     */
    function quitarError($campo, $feedback) {
        $campo.removeClass('is-invalid');
        $feedback.text('').hide();
    }

    /**
     * Muestra mensaje general en el formulario
     * @param {string} tipo - Tipo de mensaje (success, danger, info, warning)
     * @param {string} mensaje - Texto del mensaje
     */
    function mostrarMensaje(tipo, mensaje) {
        $mensajeRespuesta.removeClass().addClass('alert alert-' + tipo);
        $mensajeRespuesta.text(mensaje).show();

        // Scroll suave al mensaje
        $('html, body').animate({
            scrollTop: $mensajeRespuesta.offset().top - 100
        }, 500);
    }

    /**
     * Actualiza el color del contador según la longitud
     * @param {jQuery} $counter - Elemento contador
     * @param {number} length - Longitud actual
     * @param {number} warningThreshold - Umbral de advertencia
     */
    function updateCounterColor($counter, length, warningThreshold) {
        $counter.removeClass('text-warning text-success text-muted').addClass('form-text');

        if (length > warningThreshold) {
            $counter.addClass('text-warning');
        } else if (length > 0) {
            $counter.addClass('text-success');
        } else {
            $counter.addClass('text-muted');
        }
    }

    /**
     * Calcula el tiempo de lectura estimado
     * @param {string} texto - Texto a analizar
     * @returns {number} - Tiempo en minutos
     */
    function calcularTiempoLectura(texto) {
        const palabras = texto.split(/\s+/).filter(Boolean).length;
        return Math.max(1, Math.ceil(palabras / VALIDACION_CONFIG.descripcion.wordsPerMinute));
    }

    /**
     * ===============================================
     * SECCIÓN 3: FUNCIONES DE VALIDACIÓN
     * ===============================================
     */

    /**
     * Valida el campo título
     * @returns {boolean} - true si es válido
     */
    function validarTitulo() {
        const titulo = $tituloInput.val().trim();
        const longitud = titulo.length;

        if (titulo === '') {
            mostrarError($tituloInput, $tituloFeedback, 'El título es obligatorio');
            return false;
        } else if (longitud < VALIDACION_CONFIG.titulo.min) {
            mostrarError($tituloInput, $tituloFeedback, `El título debe tener al menos ${VALIDACION_CONFIG.titulo.min} caracteres`);
            return false;
        } else if (longitud > VALIDACION_CONFIG.titulo.max) {
            mostrarError($tituloInput, $tituloFeedback, `El título no puede exceder los ${VALIDACION_CONFIG.titulo.max} caracteres`);
            return false;
        } else {
            quitarError($tituloInput, $tituloFeedback);
            return true;
        }
    }

    /**
     * Valida el campo descripción
     * @returns {boolean} - true si es válido
     */
    function validarDescripcion() {
        const descripcion = $descripcionInput.val().trim();
        const longitud = descripcion.length;

        if (descripcion === '') {
            mostrarError($descripcionInput, $descripcionFeedback, 'El contenido es obligatorio');
            return false;
        } else if (longitud < VALIDACION_CONFIG.descripcion.min) {
            mostrarError($descripcionInput, $descripcionFeedback, `El contenido debe tener al menos ${VALIDACION_CONFIG.descripcion.min} caracteres`);
            return false;
        } else if (longitud > VALIDACION_CONFIG.descripcion.max) {
            mostrarError($descripcionInput, $descripcionFeedback, `El contenido no puede exceder los ${VALIDACION_CONFIG.descripcion.max} caracteres`);
            return false;
        } else {
            quitarError($descripcionInput, $descripcionFeedback);
            return true;
        }
    }

    /**
     * Valida el archivo de imagen
     * @returns {boolean} - true si es válido
     */
    function validarImagen() {
        const archivo = $imagenInput[0].files[0];

        if (!archivo) {
            mostrarError($imagenInput, $imagenFeedback, 'La imagen es obligatoria');
            return false;
        }

        // Validar tipo de archivo
        if (!VALIDACION_CONFIG.imagen.allowedMimeTypes.includes(archivo.type)) {
            mostrarError($imagenInput, $imagenFeedback, 'Solo se permiten imágenes en formato JPG o PNG');
            return false;
        }

        // Validar tamaño
        if (archivo.size > VALIDACION_CONFIG.imagen.maxSize) {
            const maxSizeMB = VALIDACION_CONFIG.imagen.maxSize / (1024 * 1024);
            mostrarError($imagenInput, $imagenFeedback, `La imagen no puede superar los ${maxSizeMB}MB`);
            return false;
        }

        quitarError($imagenInput, $imagenFeedback);
        return true;
    }

    /**
     * ===============================================
     * SECCIÓN 4: MANEJADORES DE EVENTOS
     * ===============================================
     */

    /**
     * Contador de caracteres para el título
     */
    $tituloInput.on('input', function() {
        const longitud = $(this).val().length;
        $contadorTitulo.text(`${longitud}/${VALIDACION_CONFIG.titulo.max} caracteres`);
        updateCounterColor($contadorTitulo, longitud, VALIDACION_CONFIG.titulo.warningThreshold);
    });

    /**
     * Contador de caracteres y tiempo de lectura para la descripción
     */
    $descripcionInput.on('input', function() {
        const texto = $(this).val();
        const longitud = texto.length;

        // Actualizar contador de caracteres
        $contadorDescripcion.text(`${longitud}/${VALIDACION_CONFIG.descripcion.max} caracteres`);
        updateCounterColor($contadorDescripcion, longitud, VALIDACION_CONFIG.descripcion.warningThreshold);

        // Calcular y mostrar tiempo de lectura
        const minutos = calcularTiempoLectura(texto);
        $tiempoLectura.text(`Tiempo de lectura: ${minutos} min`);
    });

    /**
     * Vista previa de imagen
     */
    $imagenInput.on('change', function() {
        const archivo = this.files[0];

        if (archivo) {
            // Verificar que es una imagen válida antes de mostrar vista previa
            if (VALIDACION_CONFIG.imagen.allowedMimeTypes.includes(archivo.type)) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    $previewImg.attr('src', e.target.result);
                    $imagenPreview.show();
                };

                reader.onerror = function() {
                    mostrarError($imagenInput, $imagenFeedback, 'Error al leer el archivo de imagen');
                    $imagenPreview.hide();
                };

                reader.readAsDataURL(archivo);
            } else {
                $imagenPreview.hide();
            }
        } else {
            $imagenPreview.hide();
        }
    });

    /**
     * Botón cancelar con confirmación
     */
    $btnCancelar.on('click', function() {
        if (confirm('¿Estás seguro de cancelar? Los datos no se guardarán.')) {
            window.location.href = '../Controlador/ControladorPostInvestigacion.php';
        }
    });

    /**
     * Validación y envío del formulario
     */
    $form.on('submit', function(e) {
        // Ejecutar todas las validaciones
        const tituloValido = validarTitulo();
        const descripcionValida = validarDescripcion();
        const imagenValida = validarImagen();

        const esValido = tituloValido && descripcionValida && imagenValida;

        // Si hay errores, detener envío
        if (!esValido) {
            e.preventDefault();
            mostrarMensaje('danger', 'Por favor, corrige los errores antes de continuar.');

            // Enfocar el primer campo con error
            const $firstError = $('.is-invalid').first();
            if ($firstError.length) {
                $firstError.focus();
            }

            return false;
        }

        // Formulario válido, mostrar indicador de carga
        mostrarMensaje('info', 'Enviando información... Por favor espere.');

        // Deshabilitar botón y mostrar spinner
        $btnSubmit.prop('disabled', true)
            .html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Publicando...');

        // Permitir que el formulario se envíe normalmente
        return true;
    });

    /**
     * ===============================================
     * SECCIÓN 5: FUNCIONES DE INICIALIZACIÓN
     * ===============================================
     */

    /**
     * Inicializa el formulario y sus componentes
     */
    function inicializarFormulario() {
        try {
            // Enfocar el primer campo
            $tituloInput.focus();

            // Inicializar contadores
            $tituloInput.trigger('input');
            $descripcionInput.trigger('input');

            // Log para debugging (remover en producción)
            console.log('Formulario de inserción de post inicializado correctamente');
            console.log('Configuración de validación:', VALIDACION_CONFIG);

        } catch (error) {
            console.error('Error al inicializar el formulario:', error);
        }
    }

    /**
     * ===============================================
     * SECCIÓN 6: FUNCIONES AUXILIARES
     * ===============================================
     */

    /**
     * Limpia completamente el formulario
     */
    function limpiarFormulario() {
        $form[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('').hide();
        $imagenPreview.hide();
        $mensajeRespuesta.hide();
        $btnSubmit.prop('disabled', false).html('<i class="fas fa-save"></i> Publicar investigación');

        // Reinicializar contadores
        $contadorTitulo.text('0/100 caracteres').removeClass('text-warning text-success').addClass('text-muted');
        $contadorDescripcion.text('0/1000 caracteres').removeClass('text-warning text-success').addClass('text-muted');
        $tiempoLectura.text('Tiempo de lectura: 0 min');

        $tituloInput.focus();
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
        console.error('Error no manejado en InsertarPostInvestigacion.js:', error);

        // En desarrollo, mostrar más detalles
        if (window.location.hostname === 'localhost') {
            console.error('Stack trace:', error.stack);
        }

        // En producción, enviar error a servicio de logging
        // enviarErrorAServidor(error);
    });

    /**
     * ===============================================
     * SECCIÓN 8: EJECUCIÓN DE INICIALIZACIÓN
     * ===============================================
     */

    // Ejecutar inicialización cuando el DOM esté completamente listo
    inicializarFormulario();

    /**
     * ===============================================
     * SECCIÓN 9: API PÚBLICA PARA TESTING (OPCIONAL)
     * ===============================================
     */

    // Exponer algunas funciones al scope global para testing
    window.InsertarPostValidator = {
        validarTitulo: validarTitulo,
        validarDescripcion: validarDescripcion,
        validarImagen: validarImagen,
        calcularTiempoLectura: calcularTiempoLectura,
        limpiarFormulario: limpiarFormulario,
        config: VALIDACION_CONFIG
    };

});