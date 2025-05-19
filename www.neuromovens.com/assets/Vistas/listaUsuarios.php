<?php
require '../Entidades/Usuario.php';
include '../Compartido/header.php';

// Obtener datos de la sesión
$datosPaginados = isset($_SESSION['usuarios_paginados']) ? unserialize($_SESSION['usuarios_paginados']) : null;
$usuarios = $datosPaginados['usuarios'] ?? [];
$paginaActual = $datosPaginados['pagina_actual'] ?? 1;
$totalPaginas = $datosPaginados['total_paginas'] ?? 1;
$total = $datosPaginados['total'] ?? 0;
$porPagina = $datosPaginados['por_pagina'] ?? 10;
?>

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Lista de Usuarios</h2>

                <!-- Buscador -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <label for="buscarUsuario" class="form-label">
                                    <i class="fas fa-search me-1"></i>Buscar usuarios:
                                </label>
                                <input type="text"
                                       id="buscarUsuario"
                                       class="form-control"
                                       placeholder="Buscar por nombre de usuario o email..."
                                       autocomplete="off">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button id="limpiarBusqueda" class="btn btn-outline-secondary me-2">
                                    <i class="fas fa-times"></i> Limpiar
                                </button>
                                <button id="buscarBtn" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Información de resultados y controles -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div id="infoResultados">
                        <small class="text-muted">
                            <?php
                            $inicio = ($paginaActual - 1) * $porPagina + 1;
                            $fin = min($paginaActual * $porPagina, $total);
                            ?>
                            Mostrando <?php echo $inicio; ?>-<?php echo $fin; ?> de <?php echo $total; ?> usuarios
                        </small>
                    </div>
                    <div>
                        <button id="recargarLista" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-sync-alt"></i> Recargar
                        </button>
                    </div>
                </div>

                <!-- Loading indicator -->
                <div id="loadingIndicator" class="text-center mb-3" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-muted">Buscando usuarios...</p>
                </div>

                <!-- Tabla de usuarios -->
                <div id="tablaUsuarios" class="table-container">
                    <?php if (!empty($usuarios)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead class="table-primary">
                                <tr>
                                    <th scope="col">
                                        <i class="fas fa-user me-1"></i>Usuario
                                    </th>
                                    <th scope="col">
                                        <i class="fas fa-envelope me-1"></i>Email
                                    </th>
                                    <th scope="col">
                                        <i class="fas fa-user-tag me-1"></i>Rol
                                    </th>
                                    <th scope="col">
                                        <i class="fas fa-cogs me-1"></i>Acciones
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php foreach ($usuarios as $usuario): ?>
                                    <tr class="user-row">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="user-avatar me-3">
                                                    <i class="fas fa-user-circle text-primary fs-4"></i>
                                                </div>
                                                <div>
                                                    <strong class="d-block"><?php echo htmlspecialchars($usuario->getNombreUsuario()); ?></strong>
                                                    <small class="text-muted">ID: <?php echo $usuario->getId(); ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-envelope text-muted me-2"></i>
                                                <span><?php echo htmlspecialchars($usuario->getEmail()); ?></span>
                                            </div>
                                        </td>
                                        <td>
                                            <?php
                                            $rolClass = match($usuario->getRol()->name) {
                                                'jefe' => 'bg-danger',
                                                'empleado' => 'bg-warning text-dark',
                                                'visitante' => 'bg-secondary',
                                                default => 'bg-primary'
                                            };
                                            ?>
                                            <span class="badge <?php echo $rolClass; ?> px-3 py-2">
                                            <i class="fas fa-shield-alt me-1"></i>
                                            <?php echo ucfirst($usuario->getRol()->name); ?>
                                        </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="../Controlador/ControladorUsuario.php?accion=cargar&id=<?php echo $usuario->getId(); ?>"
                                                   class="btn btn-sm btn-outline-primary"
                                                   title="Editar usuario">
                                                    <i class="fas fa-edit me-1"></i>
                                                    Editar
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info d-flex align-items-center" id="alertaVacia">
                            <i class="fas fa-info-circle me-2 fs-4"></i>
                            <div>
                                <h5 class="mb-1">No hay usuarios disponibles</h5>
                                <p class="mb-0">No se han encontrado usuarios en el sistema.</p>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Paginación -->
                <nav aria-label="Paginación de usuarios" id="paginacionContainer" class="mt-4">
                    <?php if ($totalPaginas > 1): ?>
                        <ul class="pagination justify-content-center">
                            <!-- Botón Primera página -->
                            <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                   href="<?php echo ($paginaActual > 1) ? '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=1' : '#'; ?>"
                                   title="Primera página">
                                    <i class="fas fa-angle-double-left"></i>
                                </a>
                            </li>

                            <!-- Botón Anterior -->
                            <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                   href="<?php echo ($paginaActual > 1) ? '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=' . ($paginaActual - 1) : '#'; ?>"
                                   title="Página anterior">
                                    <i class="fas fa-chevron-left"></i> Anterior
                                </a>
                            </li>

                            <!-- Números de página -->
                            <?php
                            // Calcular rango de páginas a mostrar
                            $inicio = max(1, $paginaActual - 2);
                            $fin = min($totalPaginas, $paginaActual + 2);

                            // Mostrar primera página si no está en el rango
                            if ($inicio > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=1">1</a>
                                </li>
                                <?php if ($inicio > 2): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                            <?php endif; ?>

                            <!-- Páginas del rango -->
                            <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                                <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                                    <a class="page-link"
                                       href="<?php echo ($i == $paginaActual) ? '#' : '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=' . $i; ?>"
                                       title="Página <?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <!-- Mostrar última página si no está en el rango -->
                            <?php if ($fin < $totalPaginas): ?>
                                <?php if ($fin < $totalPaginas - 1): ?>
                                    <li class="page-item disabled">
                                        <span class="page-link">...</span>
                                    </li>
                                <?php endif; ?>
                                <li class="page-item">
                                    <a class="page-link" href="../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=<?php echo $totalPaginas; ?>">
                                        <?php echo $totalPaginas; ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <!-- Botón Siguiente -->
                            <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                   href="<?php echo ($paginaActual < $totalPaginas) ? '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=' . ($paginaActual + 1) : '#'; ?>"
                                   title="Página siguiente">
                                    Siguiente <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>

                            <!-- Botón Última página -->
                            <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                                <a class="page-link"
                                   href="<?php echo ($paginaActual < $totalPaginas) ? '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=' . $totalPaginas : '#'; ?>"
                                   title="Última página">
                                    <i class="fas fa-angle-double-right"></i>
                                </a>
                            </li>
                        </ul>

                        <!-- Información adicional de paginación -->
                        <div class="text-center mt-3">
                            <small class="text-muted">
                                Página <?php echo $paginaActual; ?> de <?php echo $totalPaginas; ?>
                                (<?php echo $porPagina; ?> usuarios por página)
                            </small>
                        </div>
                    <?php endif; ?>
                </nav>

                <!-- Botones de acción -->
                <div class="mt-4 d-flex flex-wrap gap-2">
                    <a href="../Controlador/ControladorUsuario.php?accion=crear" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>
                        Añadir Usuario
                    </a>
                    <button onclick="window.print()" class="btn btn-outline-secondary">
                        <i class="fas fa-print me-2"></i>
                        Imprimir Lista
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            let timeoutId;
            let paginaBusqueda = 1;
            let terminoBusqueda = '';
            let buscandoActualmente = false;

            // Función para mostrar/ocultar loading
            function mostrarLoading(mostrar) {
                if (mostrar) {
                    $('#loadingIndicator').show();
                    $('#tablaUsuarios').css('opacity', '0.5');
                    $('#paginacionContainer').css('opacity', '0.5');
                } else {
                    $('#loadingIndicator').hide();
                    $('#tablaUsuarios').css('opacity', '1');
                    $('#paginacionContainer').css('opacity', '1');
                }
            }

            // Función para realizar búsqueda AJAX con fetch
            function buscarUsuarios(termino, pagina = 1) {
                if (buscandoActualmente) return;

                buscandoActualmente = true;
                mostrarLoading(true);

                const formData = new FormData();
                formData.append('accion', 'buscar');
                formData.append('termino', termino);
                formData.append('pagina', pagina);

                fetch('../Controlador/ControladorUsuario.php', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Error en la respuesta del servidor');
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            actualizarTablaUsuarios(data.usuarios);
                            actualizarPaginacion(data);
                            actualizarInfoResultados(data);
                        } else {
                            mostrarError('Error al buscar usuarios: ' + data.error);
                        }
                    })
                    .catch(error => {
                        console.error('Error en la búsqueda:', error);
                        mostrarError('Error al buscar usuarios. Intente nuevamente.');
                    })
                    .finally(() => {
                        buscandoActualmente = false;
                        mostrarLoading(false);
                    });
            }

            // Función para actualizar la tabla de usuarios
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
                        let rolClass = 'bg-primary';
                        switch(usuario.rol) {
                            case 'jefe': rolClass = 'bg-danger'; break;
                            case 'empleado': rolClass = 'bg-warning text-dark'; break;
                            case 'visitante': rolClass = 'bg-secondary'; break;
                        }

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
                                ${escapeHtml(usuario.rol.charAt(0).toUpperCase() + usuario.rol.slice(1))}
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
                    html = `
                <div class="alert alert-info d-flex align-items-center">
                    <i class="fas fa-info-circle me-2 fs-4"></i>
                    <div>
                        <h5 class="mb-1">No se encontraron usuarios</h5>
                        <p class="mb-0">No hay usuarios que coincidan con la búsqueda "${escapeHtml(terminoBusqueda)}".</p>
                    </div>
                </div>
            `;
                }

                $('#tablaUsuarios').html(html);
            }

            // Función para actualizar la paginación
            function actualizarPaginacion(response) {
                let html = '';

                if (response.total_paginas > 1) {
                    html = '<ul class="pagination justify-content-center">';

                    // Botón Primera página
                    html += `
                <li class="page-item ${response.pagina_actual <= 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-pagina="1" title="Primera página">
                        <i class="fas fa-angle-double-left"></i>
                    </a>
                </li>
            `;

                    // Botón Anterior
                    html += `
                <li class="page-item ${response.pagina_actual <= 1 ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-pagina="${response.pagina_actual - 1}" title="Página anterior">
                        <i class="fas fa-chevron-left"></i> Anterior
                    </a>
                </li>
            `;

                    // Números de página
                    let inicio = Math.max(1, response.pagina_actual - 2);
                    let fin = Math.min(response.total_paginas, response.pagina_actual + 2);

                    // Primera página
                    if (inicio > 1) {
                        html += `<li class="page-item"><a class="page-link" href="#" data-pagina="1">1</a></li>`;
                        if (inicio > 2) {
                            html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                    }

                    // Páginas del rango
                    for (let i = inicio; i <= fin; i++) {
                        html += `
                    <li class="page-item ${i == response.pagina_actual ? 'active' : ''}">
                        <a class="page-link" href="#" data-pagina="${i}" title="Página ${i}">${i}</a>
                    </li>
                `;
                    }

                    // Última página
                    if (fin < response.total_paginas) {
                        if (fin < response.total_paginas - 1) {
                            html += '<li class="page-item disabled"><span class="page-link">...</span></li>';
                        }
                        html += `<li class="page-item"><a class="page-link" href="#" data-pagina="${response.total_paginas}">${response.total_paginas}</a></li>`;
                    }

                    // Botón Siguiente
                    html += `
                <li class="page-item ${response.pagina_actual >= response.total_paginas ? 'disabled' : ''}">
                    <a class="page-link" href="#" data-pagina="${response.pagina_actual + 1}" title="Página siguiente">
                        Siguiente <i class="fas fa-chevron-right"></i>
                    </a>
                </li>
            `;

                    // Botón Última página
                    html += `
                <li class="page-item ${response.pagina_actual >= response.total_paginas ? 'disabled' : ''}">
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
                        (10 usuarios por página)
                    </small>
                </div>
            `;
                }

                $('#paginacionContainer').html(html);
            }

            // Función para actualizar información de resultados
            function actualizarInfoResultados(response) {
                const inicio = (response.pagina_actual - 1) * 10 + 1;
                const fin = Math.min(response.pagina_actual * 10, response.total);

                $('#infoResultados').html(`
            <small class="text-muted">
                Mostrando ${inicio}-${fin} de ${response.total} usuarios
            </small>
        `);
            }

            // Función para mostrar errores
            function mostrarError(mensaje) {
                $('#tablaUsuarios').html(`
            <div class="alert alert-danger d-flex align-items-center">
                <i class="fas fa-exclamation-triangle me-2 fs-4"></i>
                <div>
                    <h5 class="mb-1">Error</h5>
                    <p class="mb-0">${mensaje}</p>
                </div>
            </div>
        `);
            }

            // Función para escapar HTML
            function escapeHtml(text) {
                const div = document.createElement('div');
                div.textContent = text;
                return div.innerHTML;
            }

            // Event listener para búsqueda en tiempo real
            $('#buscarUsuario').on('input', function() {
                clearTimeout(timeoutId);
                const termino = $(this).val().trim();

                // Si está vacío, recargar la página original
                if (termino === '') {
                    window.location.href = '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=1';
                    return;
                }

                // Debounce: esperar 500ms antes de buscar
                timeoutId = setTimeout(function() {
                    terminoBusqueda = termino;
                    paginaBusqueda = 1;
                    buscarUsuarios(termino, 1);
                }, 500);
            });

            // Event listener para botón de búsqueda
            $('#buscarBtn').on('click', function() {
                const termino = $('#buscarUsuario').val().trim();
                terminoBusqueda = termino;
                paginaBusqueda = 1;
                buscarUsuarios(termino, 1);
            });

            // Event listener para limpiar búsqueda
            $('#limpiarBusqueda').on('click', function() {
                $('#buscarUsuario').val('');
                terminoBusqueda = '';
                window.location.href = '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=1';
            });

            // Event listener para recargar lista
            $('#recargarLista').on('click', function() {
                if (terminoBusqueda !== '') {
                    buscarUsuarios(terminoBusqueda, paginaBusqueda);
                } else {
                    window.location.reload();
                }
            });

            // Event listener para paginación AJAX
            $(document).on('click', '#paginacionContainer .page-link[data-pagina]', function(e) {
                e.preventDefault();
                const pagina = parseInt($(this).data('pagina'));

                if (pagina && pagina > 0) {
                    if (terminoBusqueda !== '') {
                        paginaBusqueda = pagina;
                        buscarUsuarios(terminoBusqueda, pagina);
                    } else {
                        window.location.href = `../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=${pagina}`;
                    }
                }
            });

            // Permitir búsqueda con Enter
            $('#buscarUsuario').on('keypress', function(e) {
                if (e.which === 13) { // Enter key
                    $('#buscarBtn').click();
                }
            });

            // Auto-focus en el campo de búsqueda
            $('#buscarUsuario').focus();

            // Efecto hover mejorado para las filas
            $(document).on('mouseenter', '.user-row', function() {
                $(this).addClass('table-active');
            }).on('mouseleave', '.user-row', function() {
                $(this).removeClass('table-active');
            });
        });
    </script>

<?php
include '../Compartido/footer.php';

?>