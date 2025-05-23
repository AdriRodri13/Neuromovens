/**
 * Archivo: ListarUsuarios.js
 * Descripción: Maneja la funcionalidad de la lista de usuarios con búsqueda y paginación
 *
 * Funcionalidades principales:
 * - Búsqueda en tiempo real con debouncing
 * - Paginación AJAX
 * - Actualización dinámica de tabla y controles
 * - Indicadores de carga
 * - Efectos de hover mejorados
 * - Recarga de lista
 *
 * Dependencias:
 * - jQuery (requerido)
 * - Bootstrap (para clases CSS y componentes)
 * - Fetch API (nativo del navegador)
 * - FontAwesome (para iconos)
 */

$(document).ready(function() {
    /**
     * ===============================================
     * SECCIÓN 1: CONFIGURACIÓN Y VARIABLES GLOBALES
     * ===============================================
     */

        // Variables de control de estado
    let timeoutId;
    let paginaBusqueda = 1;
    let terminoBusqueda = '';
    let buscandoActualmente = false;

    // Selectores jQuery cacheados
    const $buscarInput = $('#buscarUsuario');
    const $buscarBtn = $('#buscarBtn');
    const $limpiarBtn = $('#limpiarBusqueda');
    const $recargarBtn = $('#recargarLista');
    const $loadingIndicator = $('#loadingIndicator');
    const $tablaUsuarios = $('#tablaUsuarios');
    const $paginacionContainer = $('#paginacionContainer');
    const $infoResultados = $('#infoResultados');

    // Configuración
    const CONFIG = {
        debounceDelay: 500, // Tiempo de espera antes de buscar (ms)
        usuariosPorPagina: 5,
        endpoints: {
            buscar: '../Controlador/ControladorUsuario.php',
            listar: '../Controlador/ControladorUsuario.php?accion=listar_paginado'
        }
    };

    /**
     * ===============================================
     * SECCIÓN 2: FUNCIONES UTILITARIAS
     * ===============================================
     */

    /**
     * Muestra u oculta el indicador de carga
     * @param {boolean} mostrar - true para mostrar, false para ocultar
     */
    function mostrarLoading(mostrar) {
        if (mostrar) {
            $loadingIndicator.show();
            $tablaUsuarios.css('opacity', '0.5');
            $paginacionContainer.css('opacity', '0.5');
        } else {
            $loadingIndicator.hide();
            $tablaUsuarios.css('opacity', '1');
            $paginacionContainer.css('opacity', '1');
        }
    }

    /**
     * Escapa HTML para prevenir XSS
     * @param {string} text - Texto a escapar
     * @returns {string} - Texto escapado
     */
    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    /**
     * Obtiene la clase CSS para el badge del rol
     * @param {string} rol - Nombre del rol
     * @returns {string} - Clase CSS del badge
     */
    function getRolBadgeClass(rol) {
        const clases = {
            'jefe': 'bg-danger',
            'empleado': 'bg-warning text-dark',
            'visitante': 'bg-secondary',
            'administrador': 'bg-primary'
        };
        return clases[rol] || 'bg-primary';
    }

    /**
     * Capitaliza la primera letra de una cadena
     * @param {string} str - Cadena a capitalizar
     * @returns {string} - Cadena capitalizada
     */
    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    /**
     * ===============================================
     * SECCIÓN 3: FUNCIONES AJAX
     * ===============================================
     */

    /**
     * Realiza búsqueda de usuarios usando Fetch API
     * @param {string} termino - Término de búsqueda
     * @param {number} pagina - Página a solicitar
     */
    function buscarUsuarios(termino, pagina = 1) {
        if (buscandoActualmente) return;

        buscandoActualmente = true;
        mostrarLoading(true);

        const formData = new FormData();
        formData.append('accion', 'buscar');
        formData.append('termino', termino);
        formData.append('pagina', pagina);

        fetch(CONFIG.endpoints.buscar, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Error del servidor: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    actualizarTablaUsuarios(data.usuarios);
                    actualizarPaginacion(data);
                    actualizarInfoResultados(data);
                } else {
                    mostrarError('Error al buscar usuarios: ' + (data.error || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error en la búsqueda:', error);
                mostrarError('Error al buscar usuarios. Verifique su conexión e intente nuevamente.');
            })
            .finally(() => {
                buscandoActualmente = false;
                mostrarLoading(false);
            });
    }

    /**
     * ===============================================
     * SECCIÓN 4: FUNCIONES DE ACTUALIZACIÓN DE UI
     * ===============================================
     */

    /**
     * Actualiza la tabla de usuarios con nuevos datos
     * @param {Array} usuarios - Lista de usuarios
     */
    function actualizarTablaUsuarios(usuarios) {
        let html = '';

        if (usuarios.length > 0) {
            html = `
                <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <thead class="table-primary">
                            <tr>
                                <th scope="col"><i class="fas fa-user me-1"></i>Usuario</th>
                                <th scope="col"><i class="fas fa-envelope me-1"></i>Email</th>
                                <th scope="col"><i class="fas fa-user-tag me-1"></i>Rol</th>
                                <th scope="col"><i class="fas fa-cogs me-1"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
            `;

            usuarios.forEach(function(usuario) {
                const rolClass = getRolBadgeClass(usuario.rol);
                const rolCapitalized = capitalize(usuario.rol);

                html += `
                    <tr class="user-row">
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="user-avatar me-3">
                                    <i class="fas fa-user-circle text-primary fs-4"></i>
                                </div>
                                <div>
                                    <strong class="d-block">${escapeHtml(usuario.nombre_usuario)}</strong>
                                    <small class="text-muted">ID: ${usuario.id}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope text-muted me-2"></i>
                                <span>${escapeHtml(usuario.email)}</span>
                            </div>
                        </td>
                        <td>
                            <span class="badge ${rolClass} px-3 py-2">
                                <i class="fas fa-shield-alt me-1"></i>
                                ${escapeHtml(rolCapitalized)}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="../Controlador/ControladorUsuario.php?accion=cargar&id=${usuario.id}"
                                   class="btn btn-sm btn-outline-primary"
                                   title="Editar usuario">
                                    <i class="fas fa-edit me-1"></i>
                                    Editar
                                </a>
                            </div>
                        </td>
                    </tr>
                `;
            });

            html += '</tbody></table></div>';
        } else {
            const mensajeBusqueda = terminoBusqueda ?
                `No hay usuarios que coincidan con la búsqueda "${escapeHtml(terminoBusqueda)}".` :
                'No se han encontrado usuarios en el sistema.';

            html = `
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-info-circle me-2 fs-4"></i>
                    <div>
                        <h5 class="mb-1">No se encontraron usuarios</h5>
                        <p class="mb-0">${mensajeBusqueda}</p>
                    </div>
                </div>
            `;
        }

        $tablaUsuarios.html(html);
    }

    /**
     * Actualiza la paginación con nuevos datos
     * @param {Object} response - Respuesta del servidor con datos de paginación
     */
    function actualizarPaginacion(response) {
        let html = '';

        if (response.total_paginas > 1) {
            html = '<ul class="pagination justify-content-center">';

            // Botón Primera página
            const primeraPaginaDisabled = response.pagina_actual <= 1 ? 'disabled' : '';
            html += `
                <li class="page-item ${primeraPaginaDisabled}">
                    <a class="page-link" href="#" data-pagina="1" title="Primera página">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                </li>
            `;

            // Botón Anterior
            const anteriorDisabled = response.pagina_actual <= 1 ? 'disabled' : '';
            html += `
                <li class="page-item ${anteriorDisabled}">
                    <a class="page-link" href="#" data-pagina="${response.pagina_actual - 1}" title="Página anterior">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </a>
                </li>
            `;

            // Calcular rango de páginas a mostrar
            let inicio = Math.max(1, response.pagina_actual - 2);
            let fin = Math.min(response.total_paginas, response.pagina_actual + 2);

            // Mostrar primera página si no está en el rango
            if (inicio > 1) {
                html += `<li class="page-item"><a class="page-link" href="#" data-pagina="1">1</a></li>`;
                if (inicio > 2) {
                    html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            // Páginas del rango
            for (let i = inicio; i <= fin; i++) {
                const activeClass = i == response.pagina_actual ? 'active' : '';
                html += `
                    <li class="page-item ${activeClass}">
                        <a class="page-link" href="#" data-pagina="${i}" title="Página ${i}">${i}</a>
                    </li>
                `;
            }

            // Mostrar última página si no está en el rango
            if (fin < response.total_paginas) {
                if (fin < response.total_paginas - 1) {
                    html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                html += `<li class="page-item"><a class="page-link" href="#" data-pagina="${response.total_paginas}">${response.total_paginas}</a></li>`;
            }

            // Botón Siguiente
            const siguienteDisabled = response.pagina_actual >= response.total_paginas ? 'disabled' : '';
            html += `
                <li class="page-item ${siguienteDisabled}">
                    <a class="page-link" href="#" data-pagina="${response.pagina_actual + 1}" title="Página siguiente">
                        Siguiente <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `;

            // Botón Última página
            const ultimaPaginaDisabled = response.pagina_actual >= response.total_paginas ? 'disabled' : '';
            html += `
                <li class="page-item ${ultimaPaginaDisabled}">
                    <a class="page-link" href="#" data-pagina="${response.total_paginas}" title="Última página">
                        <i class="fas fa-angle-double-right"></i>
                    </a>
                </li>
            `;

            html += '</ul>';

            // Información adicional de paginación
            html += `
                <div class="text-center mt-3">
                    <small class="text-muted">
                        Página ${response.pagina_actual} de ${response.total_paginas}
                        (${CONFIG.usuariosPorPagina} usuarios por página)
                    </small>
                </div>
            `;
        }

        $paginacionContainer.html(html);
    }

    /**
     * Actualiza la información de resultados
     * @param {Object} response - Respuesta del servidor
     */
    function actualizarInfoResultados(response) {
        const inicio = (response.pagina_actual - 1) * CONFIG.usuariosPorPagina + 1;
        const fin = Math.min(response.pagina_actual * CONFIG.usuariosPorPagina, response.total);

        $infoResultados.html(`
            <small class="text-muted">
                Mostrando ${inicio}-${fin} de ${response.total} usuarios
            </small>
        `);
    }

    /**
     * Muestra un mensaje de error
     * @param {string} mensaje - Mensaje de error a mostrar
     */
    function mostrarError(mensaje) {
        $tablaUsuarios.html(`
            <div class="alert alert-danger d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2 fs-4"></i>
                <div>
                    <h5 class="mb-1">Error</h5>
                    <p class="mb-0">${escapeHtml(mensaje)}</p>
                </div>
            </div>
        `);
    }

    /**
     * ===============================================
     * SECCIÓN 5: MANEJADORES DE EVENTOS
     * ===============================================
     */

    /**
     * Búsqueda en tiempo real con debouncing
     */
    $buscarInput.on('input', function() {
        clearTimeout(timeoutId);
        const termino = $(this).val().trim();

        // Si está vacío, recargar la página original
        if (termino === '') {
            window.location.href = `${CONFIG.endpoints.listar}&pagina=1`;
            return;
        }

        // Debounce: esperar antes de buscar
        timeoutId = setTimeout(function() {
            terminoBusqueda = termino;
            paginaBusqueda = 1;
            buscarUsuarios(termino, 1);
        }, CONFIG.debounceDelay);
    });

    /**
     * Botón de búsqueda manual
     */
    $buscarBtn.on('click', function() {
        const termino = $buscarInput.val().trim();
        terminoBusqueda = termino;
        paginaBusqueda = 1;
        buscarUsuarios(termino, 1);
    });

    /**
     * Botón para limpiar búsqueda
     */
    $limpiarBtn.on('click', function() {
        $buscarInput.val('');
        terminoBusqueda = '';
        window.location.href = `${CONFIG.endpoints.listar}&pagina=1`;
    });

    /**
     * Botón para recargar la lista
     */
    $recargarBtn.on('click', function() {
        if (terminoBusqueda !== '') {
            buscarUsuarios(terminoBusqueda, paginaBusqueda);
        } else {
            window.location.reload();
        }
    });

    /**
     * Manejador de paginación AJAX
     */
    $(document).on('click', '#paginacionContainer .page-link[data-pagina]', function(e) {
        e.preventDefault();
        const pagina = parseInt($(this).data('pagina'));

        if (pagina && pagina > 0) {
            if (terminoBusqueda !== '') {
                paginaBusqueda = pagina;
                buscarUsuarios(terminoBusqueda, pagina);
            } else {
                window.location.href = `${CONFIG.endpoints.listar}&pagina=${pagina}`;
            }
        }
    });

    /**
     * Búsqueda con tecla Enter
     */
    $buscarInput.on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            $buscarBtn.click();
        }
    });

    /**
     * Efectos de hover mejorados para las filas de usuarios
     */
    $(document).on('mouseenter', '.user-row', function() {
        $(this).addClass('table-active');
    }).on('mouseleave', '.user-row', function() {
        $(this).removeClass('table-active');
    });

    /**
     * ===============================================
     * SECCIÓN 6: FUNCIONES DE INICIALIZACIÓN
     * ===============================================
     */

    /**
     * Inicializa la funcionalidad de la lista de usuarios
     */
    function inicializarLista() {
        try {
            // Auto-focus en el campo de búsqueda
            $buscarInput.focus();

            // Configurar animaciones de entrada
            $tablaUsuarios.find('.user-row').each(function(index) {
                $(this).css('opacity', '0').delay(index * 50).animate({ opacity: 1 }, 300);
            });

            // Log para debugging (remover en producción)
            console.log('Lista de usuarios inicializada correctamente');
            console.log('Configuración:', CONFIG);

        } catch (error) {
            console.error('Error al inicializar la lista de usuarios:', error);
        }
    }

    /**
     * ===============================================
     * SECCIÓN 7: FUNCIONES AUXILIARES AVANZADAS
     * ===============================================
     */


    function exportarAPDF() {
        // Obtener el término de búsqueda actual
        const termino = $buscarInput.val().trim();

        // Construir la URL del generador de PDF con el término de búsqueda
        let url = '../Controlador/generarPdfUsuarios.php';

        // Agregar el término de búsqueda como parámetro si existe
        if (termino !== '') {
            url += '?termino=' + encodeURIComponent(termino);
        }

        // Redirigir a la URL (descargará el PDF)
        window.location.href = url;
    }

// Agregar manejador de eventos al botón de imprimir
    $(document).on('click', '#btnExportarPDF', function(e) {
        e.preventDefault();
        exportarAPDF();
    });


    /**
     * Detecta el estado de la búsqueda actual
     * @returns {Object} - Estado de la búsqueda
     */
    function getEstadoBusqueda() {
        return {
            terminoActual: terminoBusqueda,
            paginaActual: paginaBusqueda,
            buscando: buscandoActualmente
        };
    }

    /**
     * Limpia todos los timeouts y estados
     */
    function limpiarEstado() {
        clearTimeout(timeoutId);
        buscandoActualmente = false;
        mostrarLoading(false);
    }

    /**
     * Manejo de errores de red específicos
     */
    $(window).on('offline', function() {
        mostrarError('Conexión perdida. Verifique su conexión a internet.');
    });

    $(window).on('online', function() {
        // Recargar datos cuando se restablezca la conexión
        if (terminoBusqueda !== '') {
            buscarUsuarios(terminoBusqueda, paginaBusqueda);
        }
    });

    /**
     * ===============================================
     * SECCIÓN 9: EJECUCIÓN DE INICIALIZACIÓN
     * ===============================================
     */

    // Ejecutar inicialización cuando el DOM esté completamente listo
    inicializarLista();


});