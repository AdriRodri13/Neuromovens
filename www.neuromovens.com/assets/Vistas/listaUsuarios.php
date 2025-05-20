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
    <h2 class="mb-4 text-center title">Lista de Usuarios</h2>

    <!-- Buscador -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row gy-3">
                <div class="col-12 col-md-8">
                    <label for="buscarUsuario" class="form-label">
                        <i class="fas fa-search me-1"></i>Buscar usuarios:
                    </label>
                    <input type="text"
                           id="buscarUsuario"
                           class="form-control"
                           placeholder="Buscar por nombre de usuario"
                           autocomplete="off">
                </div>
                <div class="col-12 col-md-4 d-flex gap-2 align-items-end">
                    <button id="limpiarBusqueda" class="btn btn-outline-secondary w-100 w-md-auto">
                        <i class="fas fa-times"></i> Limpiar
                    </button>
                    <button id="buscarBtn" class="btn btn-primary w-100 w-md-auto">
                        <i class="fas fa-search"></i> Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de resultados y controles -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-2 mb-3">
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
                <table class="table table-hover table-striped align-middle">
                    <thead class="table-primary">
                    <tr>
                        <th><i class="fas fa-user me-1"></i>Usuario</th>
                        <th><i class="fas fa-envelope me-1"></i>Email</th>
                        <th><i class="fas fa-user-tag me-1"></i>Rol</th>
                        <th><i class="fas fa-cogs me-1"></i>Acciones</th>
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
                                       class="btn btn-sm btn-outline-primary" title="Editar usuario">
                                        <i class="fas fa-edit me-1"></i>Editar
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
    <!-- Paginación Responsive -->
    <nav aria-label="Paginación de usuarios" id="paginacionContainer" class="mt-4">
        <?php if ($totalPaginas > 1): ?>
            <!-- Paginación para móviles (compacta) -->
            <div class="d-block d-md-none">
                <div class="d-flex justify-content-between align-items-center">
                    <!-- Botón Anterior -->
                    <button class="btn btn-outline-primary btn-sm <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>"
                            onclick="<?php echo ($paginaActual > 1) ? "window.location='../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=" . ($paginaActual - 1) . "'" : ''; ?>">
                        <i class="fas fa-chevron-left me-1"></i> Anterior
                    </button>

                    <!-- Indicador de página actual -->
                    <div class="text-center">
                        <span class="badge bg-primary fs-6"><?php echo $paginaActual; ?> / <?php echo $totalPaginas; ?></span>
                    </div>

                    <!-- Botón Siguiente -->
                    <button class="btn btn-outline-primary btn-sm <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>"
                            onclick="<?php echo ($paginaActual < $totalPaginas) ? "window.location='../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=" . ($paginaActual + 1) . "'" : ''; ?>">
                        Siguiente <i class="fas fa-chevron-right ms-1"></i>
                    </button>
                </div>

            </div>

            <!-- Paginación para tablets y desktop (completa) -->
            <div class="d-none d-md-block">
                <ul class="pagination justify-content-center gap-4 flex-wrap">
                    <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo ($paginaActual > 1) ? '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=1' : '#'; ?>" title="Primera página">
                            <i class="fas fa-angle-double-left"></i>
                        </a>
                    </li>

                    <li class="page-item <?php echo ($paginaActual <= 1) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo ($paginaActual > 1) ? '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=' . ($paginaActual - 1) : '#'; ?>" title="Anterior">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <?php
                    // En tablets, mostramos menos páginas que en desktop
                    $rangoMovil = 1; // Para tablets (md)
                    $rangoDesktop = 2; // Para desktop (lg+)

                    $inicio = max(1, $paginaActual - $rangoDesktop);
                    $fin = min($totalPaginas, $paginaActual + $rangoDesktop);

                    if ($inicio > 1): ?>
                        <li class="page-item"><a class="page-link" href="../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=1">1</a></li>
                        <?php if ($inicio > 2): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                    <?php endif; ?>

                    <?php for ($i = $inicio; $i <= $fin; $i++): ?>
                        <li class="page-item <?php echo ($i == $paginaActual) ? 'active' : ''; ?>">
                            <a class="page-link" href="<?php echo ($i == $paginaActual) ? '#' : '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=' . $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php endfor; ?>

                    <?php if ($fin < $totalPaginas): ?>
                        <?php if ($fin < $totalPaginas - 1): ?>
                            <li class="page-item disabled"><span class="page-link">...</span></li>
                        <?php endif; ?>
                        <li class="page-item"><a class="page-link" href="../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=<?php echo $totalPaginas; ?>"><?php echo $totalPaginas; ?></a></li>
                    <?php endif; ?>

                    <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo ($paginaActual < $totalPaginas) ? '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=' . ($paginaActual + 1) : '#'; ?>" title="Siguiente">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>

                    <li class="page-item <?php echo ($paginaActual >= $totalPaginas) ? 'disabled' : ''; ?>">
                        <a class="page-link" href="<?php echo ($paginaActual < $totalPaginas) ? '../Controlador/ControladorUsuario.php?accion=listar_paginado&pagina=' . $totalPaginas : '#'; ?>" title="Última página">
                            <i class="fas fa-angle-double-right"></i>
                        </a>
                    </li>
                </ul>


            </div>
        <?php endif; ?>
    </nav>

    <!-- Botones de acción -->
    <div class="mt-4 d-flex flex-column flex-md-row gap-2">
        <button onclick="window.print()" class="btn btn-outline-secondary">
            <i class="fas fa-print me-2"></i> Imprimir Lista
        </button>
    </div>
</div>

<script src="../js/listaUsuarios.js"></script>

<?php include '../Compartido/footer.php'; ?>
