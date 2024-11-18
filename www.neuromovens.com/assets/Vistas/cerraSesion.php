<?php
session_start();
$_SESSION['usuario'] = false;
$_SESSION['nombre_usuario'] = null;
header("location:../../index.php");