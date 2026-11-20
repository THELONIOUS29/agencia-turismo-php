<?php
include("../Vista/PlantillaReporte.php");
include("../Config/conexion.php");

// Consulta SQL para obtener la cantidad de reservas por día
$sql = "SELECT DATE(fecha_reserva) AS fecha_reserva, COUNT(id_reserva) AS cantidad_reservas
        FROM reservas
        GROUP BY DATE(fecha_reserva)
        ORDER BY fecha_reserva ASC";

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
    $pdf->Cell(0, 15, 'Reporte de Cantidad de Reservas por Día', 0, 1, 'C');
    $pdf->Ln(10);

    // Encabezado de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(0, 128, 128); // Color de fondo para el encabezado (teal)
    $pdf->SetTextColor(255, 255, 255); // Color del texto (blanco)
    
    $pdf->Cell(40, 10, 'Fecha', 1, 0, 'C', true);
    $pdf->Cell(60, 10, 'Cantidad de Reservas', 1, 0, 'C', true);
    $pdf->Ln();
    
    // Mostrar la cantidad de reservas por día en el reporte
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0); // Color del texto (negro)
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $pdf->Cell(40, 10, date('d/m/Y', strtotime($fila['fecha_reserva'])), 1, 0, 'C');
        $pdf->Cell(60, 10, $fila['cantidad_reservas'], 1, 0, 'C');
        $pdf->Ln();
    }

    // Salida del PDF
    $pdf->Output('D', 'Reporte_Cantidad_Reservas_por_Dia.pdf');
} else {
    echo "Error en la consulta SQL: " . mysqli_error($conn);
}
?>
