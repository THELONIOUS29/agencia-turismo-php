<?php
include("../Vista/PlantillaReporte.php");
include("../Config/conexion.php");

// Consulta SQL para obtener los usuarios con más reservas
$sql = "SELECT usuarios.id_user, usuarios.nombre, usuarios.apellido, COUNT(reservas.id_reserva) AS cantidad_reservas
        FROM reservas
        JOIN usuarios ON reservas.id_user = usuarios.id_user
        GROUP BY usuarios.id_user, usuarios.nombre, usuarios.apellido
        ORDER BY cantidad_reservas DESC";

$resultado = mysqli_query($conn, $sql);

if ($resultado) {
    // Generar el reporte en PDF
    $pdf = new PDF();
    $pdf->AliasNbPages();
    $pdf->AddPage();
    
    // Agregar logotipo
    $pdf->Image('../Imagenes/logo2.png', 10, 10, 30); // Ajusta la ruta y el tamaño según sea necesario
    
    $pdf->Ln(20); // Espacio después del logotipo

    // Título del reporte
    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 15, 'Reporte de Usuarios con Más Reservas', 0, 1, 'C');
    $pdf->Ln(10);

    // Encabezado de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(0, 128, 128); // Color de fondo para el encabezado (teal)
    $pdf->SetTextColor(255, 255, 255); // Color del texto (blanco)
    
    $pdf->Cell(30, 10, 'ID de Usuario', 1, 0, 'C', true);
    $pdf->Cell(60, 10, 'Nombre del Usuario', 1, 0, 'C', true);
    $pdf->Cell(60, 10, 'Apellido del Usuario', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Cantidad de Reservas', 1, 0, 'C', true);
    $pdf->Ln();
    
    // Mostrar todas las reservas en el reporte
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0); // Color del texto (negro)
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $pdf->Cell(30, 10, $fila['id_user'], 1, 0, 'C');
        $pdf->Cell(60, 10, $fila['nombre'], 1, 0, 'L');
        $pdf->Cell(60, 10, $fila['apellido'], 1, 0, 'L');
        $pdf->Cell(40, 10, $fila['cantidad_reservas'], 1, 0, 'C');
        $pdf->Ln();
    }

    // Salida del PDF
    $pdf->Output('D', 'Reporte_Usuarios_Mas_Reservas.pdf');
} else {
    echo "Error en la consulta SQL: " . mysqli_error($conn);
}
?>
