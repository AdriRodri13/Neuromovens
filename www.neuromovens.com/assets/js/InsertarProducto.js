/**
 * Archivo: InsertarProducto.js
 * Descripción: Maneja la funcionalidad del formulario de inserción de productos
 *
 * Funcionalidades principales:
 * - Validación completa de todos los campos del producto
 * - Contador de caracteres dinámico para la descripción
 * - Vista previa de imagen seleccionada
 * - Verificación de disponibilidad de nombre (AJAX)
 * - Envío del formulario con Fetch API
 * - Confirmación antes de cancelar
 * - Mensajes de error y éxito
 *
 * Dependencias:
 * - jQuery (requerido)
 * - Bootstrap (para clases CSS y validación visual)
 * - Fetch API (nativo del navegador)
 * - FontAwesome (para iconos - opcional)
 */

$(document).ready(function() {
    /**
     * ===============================================
     * SECCIÓN 1: CONFIGURACIÓN Y VARIABLES GLOBALES
     * ===============================================
     */

        // Selectores jQuery cacheados para mejor rendimiento
    const $form = $('#form-insertar-producto');
    const $nombreInput = $('#nombre');
    const $descripcionInput = $('#descripcion');
    const $precioInput = $('#precio');
    const $categoriaSelect = $('#categoria_id');
    const $imagenInput = $('#imagen_url');
    const $imagenPreview = $('#imagen-preview');
    const $previewImg = $('#preview-img');
    const $btnCancelar = $('#btn-cancelar');
    const $btnSubmit = $('button[type="submit"]');
    const $contadorCaracteres = $('#contador-caracteres');
    const $mensajeRespuesta = $('#mensaje-respuesta');

    // Elementos de feedback
    const $nombreFeedback = $('#nombre-feedback');
    const $descripcionFeedback = $('#descripcion-feedback');
    const $precioFeedback = $('#precio-feedback');
    const $categoriaFeedback = $('#categoria-feedback');
    const $imagenFeedback = $('#imagen-feedback');

    // Configuración de validaciones
    const VALIDACION_CONFIG = {
        nombre: {
            min: 3,
            max: 100
        },
        descripcion: {
            min: 10,
            max: 500,
            warningThreshold: 400
        },
        precio: {
            min: 0.01,
            max: 999999.99
        },
        imagen: {
            maxSize: 5 * 1024 * 1024, // 5MB
            allowedTypes: ['image/jpeg', 'image/png', 'image/jpg'],
            allowedMimeTypes: ['image/jpeg', 'image/png']
        }
    };

    // Variables de control
    let nombreDisponible = true;
    let verificandoNombre = false;

    /**
     * ===============================================
     * SECCIÓN 2: FUNCIONES UTILITARIAS
     * ===============================================
     */

    /**
     * Muestra error en un campo específico
     * @param {jQuery} $campo - Campo a marcar como inválido
     * @param {jQuery} $feedback - Elemento para mostrar el error
     * @param {string} mensaje - Mensaje de error
     */
    function mostrarError($campo, $feedback, mensaje) {
        $campo.addClass('is-invalid');
        $feedback.text(mensaje).show();
    }

    /**
     * Quita error de un campo específico
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
     * Oculta el mensaje general
     */
    function ocultarMensaje() {
        $mensajeRespuesta.hide();
    }

    /**
     * Actualiza el color del contador según la longitud
     * @param {number} longitud - Longitud actual del texto
     */
    function actualizarContadorColor(longitud) {
        $contadorCaracteres.removeClass('text-warning text-success text-muted').addClass('form-text');

        if (longitud > VALIDACION_CONFIG.descripcion.warningThreshold) {
            $contadorCaracteres.addClass('text-warning');
        } else if (longitud > 0) {
            $contadorCaracteres.addClass('text-success');
        } else {
            $contadorCaracteres.addClass('text-muted');
        }
    }

    /**
     * ===============================================
     * SECCIÓN 3: FUNCIONES DE VALIDACIÓN
     * ===============================================
     */

    /**
     * Valida el campo nombre del producto
     * @returns {boolean} - true si es válido
     */
    function validarNombre() {
        const nombre = $nombreInput.val().trim();

        if (nombre === '') {
            mostrarError($nombreInput, $nombreFeedback, 'El nombre es obligatorio');
            return false;
        } else if (nombre.length < VALIDACION_CONFIG.nombre.min) {
            mostrarError($nombreInput, $nombreFeedback, `El nombre debe tener al menos ${VALIDACION_CONFIG.nombre.min} caracteres`);
            return false;
        } else if (nombre.length > VALIDACION_CONFIG.nombre.max) {
            mostrarError($nombreInput, $nombreFeedback, `El nombre no puede exceder los ${VALIDACION_CONFIG.nombre.max} caracteres`);
            return false;
        } else if (!nombreDisponible) {
            mostrarError($nombreInput, $nombreFeedback, 'Este nombre de producto ya existe');
            return false;
        } else {
            quitarError($nombreInput, $nombreFeedback);
            return true;
        }
    }

    /**
     * Valida el campo descripción del producto
     * @returns {boolean} - true si es válido
     */
    function validarDescripcion() {
        const descripcion = $descripcionInput.val().trim();

        if (descripcion === '') {
            mostrarError($descripcionInput, $descripcionFeedback, 'La descripción es obligatoria');
            return false;
        } else if (descripcion.length < VALIDACION_CONFIG.descripcion.min) {
            mostrarError($descripcionInput, $descripcionFeedback, `La descripción debe tener al menos ${VALIDACION_CONFIG.descripcion.min} caracteres`);
            return false;
        } else if (descripcion.length > VALIDACION_CONFIG.descripcion.max) {
            mostrarError($descripcionInput, $descripcionFeedback, `La descripción no puede exceder los ${VALIDACION_CONFIG.descripcion.max} caracteres`);
            return false;
        } else {
            quitarError($descripcionInput, $descripcionFeedback);
            return true;
        }
    }

    /**
     * Valida el campo precio del producto
     * @returns {boolean} - true si es válido
     */
    function validarPrecio() {
        const precio = $precioInput.val();
        const precioNumerico = parseFloat(precio);

        if (precio === '') {
            mostrarError($precioInput, $precioFeedback, 'El precio es obligatorio');
            return false;
        } else if (isNaN(precioNumerico) || precioNumerico <= 0) {
            mostrarError($precioInput, $precioFeedback, 'El precio debe ser mayor que 0');
            return false;
        } else if (precioNumerico < VALIDACION_CONFIG.precio.min) {
            mostrarError($precioInput, $precioFeedback, `El precio mínimo es ${VALIDACION_CONFIG.precio.min}€`);
            return false;
        } else if (precioNumerico > VALIDACION_CONFIG.precio.max) {
            mostrarError($precioInput, $precioFeedback, `El precio máximo es ${VALIDACION_CONFIG.precio.max}€`);
            return false;
        } else {
            quitarError($precioInput, $precioFeedback);
            return true;
        }
    }

    /**
     * Valida la selección de categoría
     * @returns {boolean} - true si es válido
     */
    function validarCategoria() {
        const categoria = $categoriaSelect.val();

        if (!categoria) {
            mostrarError($categoriaSelect, $categoriaFeedback, 'Selecciona una categoría');
            return false;
        } else {
            quitarError($categoriaSelect, $categoriaFeedback);
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
            mostrarError($imagenInput, $imagenFeedback, 'Selecciona una imagen');
            return false;
        }

        // Validar tipo de archivo
        if (!VALIDACION_CONFIG.imagen.allowedMimeTypes.includes(archivo.type)) {
            mostrarError($imagenInput, $imagenFeedback, 'Solo se permiten imágenes JPG y PNG');
            return false;
        }

        // Validar tamaño
        if (archivo.size > VALIDACION_CONFIG.imagen.maxSize) {
            const maxSizeMB = VALIDACION_CONFIG.imagen.maxSize / (1024 * 1024);
            mostrarError($imagenInput, $imagenFeedback, `La imagen no puede superar ${maxSizeMB}MB`);
            return false;
        }

        quitarError($imagenInput, $imagenFeedback);
        return true;
    }

    /**
     * ===============================================
     * SECCIÓN 4: FUNCIONES AJAX
     * ===============================================
     */

    /**
     * Verifica la disponibilidad del nombre del producto
     * @param {string} nombre - Nombre a verificar
     */
    function verificarDisponibilidadNombre(nombre) {
        if (verificandoNombre) return;

        verificandoNombre = true;

        fetch(`../Controlador/ajax_productos.php?accion=comprobar_nombre&nombre=${encodeURIComponent(nombre)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                nombreDisponible = data.disponible;

                if (!nombreDisponible) {
                    mostrarError($nombreInput, $nombreFeedback, 'Este nombre de producto ya existe');
                } else if ($nombreInput.val().trim() === nombre) {
                    // Solo quitar error si el valor no ha cambiado
                    quitarError($nombreInput, $nombreFeedback);
                }
            })
            .catch(error => {
                console.error('Error al verificar disponibilidad:', error);
            })
            .finally(() => {
                verificandoNombre = false;
            });
    }

    /**
     * Envía el formulario usando Fetch API
     * @param {FormData} formData - Datos del formulario
     */
    function enviarFormulario(formData) {
        fetch('../Controlador/ajax_productos.php', {
            method: 'POST',
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
                return response.json();
            })
            .then(data => {
                if (data.exito) {
                    mostrarMensaje('success', 'Producto creado correctamente');

                    // Redirigir después de un breve delay
                    setTimeout(function() {
                        $form[0].reset();
                        $imagenPreview.hide();
                        window.location.href = '../Controlador/ControladorProductos.php';
                    }, 1500);
                } else {
                    mostrarMensaje('danger', data.mensaje || 'Error al crear el producto');
                    restaurarBotonSubmit();
                }
            })
            .catch(error => {
                mostrarMensaje('danger', 'Error en la conexión: ' + error.message);
                restaurarBotonSubmit();
            });
    }

    /**
     * ===============================================
     * SECCIÓN 5: MANEJADORES DE EVENTOS
     * ===============================================
     */

    /**
     * Vista previa de imagen al seleccionar archivo
     */
    $imagenInput.on('change', function() {
        const archivo = this.files[0];

        if (archivo) {
            // Verificar que es un tipo de imagen válido antes de mostrar vista previa
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
     * Contador de caracteres para la descripción
     */
    $descripcionInput.on('input', function() {
        const longitud = $(this).val().length;
        $contadorCaracteres.text(`${longitud}/${VALIDACION_CONFIG.descripcion.max} caracteres`);
        actualizarContadorColor(longitud);
    });

    /**
     * Verificación de disponibilidad de nombre
     */
    $nombreInput.on('blur', function() {
        const nombre = $(this).val().trim();

        if (nombre.length >= VALIDACION_CONFIG.nombre.min) {
            verificarDisponibilidadNombre(nombre);
        }
    });

    /**
     * Botón cancelar con confirmación
     */
    $btnCancelar.on('click', function() {
        if (confirm('¿Estás seguro de cancelar? Los datos no se guardarán.')) {
            window.location.href = '../Controlador/ControladorProductos.php';
        }
    });

    /**
     * Envío del formulario con validación y AJAX
     */
    $form.on('submit', function(e) {
        e.preventDefault();

        // Ocultar mensajes previos
        ocultarMensaje();

        // Ejecutar todas las validaciones
        const nombreValido = validarNombre();
        const descripcionValida = validarDescripcion();
        const precioValido = validarPrecio();
        const categoriaValida = validarCategoria();
        const imagenValida = validarImagen();

        const esValido = nombreValido && descripcionValida && precioValido && categoriaValida && imagenValida;

        // Si hay errores, detener envío
        if (!esValido) {
            mostrarMensaje('danger', 'Por favor, corrige los errores antes de continuar.');

            // Enfocar el primer campo con error
            const $firstError = $('.is-invalid').first();
            if ($firstError.length) {
                $firstError.focus();
            }

            return false;
        }

        // Mostrar indicador de carga
        mostrarMensaje('info', 'Procesando...');
        $btnSubmit.prop('disabled', true).text('Procesando...');

        // Preparar y enviar datos
        const formData = new FormData(this);
        enviarFormulario(formData);
    });

    /**
     * ===============================================
     * SECCIÓN 6: FUNCIONES AUXILIARES
     * ===============================================
     */

    /**
     * Restaura el botón de submit a su estado original
     */
    function restaurarBotonSubmit() {
        $btnSubmit.prop('disabled', false).text('Insertar Producto');
    }

    /**
     * Limpia completamente el formulario
     */
    function limpiarFormulario() {
        $form[0].reset();
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').text('').hide();
        $imagenPreview.hide();
        ocultarMensaje();
        restaurarBotonSubmit();

        // Reinicializar contador
        $contadorCaracteres.text('0/500 caracteres').removeClass('text-warning text-success').addClass('text-muted');

        // Reset variables de control
        nombreDisponible = true;
        verificandoNombre = false;

        $nombreInput.focus();
    }

    /**
     * ===============================================
     * SECCIÓN 7: FUNCIONES DE INICIALIZACIÓN
     * ===============================================
     */

    /**
     * Inicializa el formulario y sus componentes
     */
    function inicializarFormulario() {
        try {
            // Enfocar el primer campo
            $nombreInput.focus();

            // Inicializar contador de descripción
            $descripcionInput.trigger('input');

            // Log para debugging (remover en producción)
            console.log('Formulario de inserción de producto inicializado correctamente');
            console.log('Configuración de validación:', VALIDACION_CONFIG);

        } catch (error) {
            console.error('Error al inicializar el formulario:', error);
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
        console.error('Error no manejado en InsertarProducto.js:', error);

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
    window.InsertarProductoValidator = {
        validarNombre: validarNombre,
        validarDescripcion: validarDescripcion,
        validarPrecio: validarPrecio,
        validarCategoria: validarCategoria,
        validarImagen: validarImagen,
        limpiarFormulario: limpiarFormulario,
        verificarDisponibilidadNombre: verificarDisponibilidadNombre,
        config: VALIDACION_CONFIG
    };

});