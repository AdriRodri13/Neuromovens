<?php

require '../Entidades/Usuario.php';

include '../Compartido/header.php';


$usuarios = unserialize($_SESSION['usuarios']);

?>

    <div class="container py-4">
        <div class="row">
            <div class="col-12">
                <h2 class="mb-4">Lista de Usuarios</h2>

                <?php if (!empty($usuarios)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-primary">
                            <tr>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Acciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach ($usuarios as $usuario): ?>
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-user-circle text-primary me-2"></i>
                                            <strong><?php echo htmlspecialchars($usuario->getNombreUsuario()); ?></strong>
                                        </div>
                                    </td>
                                    <td>
                                        <i class="fas fa-envelope text-muted me-1"></i>
                                        <?php echo htmlspecialchars($usuario->getEmail()); ?>
                                    </td>
                                    <td>
                                            <span class="badge bg-danger">
                                                <?php echo $usuario->getRol()->name; ?>
                                            </span>
                                    </td>
                                    <td>
                                        <a href="../Controlador/ControladorUsuario.php?accion=cargar&id=<?php echo $usuario->getId(); ?>"
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit me-1"></i>
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        No hay usuarios disponibles.
                    </div>
                <?php endif; ?>

                <!-- Botón añadir -->
                <div class="mt-3">
                    <a href="../Controlador/ControladorUsuario.php?accion=crear" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>
                        Añadir Usuario
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Resetear conflictos con CSS general */
        .container, .table, .btn, .alert, h2 {
            font-family: system-ui, -apple-system, sans-serif !important;
        }

        main {
            text-align: initial !important;
        }

        h2 {
            color: #333 !important;
            font-size: 1.8rem !important;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }
    </style>

<?php

include '../Compartido/footer.php';