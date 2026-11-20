<?php
require_once __DIR__ . '/../Config/session.php';
agencia_session_start(); // Inicia la sesiÃ³n

include("../Config/conexion.php");

if (isset($_SESSION['usuario'])) { // Verifica si la variable $_SESSION['usuario'] estÃ¡ establecida
    $usuario = $_SESSION['usuario'];
    $sql = "SELECT * FROM usuarios WHERE usuario = '$usuario'";
    $resultado = mysqli_query($conn, $sql);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $usuario = mysqli_fetch_assoc($resultado);
        // Obtener los datos del usuario
        $id_user = $usuario['id_user']; // Obtener el id_user
        $nombre = isset($usuario['nombre']) ? $usuario['nombre'] : '';
        $apellido = isset($usuario['apellido']) ? $usuario['apellido'] : '';
    }
}

$_SESSION['id_user'] = $id_user; // Guarda el id_user en la sesiÃ³n
$_SESSION['nombre'] = $nombre; // Guarda el nombre de la sesiÃ³n
$_SESSION['apellido'] = $apellido; // Guarda el apellido de la sesiÃ³n
?>
<?php

require('../fpdf186/fpdf.php');
header('Content-Type: text/html; charset=utf-8');

class PDF extends FPDF
{
    // Cabecera de pÃ¡gina
    function Header()
    {
        // Fondo de la cabecera
        $this->SetFillColor(0, 128, 128); // Teal
        $this->Rect(0, 0, 210, 30, 'F');

        // Logo o nombre de la empresa
        $this->Image('../Imagenes/logo2.png', 10, 10, 30);
        $this->SetFont('Arial', 'B', 18);
        $this->SetTextColor(255, 255, 255); // Texto blanco
        
        $this->Cell(0, 10, 'Ecuador Trails', 0, 1, 'C');
        
        // SubtÃ­tulo
        $this->SetFont('Arial', 'I', 14);
        $this->Cell(0, 10, utf8_decode('Explora Ecuador con nosotros'), 0, 1, 'C');
        
        // LÃ­nea de separaciÃ³n
        $this->SetDrawColor(255, 255, 255); // LÃ­nea blanca
        $this->Line(10, 30, 200, 30);
        
        // Nombre del responsable
        $this->SetFont('Arial', '', 12);
        $this->SetTextColor(255, 255, 255); // Texto blanco
        $nombre = isset($_SESSION['nombre']) ? $_SESSION['nombre'] : 'Nombre no encontrado';
        $apellido = isset($_SESSION['apellido']) ? $_SESSION['apellido'] : 'Apellido no encontrado';
        $this->Cell(0, 10, utf8_decode('Administrador responsable: ') . $nombre . ' ' . $apellido, 0, 1, 'C');
        
        // LÃ­nea de separaciÃ³n
        $this->Line(10, 40, 200, 40);
        
        // Fecha del reporte
        $this->SetFont('Arial', '', 11);
        $this->SetTextColor(0, 0, 0); // Texto negro
        $this->Cell(0, 10, 'Fecha del reporte: ' . date('d F Y'), 0, 1, 'C');
        
        // Espacio para el contenido
        $this->Ln(10);
    }

    // Pie de pÃ¡gina
    function Footer()
    {
        // PosiciÃ³n a 1.5 cm del final
        $this->SetY(-30);

        // Fuente Arial itÃ¡lica, tamaÃ±o 8
        $this->SetFont('Arial', 'I', 8);

        // LÃ­nea de separaciÃ³n
        $this->SetDrawColor(0, 128, 128); // LÃ­nea en color teal
        $this->Line(10, $this->GetY(), 200, $this->GetY());

        // NÃºmero de pÃ¡gina y total de pÃ¡ginas
        $this->Cell(0, 10, utf8_decode('PÃ¡gina ') . $this->PageNo() . ' de {nb}', 0, 1, 'C');
        
        // InformaciÃ³n de contacto
        $this->Cell(0, 5, utf8_decode('DirecciÃ³n: Calle Principal #123, Riobamba'), 0, 1, 'C');
        $this->Cell(0, 5, utf8_decode('TelÃ©fono: (123) 456-7890  Email: contacto@ecuadortrails.com'), 0, 1, 'C');
    }
}

$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();



?>

