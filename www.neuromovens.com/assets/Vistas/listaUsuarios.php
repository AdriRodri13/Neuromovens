<?php

require '../Entidades/Usuario.php';

include '../Compartido/header.php';


$usuarios = unserialize($_SESSION['usuarios']);

?>
    <style>
        /* Estilos para las tarjetas de los usuarios */
        .usuario-tarjeta {
            display: flex;
            align-items: center;
            background-color: var(--color-blanco);
            border-radius: 8px;
            box-shadow: 0 2px 8px var(--color-sombra);
            margin: 16px 0;
            padding: 20px;
            transition: transform 0.3s ease-in-out;
        }

        .usuario-tarjeta:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px var(--color-sombra-intensa);
        }

        .usuario-info {
            flex: 1;
            padding-left: 20px;
        }

        .usuario-nombre {
            font-size: 20px;
            font-weight: bold;
            color: var(--color-oscuro);
            margin: 0;
        }

        .usuario-email,
        .usuario-contra,
        .usuario-rol {
            font-size: 14px;
            color: var(--color-gris-suave);
            margin: 5px 0;
        }

        .usuario-email {
            color: var(--color-principal);
        }

        .usuario-contra {
            color: var(--color-suave);
        }

        .usuario-rol {
            background-color: var(--color-secundario);
            color: var(--color-blanco);
            border-radius: 5px;
            padding: 3px 8px;
        }
    </style>
    <main>
        <?php if (!empty($usuarios)): ?>
            <?php foreach ($usuarios as $usuario): ?>
                <div class="usuario-tarjeta">
                    <div class="usuario-info">
                        <h3 class="usuario-nombre"><?php echo htmlspecialchars($usuario->getNombreUsuario()); ?></h3>
                        <p class="usuario-email"><?php echo htmlspecialchars($usuario->getEmail()); ?></p>
                        <p class="usuario-contra"><?php echo htmlspecialchars($usuario->getContra()); ?></p>
                        <p class="usuario-rol"><?php echo $usuario->getRol()->name; ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay usuarios disponibles.</p>
        <?php endif; ?>
    </main>

<?php


include '../Compartido/footer.php';