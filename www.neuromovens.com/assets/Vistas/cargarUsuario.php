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
                            <div class="row mb-4">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">
                                        <i class="fas fa-user me-1"></i>
                                        Usuario Actual
                                    </label>
                                    <div class="d-flex align-items-center">
                                        <div class="me-3">
                                            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-white fs-4"></i>
                                            </div>
                                        </div>
                                        <div>
                                            <h5 class="mb-1"><?= htmlspecialchars($usuario->getNombreUsuario()); ?></h5>
                                            <small class="text-muted">ID: #<?= $usuario->getId(); ?></small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold text-muted">
                                        <i class="fas fa-shield-alt me-1"></i>
                                        Rol Actual
                                    </label>
                                    <div class="mt-2">
                                        <?php if ($usuario->getRol()->name == 'jefe'): ?>
                                            <span class="badge bg-danger fs-6">
                                                    <i class="fas fa-crown me-1"></i>
                                                    Jefe
                                                </span>
                                        <?php elseif ($usuario->getRol()->name == 'administrador'): ?>
                                            <span class="badge bg-warning fs-6">
                                                    <i class="fas fa-user-cog me-1"></i>
                                                    Administrador
                                                </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary fs-6">
                                                    <i class="fas fa-user me-1"></i>
                                                    Visitante
                                                </span>
                                        <?php endif; ?>
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
                                <div class="d-flex justify-content-between mt-2">
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Solo letras, números y guiones bajos
                                    </small>
                                    <small id="nombre-usuario-contador" class="form-text text-muted fw-bold">
                                        0/30 caracteres
                                    </small>
                                </div>
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
                                <small id="email-helper" class="form-text text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Formato: usuario@dominio.com
                                </small>
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
                                <small class="form-text text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    La contraseña no se puede modificar desde este formulario
                                </small>
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
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Seleccione el nivel de acceso para este usuario
                                    </small>
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

                            <!-- Fecha de Modificación -->
                            <div class="mb-4">
                                <label class="form-label fw-semibold">
                                    <i class="fas fa-clock me-1"></i>
                                    Última Modificación
                                </label>
                                <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-calendar-alt"></i>
                                        </span>
                                    <div id="fecha-modificacion" class="form-control bg-light text-muted">
                                        <i class="fas fa-spinner fa-spin me-2"></i>
                                        Cargando fecha...
                                    </div>
                                </div>
                                <small class="form-text text-muted">
                                    <i class="fas fa-lightbulb me-1"></i>
                                    Fecha automática de última actualización
                                </small>
                            </div>

                            <!-- Botones de Acción -->
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-5">
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

                    <!-- Footer del Card -->
                    <div class="card-footer bg-light text-center text-muted">
                        <small>
                            <i class="fas fa-shield-alt me-1"></i>
                            Los cambios se aplicarán inmediatamente
                            <span class="mx-2">•</span>
                            <i class="fas fa-user-check me-1"></i>
                            Usuario verificado y validado
                        </small>
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