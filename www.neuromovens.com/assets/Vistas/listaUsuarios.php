<?php

require '../Entidades/Usuario.php';

include '../Compartido/header.php';


$usuarios = unserialize($_SESSION['usuarios']);

?>
    <style>
        main {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            width: 100%;
        }

        /* Estilos para las tarjetas de los usuarios */
        .usuario-tarjeta {
            display: grid;  /* Usamos grid en lugar de flexbox */
            grid-template-columns: 1fr 2fr 4fr 1fr;  /* Definimos las proporciones para las columnas */
            gap: 10px;  /* Espacio entre las celdas */
            background-color: var(--color-blanco);
            border-radius: 8px;
            box-shadow: 0 4px 8px var(--color-sombra);
            margin: 16px 0;
            padding: 20px;
            width: 80%;
            border: 1px solid #ddd; /* Bordes alrededor de la tarjeta */
        }

        /* Hover effect para las tarjetas */
        .usuario-tarjeta:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 16px var(--color-sombra-intensa);
        }

        /* Contenedor para la información del usuario */
        .usuario-info {
            display: contents;  /* El contenido se muestra como parte del grid sin añadir un nuevo contenedor */
        }

        /* Estilo para las celdas */
        .usuario-info div {
            padding: 10px;
            border: 1px solid #ddd; /* Bordes entre las celdas */
            border-radius: 4px;
            text-align: center; /* Centra el contenido dentro de cada celda */
        }

        /* Estilo para la primera celda con el color principal */
        .usuario-info .usuario-nombre {
            background-color: var(--color-principal);
            color: var(--color-blanco);
            font-size: 20px;
            font-weight: bold;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Estilo para el correo electrónico */
        .usuario-info .usuario-email {
            color: var(--color-principal);
            font-size: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Estilo para la contraseña */
        .usuario-info .usuario-contra {
            color: var(--color-suave);
            font-size: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Estilo para el rol */
        .usuario-info .usuario-rol {
            background-color: var(--color-secundario);
            color: var(--color-blanco);
            border-radius: 5px;
            padding: 3px 8px;
            font-size: 14px;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        @media screen and (max-width: 768px) {
            .usuario-tarjeta {
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
                width: 80%;
            }

            .usuario-info {
                width: 100%;
                text-align: center; /* Opcional, alinea el texto */
            }

            .usuario-info > div {
                width: 100%; /* Asegura que cada campo dentro de usuario-info ocupe todo el ancho */
                box-sizing: border-box; /* Previene problemas de padding y ancho */
            }

            .usuario-info .usuario-contra {
                font-size: 8px;
            }

            .usuario-tarjeta > div a.btn {
                display: block; /* Asegura que el botón ocupe el ancho completo */
                width: 100%;
                text-align: center; /* Centra el texto del botón */
            }
        }


    </style>
    <main>
        <?php if (!empty($usuarios)): ?>
            <?php foreach ($usuarios as $usuario): ?>
                <div class="usuario-tarjeta">
                    <div class="usuario-info">
                        <div class="usuario-nombre"><?php echo htmlspecialchars($usuario->getNombreUsuario()); ?></div>
                        <div class="usuario-email"><?php echo htmlspecialchars($usuario->getEmail()); ?></div>
                        <div class="usuario-contra"><?php echo htmlspecialchars($usuario->getContra()); ?></div>
                        <div class="usuario-rol"><?php echo $usuario->getRol()->name; ?></div>
                    </div>
                    <div>
                        <a href="../Controlador/ControladorUsuario.php?accion=cargar&id=<?php echo $usuario->getId(); ?>" class="btn btn-info">Editar</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay usuarios disponibles.</p>
        <?php endif; ?>
    </main>



<?php


include '../Compartido/footer.php';