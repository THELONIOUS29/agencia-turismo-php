<?php
include("../Vista/PlantillaReporte.php");
include("../Config/conexion.php");

// Consulta SQL para obtener los paquetes con más reservas
$sql = "SELECT paquetes.id_paquete, paquetes.nombre_paquete, COUNT(reservas.id_reserva) AS cantidad_reservas
        FROM reservas
        JOIN paquetes ON reservas.id_paquete = paquetes.id_paquete
        GROUP BY paquetes.id_paquete, paquetes.nombre_paquete
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
    $pdf->Cell(0, 15, 'Reporte de Paquetes Más Reservados', 0, 1, 'C');
    $pdf->Ln(10);

    // Encabezado de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(0, 128, 128); // Color de fondo para el encabezado (teal)
    $pdf->SetTextColor(255, 255, 255); // Color del texto (blanco)
    
    $pdf->Cell(30, 10, 'ID de Paquete', 1, 0, 'C', true);
    $pdf->Cell(90, 10, 'Nombre del Paquete', 1, 0, 'C', true);
    $pdf->Cell(60, 10, 'Cantidad de Reservas', 1, 0, 'C', true);
    $pdf->Ln();
    
    // Mostrar los paquetes más reservados en el reporte
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0); // Color del texto (negro)
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $pdf->Cell(30, 10, $fila['id_paquete'], 1, 0, 'C');
        $pdf->Cell(90, 10, $fila['nombre_paquete'], 1, 0, 'L');
        $pdf->Cell(60, 10, $fila['cantidad_reservas'], 1, 0, 'C');
        $pdf->Ln();
    }

    // Salida del PDF
    $pdf->Output('D', 'Reporte_Paquetes_Mas_Reservados.pdf');
} else {
    echo "Error en la consulta SQL: " . mysqli_error($conn);
}
?>
