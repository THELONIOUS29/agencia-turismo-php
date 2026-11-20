<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['chartImage'])) {
    $chartImage = $_POST['chartImage'];

    // Elimina el encabezado data:image/png;base64,
    $chartImage = str_replace('data:image/png;base64,', '', $chartImage);
    $chartImage = str_replace(' ', '+', $chartImage);

    // Decodifica la imagen
    $chartImage = base64_decode($chartImage);

    // Guarda la imagen en el servidor
    file_put_contents('grafico.png', $chartImage);

    // Redirige a la página que generará el PDF
    header('Location: generar_pdf.php');
}
?>
