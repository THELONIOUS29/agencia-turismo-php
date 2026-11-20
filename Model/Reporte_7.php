<?php
include("../Vista/PlantillaReporte.php");
include("../Config/conexion.php");

// Consulta SQL para obtener los 3 destinos con mejor reseña
$sql = "SELECT id_destino, nombre_destino, resenas
        FROM destinos
        ORDER BY resenas DESC
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
    $pdf->Cell(0, 15, 'Top 3 Destinos con Mejor Reseña', 0, 1, 'C');
    $pdf->Ln(10);

    // Encabezado de la tabla
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->SetFillColor(255, 69, 58); // Color de fondo para el encabezado (rojo coral)
    $pdf->SetTextColor(255, 255, 255); // Color del texto (blanco)
    
    $pdf->Cell(30, 10, 'ID de Destino', 1, 0, 'C', true);
    $pdf->Cell(100, 10, 'Nombre del Destino', 1, 0, 'C', true);
    $pdf->Cell(40, 10, 'Reseñas', 1, 0, 'C', true);
    $pdf->Ln();
    
    // Mostrar los destinos con mejores reseñas en el reporte
    $pdf->SetFont('Arial', '', 12);
    $pdf->SetTextColor(0, 0, 0); // Color del texto (negro)
    
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $pdf->Cell(30, 10, $fila['id_destino'], 1, 0, 'C');
        $pdf->Cell(100, 10, $fila['nombre_destino'], 1, 0, 'L');
        $pdf->Cell(40, 10, $fila['resenas'], 1, 0, 'R');
        $pdf->Ln();
    }

    // Salida del PDF
    $pdf->Output('D', 'Top_3_Destinos_Mejores_Resenas.pdf');
} else {
    echo "Error en la consulta SQL: " . mysqli_error($conn);
}
?>
