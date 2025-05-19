<?php
include '../Compartido/header.php';
use Entidades\Rol;
use Entidades\Usuario;
require '../Entidades/Rol.php';
require '../Entidades/Usuario.php';
$usuario = unserialize($_SESSION['usuarioUpdate']);
?>

<?php if ($usuario instanceof Usuario): ?>
    <body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        <h2 class="card-title mb-0">
                            <i class="fas fa-user-edit me-2"></i>
                            Actualizar Usuario
                        </h2>
                    </div>

                    <div class="card-body p-4">
                        <form id="form-actualizar-usuario" action="../Controlador/ControladorUsuario.php" method="post">
                            <!-- Campos ocultos -->
                            <input type="hidden" name="accion" value="actualizar">
                            <input type="hidden" name="usuario[id]" value="<?= $usuario->getId(); ?>">

                            <!-- Información del usuario -->
                            <div class="card border-0 shadow-sm">
                                <div class="card-body p-3 p-md-4">
                                    <!-- Usuario -->
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="me-3">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1 min-w-0">
                                            <h5 class="mb-1 text-truncate">
                                                <?= htmlspecialchars($usuario->getNombreUsuario()); ?>
                                            </h5>
                                            <span class="badge bg-light text-dark">
                                                ID: #<?= $usuario->getId(); ?>
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Rol -->
                                    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                        <div class="d-flex align-items-center">
                                            <?php if ($usuario->getRol()->name == 'jefe'): ?>
                                                <span class="badge bg-danger">
                                                    <i class="fas fa-crown me-1"></i> Jefe
                                                </span>
                                            <?php elseif ($usuario->getRol()->name == 'administrador'): ?>
                                                <span class="badge bg-warning">
                                                    <i class="fas fa-user-cog me-1"></i> Administrador
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-user me-1"></i> Visitante
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <span class="badge bg-success">
                                            <i class="fas fa-circle me-1" style="font-size: 0.5rem;"></i>
                                            Activo
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Campo Nombre de Usuario -->
                            <div class="mb-4">
                                <label for="nombre_usuario" class="form-label fw-semibold">
                                    <i class="fas fa-at me-1"></i>
                                    Nombre de Usuario
                                </label>
                                <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-user-tag"></i>
                                        </span>
                                    <input type="text"
                                           id="nombre_usuario"
                                           name="usuario[nombre_usuario]"
                                           class="form-control form-control-lg"
                                           value="<?= htmlspecialchars($usuario->getNombreUsuario()); ?>"
                                           placeholder="Ingrese el nombre de usuario..."
                                           required
                                           autocomplete="username">
                                </div>
                                <div id="nombre-usuario-feedback" class="invalid-feedback"></div>

                            </div>

                            <!-- Campo Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label fw-semibold">
                                    <i class="fas fa-envelope me-1"></i>
                                    Correo Electrónico
                                </label>
                                <div class="input-group">
                                        <span class="input-group-text">
                                            <i class="fas fa-at"></i>
                                        </span>
                                    <input type="email"
                                           id="email"
                                           name="usuario[email]"
                                           class="form-control form-control-lg"
                                           value="<?= htmlspecialchars($usuario->getEmail()); ?>"
                                           placeholder="usuario@dominio.com"
                                           required
                                           autocomplete="email">
                                </div>
                                <div id="email-feedback" class="invalid-feedback"></div>

                            </div>

                            <!-- Campo Contraseña (Solo lectura) -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-lock me-1"></i>
                                    Contraseña Actual
                                </label>
                                <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-key"></i>
                                        </span>
                                    <div class="form-control bg-light d-flex align-items-center">
                                            <span class="text-muted font-monospace" style="letter-spacing: 2px;">
                                                <?= str_repeat('•', min(strlen($usuario->getContra()), 12)); ?>
                                            </span>
                                        <span class="badge bg-info ms-auto">
                                                <i class="fas fa-eye-slash me-1"></i>
                                                Oculta
                                            </span>
                                    </div>
                                    <input type="hidden" name="usuario[contra]" value="<?= htmlspecialchars($usuario->getContra()); ?>">
                                </div>

                            </div>

                            <!-- Campo Rol -->
                            <?php if ($usuario->getRol()->name != 'jefe'): ?>
                                <div class="mb-4">
                                    <label for="rol" class="form-label fw-semibold">
                                        <i class="fas fa-user-shield me-1"></i>
                                        Rol del Usuario
                                    </label>
                                    <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="fas fa-list"></i>
                                            </span>
                                        <select id="rol"
                                                name="usuario[rol]"
                                                class="form-select form-select-lg"
                                                required>
                                            <option value="">Seleccione un rol</option>
                                            <option value="administrador" <?= ($usuario->getRol()->name == 'administrador') ? 'selected' : ''; ?>>
                                                <i class="fas fa-user-cog me-1"></i>
                                                Administrador
                                            </option>
                                            <option value="visitante" <?= ($usuario->getRol()->name == 'visitante') ? 'selected' : ''; ?>>
                                                <i class="fas fa-user me-1"></i>
                                                Visitante
                                            </option>
                                        </select>
                                    </div>
                                    <div id="rol-feedback" class="invalid-feedback"></div>

                                </div>
                            <?php else: ?>
                                <input type="hidden" name="usuario[rol]" value="<?= $usuario->getRol()->name; ?>">
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user-shield me-1"></i>
                                        Rol del Usuario
                                    </label>
                                    <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-crown text-warning"></i>
                                            </span>
                                        <div class="form-control bg-light d-flex align-items-center">
                                                <span class="badge bg-danger me-2">
                                                    <i class="fas fa-crown me-1"></i>
                                                    Jefe
                                                </span>
                                            <small class="text-muted">
                                                <i class="fas fa-lock me-1"></i>
                                                No modificable
                                            </small>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        El rol de Jefe tiene permisos especiales y no puede ser modificado
                                    </small>
                                </div>
                            <?php endif; ?>



                            <!-- Botones de Acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-center mt-5">
                                <button type="button"
                                        id="btn-cancelar"
                                        class="btn btn-outline-secondary btn-lg me-md-2">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Cancelar
                                </button>
                                <button type="submit"
                                        id="btn-actualizar"
                                        class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>
                                    Actualizar Usuario
                                </button>
                            </div>
                        </form>
                    </div>


                </div>
            </div>
        </div>
    </div>

    <!-- Incluir archivo JavaScript separado -->
    <script src="../js/CargarUsuario.js"></script>
    </body>
<?php else: ?>
    <body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-danger">
                    <div class="card-header bg-danger text-white text-center">
                        <h3 class="card-title mb-0">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Error de Usuario
                        </h3>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-3">
                            <i class="fas fa-user-times text-danger" style="font-size: 4rem;"></i>
                        </div>
                        <h4 class="text-danger">Usuario no válido</h4>
                        <p class="text-muted">No se pudo cargar la información del usuario.</p>
                        <a href="../Controlador/ControladorUsuario.php" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Volver a la lista
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </body>
<?php endif; ?>

<?php include '../Compartido/footer.php'; ?>