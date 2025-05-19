/**
 * Archivo: ActualizarProducto.js
 * Descripción: Maneja toda la funcionalidad del formulario de actualización de productos
 *
 * Funcionalidades principales:
 * - Validación en tiempo real de todos los campos
 * - Contador de caracteres dinámico para descripción
 * - Vista previa de imágenes con validación
 * - Establecimiento automático de fecha de actualización
 * - Confirmación antes de cancelar cambios
 * - Validación integral antes del envío
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
    const $form = $('#form-actualizar-producto');
    const $nombreInput = $('#nombre');
    const $nombreError = $('#nombre-error');
    const $descripcionInput = $('#descripcion');
    const $descripcionError = $('#descripcion-error');
    const $contadorCaracteres = $('#contador-caracteres');
    const $precioInput = $('#precio');
    const $precioError = $('#precio-error');
    const $categoriaSelect = $('#categoria_id');
    const $categoriaError = $('#categoria-error');
    const $imagenInput = $('#imagen_url');
    const $imagenError = $('#imagen-error');
    const $nuevaImagenPreview = $('#nueva-imagen-preview');
    const $previewImg = $('#preview-img');
    const $cancelarImagen = $('#cancelar-imagen');
    const $fechaActualizacion = $('#fecha_actualizacion');
    const $btnCancelar = $('#btn-cancelar');
    const $btnActualizar = $('#btn-actualizar');

    // Configuración de validaciones
    const VALIDACION_CONFIG = {
        nombre: {
            min: 3,
            max: 100
        },
        descripcion: {
            min: 10,
            max: 1000,
            warningThreshold: 300,
            dangerThreshold: 500
        },
        precio: {
            min: 0.01,
            max: 999999.99
        },
        imagen: {
            maxSize: 5 * 1024 * 1024, // 5MB
            allowedTypes: ['image/jpeg', 'image/jpg', 'image/png'],
            allowedExtensions: ['jpg', 'jpeg', 'png']
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
     * @param {jQuery} $errorElement - Elemento para mostrar el error
     * @param {string} message - Mensaje de error
     */
    function setFieldInvalid($input, $errorElement, message) {
        $input.addClass('is-invalid').removeClass('is-valid');
        $errorElement.text(message).show();
    }

    /**
     * Marca un campo como válido y limpia errores
     * @param {jQuery} $input - Campo a marcar como válido
     * @param {jQuery} $errorElement - Elemento de error a limpiar
     */
    function setFieldValid($input, $errorElement) {
        $input.removeClass('is-invalid').addClass('is-valid');
        $errorElement.text('').hide();
    }

    /**
     * Verifica si el formulario completo es válido
     * @returns {boolean} - true si no hay campos inválidos
     */
    function isFormValid() {
        return $('.is-invalid').length === 0;
    }

    /**
     * Actualiza el color del contador según la longitud del texto
     * @param {number} length - Longitud actual del texto
     */
    function updateCounterColor(length) {
        $contadorCaracteres.removeClass('text-muted text-success text-warning text-danger');

        if (length > VALIDACION_CONFIG.descripcion.dangerThreshold) {
            $contadorCaracteres.addClass('text-danger');
        } else if (length > VALIDACION_CONFIG.descripcion.warningThreshold) {
            $contadorCaracteres.addClass('text-warning');
        } else if (length > 0) {
            $contadorCaracteres.addClass('text-success');
        } else {
            $contadorCaracteres.addClass('text-muted');
        }
    }

    /**
     * Formatea un número como precio (con 2 decimales)
     * @param {number} precio - Precio a formatear
     * @returns {string} - Precio formateado
     */
    function formatearPrecio(precio) {
        return parseFloat(precio).toFixed(2);
    }

    /**
     * ===============================================
     * SECCIÓN 3: FUNCIONES DE VALIDACIÓN
     * ===============================================
     */

    /**
     * Valida el campo nombre del producto
     */
    function validarNombre() {
        const nombre = $nombreInput.val().trim();
        const length = nombre.length;

        if (length === 0) {
            setFieldInvalid($nombreInput, $nombreError, 'El nombre del producto es obligatorio');
            return false;
        } else if (length < VALIDACION_CONFIG.nombre.min) {
            setFieldInvalid($nombreInput, $nombreError, `El nombre debe tener al menos ${VALIDACION_CONFIG.nombre.min} caracteres`);
            return false;
        } else if (length > VALIDACION_CONFIG.nombre.max) {
            setFieldInvalid($nombreInput, $nombreError, `El nombre no puede tener más de ${VALIDACION_CONFIG.nombre.max} caracteres`);
            return false;
        } else {
            setFieldValid($nombreInput, $nombreError);
            return true;
        }
    }

    /**
     * Valida el campo descripción del producto
     */
    function validarDescripcion() {
        const descripcion = $descripcionInput.val().trim();
        const length = descripcion.length;

        // Actualizar contador de caracteres
        $contadorCaracteres.text(`${length} caracteres`);
        updateCounterColor(length);

        if (length === 0) {
            setFieldInvalid($descripcionInput, $descripcionError, 'La descripción del producto es obligatoria');
            return false;
        } else if (length < VALIDACION_CONFIG.descripcion.min) {
            setFieldInvalid($descripcionInput, $descripcionError, `La descripción debe tener al menos ${VALIDACION_CONFIG.descripcion.min} caracteres`);
            return false;
        } else if (length > VALIDACION_CONFIG.descripcion.max) {
            setFieldInvalid($descripcionInput, $descripcionError, `La descripción no puede tener más de ${VALIDACION_CONFIG.descripcion.max} caracteres`);
            return false;
        } else {
            setFieldValid($descripcionInput, $descripcionError);
            return true;
        }
    }

    /**
     * Valida el campo precio del producto
     */
    function validarPrecio() {
        const precioValue = $precioInput.val();
        const precio = parseFloat(precioValue);

        if (!precioValue || precioValue === '') {
            setFieldInvalid($precioInput, $precioError, 'El precio es obligatorio');
            return false;
        } else if (isNaN(precio)) {
            setFieldInvalid($precioInput, $precioError, 'El precio debe ser un número válido');
            return false;
        } else if (precio < VALIDACION_CONFIG.precio.min) {
            setFieldInvalid($precioInput, $precioError, `El precio debe ser mayor que ${VALIDACION_CONFIG.precio.min}€`);
            return false;
        } else if (precio > VALIDACION_CONFIG.precio.max) {
            setFieldInvalid($precioInput, $precioError, `El precio no puede superar ${VALIDACION_CONFIG.precio.max}€`);
            return false;
        } else {
            setFieldValid($precioInput, $precioError);
            // Formatear el precio con 2 decimales
            $precioInput.val(formatearPrecio(precio));
            return true;
        }
    }

    /**
     * Valida la selección de categoría
     */
    function validarCategoria() {
        const categoria = $categoriaSelect.val();

        if (!categoria || categoria === '') {
            setFieldInvalid($categoriaSelect, $categoriaError, 'Debe seleccionar una categoría');
            return false;
        } else {
            setFieldValid($categoriaSelect, $categoriaError);
            return true;
        }
    }

    /**
     * Valida el archivo de imagen seleccionado
     * @param {File} file - Archivo a validar
     * @returns {Object} - {isValid: boolean, message: string}
     */
    function validarImagenArchivo(file) {
        if (!file) return { isValid: true, message: '' };

        // Validar tamaño
        if (file.size > VALIDACION_CONFIG.imagen.maxSize) {
            const maxSizeMB = VALIDACION_CONFIG.imagen.maxSize / (1024 * 1024);
            return {
                isValid: false,
                message: `La imagen es demasiado grande. Tamaño máximo: ${maxSizeMB}MB. Tamaño actual: ${(file.size / (1024 * 1024)).toFixed(2)}MB`
            };
        }

        // Validar tipo de archivo
        if (!VALIDACION_CONFIG.imagen.allowedTypes.includes(file.type)) {
            return {
                isValid: false,
                message: `Tipo de archivo no permitido. Solo se aceptan: ${VALIDACION_CONFIG.imagen.allowedExtensions.join(', ').toUpperCase()}`
            };
        }

        // Validar extensión
        const fileName = file.name.toLowerCase();
        const extension = fileName.split('.').pop();
        if (!VALIDACION_CONFIG.imagen.allowedExtensions.includes(extension)) {
            return {
                isValid: false,
                message: `Extensión no válida. Solo se permiten: ${VALIDACION_CONFIG.imagen.allowedExtensions.join(', ').toUpperCase()}`
            };
        }

        return { isValid: true, message: '' };
    }

    /**
     * ===============================================
     * SECCIÓN 4: FUNCIONES DE FECHA
     * ===============================================
     */

    /**
     * Establece la fecha actual en el campo correspondiente
     */
    function establecerFechaActual() {
        try {
            const fechaActual = new Date();

            // Formatear fecha en español
            const opciones = {
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };

            const fechaFormateada = fechaActual.toLocaleDateString('es-ES', opciones);
            $fechaActualizacion.val(fechaFormateada);

            console.log('Fecha establecida correctamente:', fechaFormateada);
        } catch (error) {
            console.error('Error al establecer fecha:', error);
            // Fallback a formato simple
            const fechaSimple = new Date().toLocaleDateString();
            $fechaActualizacion.val(fechaSimple);
        }
    }

    /**
     * ===============================================
     * SECCIÓN 5: FUNCIONES DE IMAGEN
     * ===============================================
     */

    /**
     * Maneja la vista previa de la imagen seleccionada
     * @param {File} file - Archivo de imagen
     */
    function mostrarVistaPrevia(file) {
        const reader = new FileReader();

        reader.onload = function(e) {
            $previewImg.attr('src', e.target.result);
            $nuevaImagenPreview.fadeIn(300);
        };

        reader.onerror = function() {
            console.error('Error al leer el archivo');
            setFieldInvalid($imagenInput, $imagenError, 'Error al procesar la imagen');
        };

        reader.readAsDataURL(file);
    }

    /**
     * Cancela la selección de imagen y oculta la vista previa
     */
    function cancelarSeleccionImagen() {
        $imagenInput.val('');
        $nuevaImagenPreview.fadeOut(300);
        setFieldValid($imagenInput, $imagenError);
    }

    /**
     * ===============================================
     * SECCIÓN 6: MANEJADORES DE EVENTOS
     * ===============================================
     */

    /**
     * Eventos de validación en tiempo real
     */
    $nombreInput.on('input blur', validarNombre);
    $descripcionInput.on('input', validarDescripcion);
    $precioInput.on('input blur', validarPrecio);
    $categoriaSelect.on('change', validarCategoria);

    /**
     * Evento para formatear precio al perder el foco
     */
    $precioInput.on('blur', function() {
        const valor = $(this).val();
        if (valor && !isNaN(parseFloat(valor))) {
            $(this).val(formatearPrecio(parseFloat(valor)));
        }
    });

    /**
     * Evento de cambio de imagen
     */
    $imagenInput.on('change', function() {
        const file = this.files[0];

        if (!file) {
            $nuevaImagenPreview.hide();
            setFieldValid($imagenInput, $imagenError);
            return;
        }

        const validacion = validarImagenArchivo(file);

        if (!validacion.isValid) {
            setFieldInvalid($imagenInput, $imagenError, validacion.message);
            $(this).val(''); // Limpiar input
            $nuevaImagenPreview.hide();
        } else {
            setFieldValid($imagenInput, $imagenError);
            mostrarVistaPrevia(file);
        }
    });

    /**
     * Evento para cancelar imagen
     */
    $cancelarImagen.on('click', cancelarSeleccionImagen);

    /**
     * Evento del botón cancelar
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
                    window.location.href = '../Controlador/ControladorProductos.php';
                }
            });
        } else {
            if (confirm('¿Estás seguro? Los cambios no guardados se perderán')) {
                window.location.href = '../Controlador/ControladorProductos.php';
            }
        }
    });

    /**
     * Evento de envío del formulario
     */
    $form.on('submit', function(e) {
        // Ejecutar todas las validaciones
        const nombreValido = validarNombre();
        const descripcionValida = validarDescripcion();
        const precioValido = validarPrecio();
        const categoriaValida = validarCategoria();

        // Validar imagen si se seleccionó una nueva
        let imagenValida = true;
        const imagenFile = $imagenInput[0].files[0];
        if (imagenFile) {
            const validacionImagen = validarImagenArchivo(imagenFile);
            if (!validacionImagen.isValid) {
                setFieldInvalid($imagenInput, $imagenError, validacionImagen.message);
                imagenValida = false;
            }
        }

        // Si hay errores, prevenir envío
        if (!nombreValido || !descripcionValida || !precioValido || !categoriaValida || !imagenValida) {
            e.preventDefault();

            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    html: 'Por favor, corrija los errores antes de continuar.<br><small>Los campos con errores están marcados en rojo.</small>',
                    confirmButtonText: 'Entendido',
                    footer: '<i class="fas fa-lightbulb"></i> Verifique todos los campos requeridos'
                });
            } else {
                alert('Por favor, corrija los errores antes de continuar');
            }

            // Scroll al primer campo con error
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
                    title: '<i class="fas fa-save"></i> Guardando cambios',
                    html: 'Actualizando producto...<br><small>Por favor, no cierre esta ventana</small>',
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
     * SECCIÓN 7: FUNCIONES DE INICIALIZACIÓN
     * ===============================================
     */

    /**
     * Inicializa todos los componentes del formulario
     */
    function inicializarFormulario() {
        try {
            // Establecer fecha actual
            establecerFechaActual();

            // Ejecutar validaciones iniciales para establecer contadores
            validarDescripcion(); // Para mostrar el contador inicial

            // Enfocar el primer campo
            $nombreInput.focus();

            // Log para debugging (remover en producción)
            console.log('Formulario de actualización de producto inicializado correctamente');
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
     * ===============================================
     * SECCIÓN 8: MANEJO DE ERRORES GLOBALES
     * ===============================================
     */

    /**
     * Manejador de errores globales no capturados
     */
    $(window).on('error', function(event) {
        const error = event.originalEvent.error;
        console.error('Error no manejado en ActualizarProducto.js:', error);

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
    window.ProductoFormValidator = {
        validarNombre: validarNombre,
        validarDescripcion: validarDescripcion,
        validarPrecio: validarPrecio,
        validarCategoria: validarCategoria,
        validarImagenArchivo: validarImagenArchivo,
        isFormValid: isFormValid,
        establecerFechaActual: establecerFechaActual,
        config: VALIDACION_CONFIG
    };

});