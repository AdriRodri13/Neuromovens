
/**
 * Archivo: contacto.js
 * Descripción: Validación simple para formulario de contacto
 * Dependencias: jQuery
 */

$(document).ready(function() {
    const $form = $('#form-contacto');
    const $nombreInput = $('#nombre');
    const $emailInput = $('#email');
    const $telefonoInput = $('#telefono');
    const $consultaInput = $('#consulta');
    const $politicaInput = $('#politica');
    const $btnLimpiar = $('#btn-limpiar');
    const $btnEnviar = $('#btn-enviar');

    // Configuración de validación simple
    const CONFIG = {
        nombre: {
            min: 2,
            pattern: /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/
        },
        email: {
            pattern: /^[^\s@]+@[^\s@]+\.[^\s@]+$/
        },
        telefono: {
            pattern: /^(\+34\s?)?[67890]\d{8}$/
        },
        consulta: {
            min: 10
        }
    };

    // Función para mostrar error
    function mostrarError($input, mensaje) {
        $input.addClass('is-invalid').removeClass('is-valid');
        $input.next('.invalid-feedback').remove();
        $input.after(`<div class="invalid-feedback">${mensaje}</div>`);
    }

    // Función para mostrar como válido
    function mostrarValido($input) {
        $input.removeClass('is-invalid').addClass('is-valid');
        $input.next('.invalid-feedback').remove();
    }

    // Validar nombre
    function validarNombre() {
        const valor = $nombreInput.val().trim();

        if (!valor) {
            mostrarError($nombreInput, 'El nombre es obligatorio');
            return false;
        }
        if (valor.length < CONFIG.nombre.min) {
            mostrarError($nombreInput, `Mínimo ${CONFIG.nombre.min} caracteres`);
            return false;
        }
        if (!CONFIG.nombre.pattern.test(valor)) {
            mostrarError($nombreInput, 'Solo letras y espacios permitidos');
            return false;
        }

        mostrarValido($nombreInput);
        return true;
    }

    // Validar email
    function validarEmail() {
        const valor = $emailInput.val().trim();

        if (!valor) {
            mostrarError($emailInput, 'El email es obligatorio');
            return false;
        }
        if (!CONFIG.email.pattern.test(valor)) {
            mostrarError($emailInput, 'Formato de email inválido');
            return false;
        }

        mostrarValido($emailInput);
        return true;
    }

    // Validar teléfono
    function validarTelefono() {
        const valor = $telefonoInput.val().trim();

        if (!valor) {
            mostrarError($telefonoInput, 'El teléfono es obligatorio');
            return false;
        }
        if (!CONFIG.telefono.pattern.test(valor)) {
            mostrarError($telefonoInput, 'Formato: +34 123456789 o 123456789');
            return false;
        }

        mostrarValido($telefonoInput);
        return true;
    }

    // Validar consulta
    function validarConsulta() {
        const valor = $consultaInput.val().trim();

        if (!valor) {
            mostrarError($consultaInput, 'La consulta es obligatoria');
            return false;
        }
        if (valor.length < CONFIG.consulta.min) {
            mostrarError($consultaInput, `Mínimo ${CONFIG.consulta.min} caracteres`);
            return false;
        }

        mostrarValido($consultaInput);
        return true;
    }

    // Validar política
    function validarPolitica() {
        if (!$politicaInput.is(':checked')) {
            mostrarError($politicaInput, 'Debe aceptar la política');
            return false;
        }

        mostrarValido($politicaInput);
        return true;
    }

    // Formatear teléfono automáticamente
    $telefonoInput.on('input', function() {
        let valor = $(this).val().replace(/\D/g, '');

        if (valor.length === 9 && /^[67890]/.test(valor)) {
            $(this).val('+34 ' + valor);
        }
    });

    // Validación en tiempo real (opcional)
    $nombreInput.on('blur', validarNombre);
    $emailInput.on('blur', validarEmail);
    $telefonoInput.on('blur', validarTelefono);
    $consultaInput.on('blur', validarConsulta);
    $politicaInput.on('change', validarPolitica);

    // Limpiar formulario
    $btnLimpiar.on('click', function() {
        $form[0].reset();
        $('.form-control, .form-check-input').removeClass('is-valid is-invalid');
        $('.invalid-feedback').remove();
        $nombreInput.focus();
    });

    // Envío del formulario
    $form.on('submit', function(e) {
        e.preventDefault();

        // Ejecutar todas las validaciones
        const nombreValido = validarNombre();
        const emailValido = validarEmail();
        const telefonoValido = validarTelefono();
        const consultaValida = validarConsulta();
        const politicaValida = validarPolitica();

        // Si hay errores, enfocar primer campo con error
        if (!nombreValido || !emailValido || !telefonoValido || !consultaValida || !politicaValida) {
            $('.is-invalid').first().focus();
            return;
        }

        // Todo válido - mostrar loading y simular envío
        $btnEnviar.prop('disabled', true).text('Enviando...');


        setTimeout(() => {
            alert('¡Consulta enviada correctamente!');

            // Resetear formulario
            $form[0].reset();
            $('.form-control, .form-check-input').removeClass('is-valid is-invalid');
            $('.invalid-feedback').remove();

            // Restaurar botón
            $btnEnviar.prop('disabled', false).text('Enviar consulta');

            // Enfocar primer campo
            $nombreInput.focus();
        }, 1500);
    });

    // Enfocar primer campo al cargar
    $nombreInput.focus();
});