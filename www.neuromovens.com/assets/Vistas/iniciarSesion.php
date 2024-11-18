<?php include '../Compartido/header.php';?>

<style>
    /* Estilo General */
    body {
        background-color: var(--color-gris-claro);
        font-family: 'Etna', sans-serif;
    }

    /* Contenedor de Login */
    .login-container {
        max-width: 400px;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        background-color: var(--color-blanco);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .login-container:hover {
        transform: scale(1.02);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.25);
    }

    /* Encabezado de Login */
    .login-header {
        color: var(--color-principal);
        font-weight: bold;
        font-size: 1.7rem;
        text-align: center;
        margin-bottom: 1.5rem;
    }

    /* Estilos de los campos de entrada */
    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }
    .form-group label {
        font-weight: bold;
        color: var(--color-gris-oscuro);
    }
    .form-group .form-control {
        padding-left: 2.5rem;
    }
    .form-group i {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        color: var(--color-suave);
    }

    .form-control{
        padding: 1em;
    }

    .form-control:focus {
        border-color: var(--color-principal);
        box-shadow: 0 0 0 0.2rem rgba(255, 87, 34, 0.25);
    }

    /* Botón de inicio de sesión */
    .btn-login {
        background-color: var(--color-principal);
        color: var(--color-blanco);
        border: none;
        font-weight: bold;
        width: 100%;
        padding: 0.75rem;
        border-radius: 6px;
        transition: background-color 0.3s;
    }
    .btn-login:hover {
        background-color: var(--color-suave);
    }

    /* Mensaje de Error (oculto por defecto) */
    .error-message {
        display: none;
        color: red;
        text-align: center;
        font-size: 0.9rem;
        margin-top: 0.5rem;
    }
</style>

<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="login-container">
        <div class="login-header">Iniciar Sesión</div>

        <!-- Mensaje de error opcional -->
        <div class="error-message" id="errorMessage">Nombre de usuario o contraseña incorrectos</div>

        <form  action="../Controlador/ControladorUsuario.php" method="post">
            <input type="hidden" name="accion" value="iniciarSesion">
            <div class="form-group">
                <label for="username">Nombre de Usuario</label>
                <i class="fa-solid fa-user"></i> <!-- Ícono de usuario -->
                <input type="text" name="nombre_usuario" class="form-control" id="username" placeholder="Ingrese su usuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña</label>
                <i class="fa-solid fa-lock"></i> <!-- Ícono de candado -->
                <input type="password" name="contra" class="form-control" id="password" placeholder="Ingrese su contraseña" required>
            </div>
            <button type="submit" class="btn btn-login">Entrar</button>
        </form>
    </div>
</div>
<script src="../js/comprobacion.js"></script>



<?php include '../Compartido/footer.php'; ?>
