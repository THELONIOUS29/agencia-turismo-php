<?php
include("../Vista/PlantillaReporte.php");
include("../Config/conexion.php");

// Consulta SQL para obtener los 3 paquetes más económicos
$sql = "SELECT id_paquete, nombre_paquete, precio
        FROM paquetes
        ORDER BY precio ASC
        LIMIT 3";

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
    $pdf->Cell(0, 15, 'Top 3 Paquetes Más Económicos', 0, 1, 'C');
    $pdf->Ln(10);

    // Encabezado de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(0, 102, 204); // Color de fondo para el encabezado (azul oscuro)
    $pdf->SetTextColor(255, 255, 255); // Color del texto (blanco)
    
    $pdf->Cell(30, 10, 'ID de Paquete', 1, 0, 'C', true);
    $pdf->Cell(100, 10, 'Nombre del Paquete', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Precio', 1, 0, 'C', true);
    $pdf->Ln();
    
    // Mostrar los paquetes económicos en el reporte
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0); // Color del texto (negro)
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $pdf->Cell(30, 10, $fila['id_paquete'], 1, 0, 'C');
        $pdf->Cell(100, 10, $fila['nombre_paquete'], 1, 0, 'L');
        $pdf->Cell(40, 10, number_format($fila['precio'], 2), 1, 0, 'R');
        $pdf->Ln();
    }

    // Salida del PDF
    $pdf->Output('D', 'Top_3_Paquetes_Mas_Economicos.pdf');
} else {
    echo "Error en la consulta SQL: " . mysqli_error($conn);
}
?>
