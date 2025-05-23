<?php
namespace Controlador;
session_start();

require '../vendor/autoload.php';
require '../connection.php';
require_once '../Entidades/Usuario.php';
require_once '../Modelos/ModeloUsuario.php';

use Dompdf\Dompdf;
use Dompdf\Options;
use Modelos\ModeloUsuario;
use Entidades\Usuario;
use Exception;

class GeneradorPDFUsuarios {
    private $conexion;
    private $modeloUsuario;
    private $dompdf;

    public function __construct() {
        $this->conexion = establecerConexion();
        $this->modeloUsuario = new ModeloUsuario($this->conexion);

        // Configurar opciones de DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        // Inicializar DOMPDF
        $this->dompdf = new Dompdf($options);
    }

    public function generarPDF() {
        try {
            // Obtener parámetros
            $termino = isset($_GET['termino']) ? trim($_GET['termino']) : '';
            $titulo = empty($termino) ? 'Lista Completa de Usuarios' : 'Usuarios filtrados por: ' . htmlspecialchars($termino);

            // Obtener usuarios (filtrados o todos)
            if (!empty($termino)) {
                $resultado = $this->modeloUsuario->buscarUsuarios($termino);
                $usuarios = $resultado['usuarios'];
                $total = $resultado['total'];
            } else {
                // Obtener todos los usuarios sin paginación
                $resultado = $this->modeloUsuario->obtenerPaginado(1, 1000);
                $usuarios = $resultado['usuarios'];
                $total = $resultado['total'];
            }

            // Fecha actual para el PDF
            $fechaGenerado = date('d/m/Y H:i:s');

            // Generar HTML para el PDF
            $html = $this->generarHTMLParaPDF($usuarios, $titulo, $fechaGenerado, $total, $termino);

            // Cargar HTML en DOMPDF
            $this->dompdf->loadHtml($html);

            // Configurar papel y orientación
            $this->dompdf->setPaper('A4', 'portrait');

            // Renderizar PDF
            $this->dompdf->render();

            // Configurar headers para descarga
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="usuarios.pdf"');
            header('Cache-Control: max-age=0');

            // Salida del PDF
            $this->dompdf->stream('usuarios.pdf', ['Attachment' => true]);

        } catch (Exception $e) {
            // Manejar errores
            echo 'Error al generar PDF: ' . $e->getMessage();
        }
    }

    private function generarHTMLParaPDF($usuarios, $titulo, $fechaGenerado, $total, $termino) {
        $html = '
        <!DOCTYPE html>
        <html lang="es">
        <head>
            <meta charset="UTF-8">
            <title>' . $titulo . '</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 15px;
                    font-size: 12px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 20px;
                    border-bottom: 1px solid #ccc;
                    padding-bottom: 10px;
                }
                h1 {
                    color: #2a5885;
                    font-size: 20px;
                    margin: 0 0 10px 0;
                }
                .info {
                    margin-bottom: 15px;
                    font-size: 11px;
                    color: #666;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-bottom: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                .footer {
                    margin-top: 20px;
                    font-size: 10px;
                    text-align: center;
                    color: #666;
                    border-top: 1px solid #ccc;
                    padding-top: 10px;
                }
                .badge {
                    padding: 3px 6px;
                    border-radius: 3px;
                    font-size: 10px;
                    color: #fff;
                }
                .bg-danger {
                    background-color: #dc3545;
                }
                .bg-warning {
                    background-color: #ffc107;
                    color: #000;
                }
                .bg-secondary {
                    background-color: #6c757d;
                }
                .bg-primary {
                    background-color: #0d6efd;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>' . $titulo . '</h1>
            </div>
            
            <div class="info">
                <p><strong>Fecha de generación:</strong> ' . $fechaGenerado . '</p>
                <p><strong>Total de usuarios:</strong> ' . $total . '</p>';

        if (!empty($termino)) {
            $html .= '<p><strong>Filtro aplicado:</strong> ' . htmlspecialchars($termino) . '</p>';
        }

        $html .= '</div>';

        if (count($usuarios) > 0) {
            $html .= '
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Email</th>
                        <th>Rol</th>
                    </tr>
                </thead>
                <tbody>';

            foreach ($usuarios as $usuario) {
                $rolClass = match($usuario->getRol()->name) {
                    'jefe' => 'bg-danger',
                    'empleado' => 'bg-warning',
                    'visitante' => 'bg-secondary',
                    default => 'bg-primary'
                };

                $rolCapitalized = ucfirst($usuario->getRol()->name);

                $html .= '
                <tr>
                    <td>' . $usuario->getId() . '</td>
                    <td>' . htmlspecialchars($usuario->getNombreUsuario()) . '</td>
                    <td>' . htmlspecialchars($usuario->getEmail()) . '</td>
                    <td><span class="badge ' . $rolClass . '">' . $rolCapitalized . '</span></td>
                </tr>';
            }

            $html .= '
                </tbody>
            </table>';
        } else {
            $html .= '<div style="text-align:center;padding:20px;color:#666;">
                <p>No hay usuarios disponibles que coincidan con los criterios especificados.</p>
            </div>';
        }

        $html .= '
            <div class="footer">
                <p>Documento generado por el sistema de gestión de usuarios. Este documento es confidencial.</p>
                <p>Página 1</p>
            </div>
        </body>
        </html>';

        return $html;
    }
}

// Ejecutar generador si se llama directamente
if (basename($_SERVER['SCRIPT_NAME']) === basename(__FILE__)) {
    $generador = new GeneradorPDFUsuarios();
    $generador->generarPDF();
}
