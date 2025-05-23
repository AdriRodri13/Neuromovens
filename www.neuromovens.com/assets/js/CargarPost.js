/**
 * Archivo: CargarPost.js
 * Descripción: Gestiona la funcionalidad del formulario de actualización de posts de investigación
 *
 * Funcionalidades principales:
 * - Validación en tiempo real de título y contenido
 * - Contador de caracteres dinámico
 * - Cálculo automático del tiempo de lectura
 * - Vista previa de imágenes con validación
 * - Gestión de errores y validaciones del formulario
 * - Confirmación antes de cancelar cambios
 *
 * Dependencias:
 * - jQuery (requerido)
 * - SweetAlert2 (opcional, para notificaciones mejoradas)
 * - Bootstrap (para clases CSS)
 * - FontAwesome (para iconos)
 */

$(document).ready(function() {
    /**
     * ===============================================
     * SECCIÓN 1: VARIABLES Y CONFIGURACIÓN INICIAL
     * ===============================================
     */

        // Selectores jQuery para elementos del formulario (cacheados para mejor rendimiento)
    const $form = $('#form-actualizar-post');
    const $tituloInput = $('#titulo');
    const $tituloFeedback = $('#titulo-feedback');
    const $tituloContador = $('#titulo-contador');
    const $descripcionInput = $('#descripcion');
    const $descripcionFeedback = $('#descripcion-feedback');
    const $descripcionContador = $('#descripcion-contador');
    const $tiempoLectura = $('#tiempo-lectura');
    const $imagenInput = $('#imagen_url');
    const $imagenFeedback = $('#imagen-feedback');
    const $nuevaImagenPreview = $('#nueva-imagen-preview');
    const $previewImg = $('#preview-img');
    const $cancelarImagen = $('#cancelar-imagen');
    const $btnCancelar = $('#btn-cancelar');
    const $fechaActualizacion = $('#fecha-actualizacion');

    // Constantes de configuración para validaciones
    const CONFIG = {
        titulo: {
            minLength: 5,
            maxLength: 100,
            warningThreshold: 80
        },
        descripcion: {
            minLength: 20,
            maxLength: 2000,
            warningThreshold: 1500,
            wordsPerMinute: 200  // Promedio de palabras leídas por minuto
        },
        imagen: {
            maxSize: 5 * 1024 * 1024, // 5MB
            allowedTypes: ['image/jpeg', 'image/png', 'image/jpg']
        }
    };

    /**
     * ===============================================
     * SECCIÓN 2: FUNCIONES UTILITARIAS DE VALIDACIÓN
     * ===============================================
     */

    /**
     * Marca un campo como inválido y muestra mensaje de error
     * @param {jQuery} $input - Elemento input a marcar
     * @param {jQuery} $feedback - Elemento para mostrar error
     * @param {string} message - Mensaje de error a mostrar
     */
    function setInvalid($input, $feedback, message) {
        $input.addClass('is-invalid').removeClass('is-valid');
        $feedback.text(message).show();
    }

    /**
     * Marca un campo como válido y limpia mensajes de error
     * @param {jQuery} $input - Elemento input a marcar
     * @param {jQuery} $feedback - Elemento de feedback a limpiar
     */
    function setValid($input, $feedback) {
        $input.removeClass('is-invalid').addClass('is-valid');
        $feedback.text('').hide();
    }

    /**
     * Verifica si todo el formulario es válido
     * @returns {boolean} - true si no hay errores de validación
     */
    function isFormValid() {
        return $('.is-invalid').length === 0;
    }

    /**
     * Actualiza el estilo del contador según la longitud
     * @param {jQuery} $counter - Elemento contador
     * @param {number} length - Longitud actual
     * @param {number} maxLength - Longitud máxima
     * @param {number} warningThreshold - Umbral de advertencia
     */
    function updateCounterStyle($counter, length, maxLength, warningThreshold) {
        // Remover todas las clases de color
        $counter.removeClass('text-muted text-success text-warning text-danger');

        // Aplicar color según la longitud
        if (length > maxLength) {
            $counter.addClass('text-danger');
        } else if (length > warningThreshold) {
            $counter.addClass('text-warning');
        } else if (length > 0) {
            $counter.addClass('text-success');
        } else {
            $counter.addClass('text-muted');
        }
    }

    /**
     * ===============================================
     * SECCIÓN 3: FUNCIONES DE NEGOCIO
     * ===============================================
     */

    /**
     * Muestra la fecha y hora actual de actualización
     */
    function mostrarFechaActualizacion() {
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

        try {
            const fechaFormateada = fechaActual.toLocaleDateString('es-ES', opciones);
            $fechaActualizacion.html(`<i class="fas fa-check-circle me-2 text-success"></i>${fechaFormateada}`);
        } catch (error) {
            console.warn('Error al formatear fecha:', error);
            $fechaActualizacion.html(`<i class="fas fa-exclamation-circle me-2 text-warning"></i>Error al cargar fecha`);
        }
    }

    /**
     * Calcula el tiempo de lectura estimado basado en el número de palabras
     * @param {string} text - Texto para analizar
     * @returns {number} - Tiempo de lectura en minutos
     */
    function calcularTiempoLectura(text) {
        if (!text || text.trim().length === 0) return 0;

        // Contar palabras (separadas por espacios, filtrar vacías)
        const palabras = text.trim().split(/\s+/).filter(word => word.length > 0).length;

        // Calcular minutos (mínimo 1 minuto)
        return Math.max(1, Math.ceil(palabras / CONFIG.descripcion.wordsPerMinute));
    }

    /**
     * Valida un archivo de imagen
     * @param {File} file - Archivo a validar
     * @returns {Object} - {isValid: boolean, message: string}
     */
    function validarImagen(file) {
        if (!file) return { isValid: true, message: '' };

        // Validar tipo de archivo
        if (!CONFIG.imagen.allowedTypes.includes(file.type)) {
            return {
                isValid: false,
                message: `Solo se permiten imágenes en formato ${CONFIG.imagen.allowedTypes.map(type => type.split('/')[1].toUpperCase()).join(', ')}`
            };
        }

        // Validar tamaño
        if (file.size > CONFIG.imagen.maxSize) {
            const maxSizeMB = CONFIG.imagen.maxSize / (1024 * 1024);
            return {
                isValid: false,
                message: `La imagen no puede superar los ${maxSizeMB}MB. Tamaño actual: ${(file.size / (1024 * 1024)).toFixed(2)}MB`
            };
        }

        return { isValid: true, message: '' };
    }

    /**
     * ===============================================
     * SECCIÓN 4: FUNCIONES DE VALIDACIÓN ESPECÍFICAS
     * ===============================================
     */

    /**
     * Valida el campo título del post
     */
    function validarTitulo() {
        const valor = $tituloInput.val().trim();
        const longitud = valor.length;

        // Actualizar contador
        $tituloContador.text(`${longitud}/${CONFIG.titulo.maxLength} caracteres`);
        updateCounterStyle($tituloContador, longitud, CONFIG.titulo.maxLength, CONFIG.titulo.warningThreshold);

        // Aplicar validaciones
        if (longitud === 0) {
            setInvalid($tituloInput, $tituloFeedback, 'El título es obligatorio');
        } else if (longitud < CONFIG.titulo.minLength) {
            setInvalid($tituloInput, $tituloFeedback, `El título debe tener al menos ${CONFIG.titulo.minLength} caracteres`);
        } else if (longitud > CONFIG.titulo.maxLength) {
            setInvalid($tituloInput, $tituloFeedback, `El título no puede exceder los ${CONFIG.titulo.maxLength} caracteres`);
        } else {
            setValid($tituloInput, $tituloFeedback);
        }
    }

    /**
     * Valida el campo descripción/contenido del post
     */
    function validarDescripcion() {
        const valor = $descripcionInput.val().trim();
        const longitud = valor.length;

        // Actualizar contador de caracteres
        $descripcionContador.text(`${longitud}/${CONFIG.descripcion.maxLength} caracteres`);
        updateCounterStyle($descripcionContador, longitud, CONFIG.descripcion.maxLength, CONFIG.descripcion.warningThreshold);

        // Calcular y mostrar tiempo de lectura
        const tiempoLectura = calcularTiempoLectura(valor);
        $tiempoLectura.html(`<i class="fas fa-clock me-1"></i>Tiempo de lectura: ${tiempoLectura} min`);

        // Aplicar validaciones
        if (longitud === 0) {
            setInvalid($descripcionInput, $descripcionFeedback, 'El contenido es obligatorio');
        } else if (longitud < CONFIG.descripcion.minLength) {
            setInvalid($descripcionInput, $descripcionFeedback, `El contenido debe tener al menos ${CONFIG.descripcion.minLength} caracteres`);
        } else if (longitud > CONFIG.descripcion.maxLength) {
            setInvalid($descripcionInput, $descripcionFeedback, `El contenido no puede exceder los ${CONFIG.descripcion.maxLength} caracteres`);
        } else {
            setValid($descripcionInput, $descripcionFeedback);
        }
    }

    /**
     * ===============================================
     * SECCIÓN 5: MANEJADORES DE EVENTOS
     * ===============================================
     */

    /**
     * Evento de validación en tiempo real para el título
     */
    $tituloInput.on('input', function() {
        validarTitulo();
    });

    /**
     * Evento de validación en tiempo real para la descripción
     */
    $descripcionInput.on('input', function() {
        validarDescripcion();
    });

    /**
     * Evento para manejo de cambio de imagen
     */
    $imagenInput.on('change', function() {
        const file = this.files[0];

        if (!file) {
            $nuevaImagenPreview.hide();
            setValid($imagenInput, $imagenFeedback);
            return;
        }

        // Validar archivo
        const validacion = validarImagen(file);

        if (!validacion.isValid) {
            setInvalid($imagenInput, $imagenFeedback, validacion.message);
            $(this).val(''); // Limpiar input
            $nuevaImagenPreview.hide();
            return;
        }

        // Mostrar vista previa
        const reader = new FileReader();
        reader.onload = function(e) {
            $previewImg.attr('src', e.target.result);
            $nuevaImagenPreview.fadeIn(300); // Animación suave
            setValid($imagenInput, $imagenFeedback);
        };

        reader.onerror = function() {
            setInvalid($imagenInput, $imagenFeedback, 'Error al leer el archivo de imagen');
            $nuevaImagenPreview.hide();
        };

        reader.readAsDataURL(file);
    });

    /**
     * Evento para cancelar selección de imagen
     */
    $cancelarImagen.on('click', function() {
        $imagenInput.val('');
        $nuevaImagenPreview.fadeOut(300); // Animación suave
        setValid($imagenInput, $imagenFeedback);
    });

    /**
     * Evento para el botón cancelar con confirmación
     */
    $btnCancelar.on('click', function() {
        const hasChanges = checkForChanges(); // Función para detectar cambios (opcional)

        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: '¿Estás seguro?',
                text: hasChanges ? "Los cambios no guardados se perderán" : "Salir sin guardar cambios",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-sign-out-alt me-1"></i>Sí, salir',
                cancelButtonText: '<i class="fas fa-edit me-1"></i>Continuar editando',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '../Controlador/ControladorPostInvestigacion.php';
                }
            });
        } else {
            const mensaje = hasChanges
                ? '¿Estás seguro? Los cambios no guardados se perderán'
                : '¿Estás seguro de que quieres salir?';

            if (confirm(mensaje)) {
                window.location.href = '../Controlador/ControladorPostInvestigacion.php';
            }
        }
    });

    /**
     * Evento de envío del formulario con validación completa
     */
    $form.on('submit', function(event) {
        // Ejecutar todas las validaciones
        validarTitulo();
        validarDescripcion();

        // Verificar si hay errores
        if (!isFormValid()) {
            event.preventDefault();

            // Mostrar mensaje de error
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: 'Por favor, corrija los errores antes de continuar',
                    confirmButtonText: 'Entendido',
                    footer: '<i class="fas fa-lightbulb"></i> Revise los campos marcados en rojo'
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
            // Mostrar indicador de carga
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: '<i class="fas fa-save"></i> Guardando cambios',
                    html: 'Procesando su solicitud...<br><small>Por favor, no cierre esta ventana</small>',
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
     * ===============================================
     * SECCIÓN 6: FUNCIONES AUXILIARES
     * ===============================================
     */

    /**
     * Detecta si hay cambios en el formulario (opcional)
     * @returns {boolean} - true si hay cambios pendientes
     */
    function checkForChanges() {
        // Esta función podría implementarse para detectar cambios
        // comparando valores iniciales con valores actuales
        // Por simplicidad, asumimos que siempre hay cambios si los campos no están vacíos
        return $tituloInput.val().trim().length > 0 || $descripcionInput.val().trim().length > 0;
    }

    /**
     * ===============================================
     * SECCIÓN 7: INICIALIZACIÓN DEL FORMULARIO
     * ===============================================
     */

    /**
     * Función de inicialización que configura el estado inicial
     */
    function inicializarFormulario() {
        try {
            // Mostrar fecha actual
            mostrarFechaActualizacion();

            // Ejecutar validaciones iniciales para establecer contadores
            validarTitulo();
            validarDescripcion();

            // Enfocar el primer campo
            $tituloInput.focus();

            // Log para debugging (remover en producción)
            console.log('Formulario de actualización de post inicializado correctamente');
            console.log('Configuración:', CONFIG);

        } catch (error) {
            console.error('Error al inicializar el formulario:', error);

            // Mostrar error al usuario si es crítico
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de inicialización',
                    text: 'Hubo un problema al cargar el formulario. Por favor, recargue la página.',
                    confirmButtonText: 'Recargar',
                    allowOutsideClick: false
                }).then(() => {
                    window.location.reload();
                });
            }
        }
    }



    /**
     * Ejecutar inicialización cuando el DOM esté completamente listo
     */
    inicializarFormulario();


});