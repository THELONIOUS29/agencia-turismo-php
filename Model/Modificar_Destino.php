<?php
require_once __DIR__ . '/../Config/session.php';
agencia_session_start();
include("../Config/conexion.php");

// Verificar si se pudo conectar a la base de datos
if (mysqli_connect_errno()) {
    die("FallÃ³ la conexiÃ³n a MySQL: " . mysqli_connect_error());
}

// Verificar si se recibieron los datos del formulario
if (
    isset($_POST['id_destino']) &&
    isset($_POST['nombre_destino']) &&
    isset($_POST['descripcion']) &&
    isset($_POST['resenas'])
) {
    // Recuperar los datos del formulario
    $id_destino = intval($_POST['id_destino']);
    $nombre_destino = $_POST['nombre_destino'];
    $descripcion = $_POST['descripcion'];
    $resenas = intval($_POST['resenas']);

    // Manejo de la foto de destino
    if (isset($_FILES['foto_destino']) && $_FILES['foto_destino']['error'] == UPLOAD_ERR_OK) {
        $foto_destino = $_FILES['foto_destino']['tmp_name'];
        $foto_destinoData = file_get_contents($foto_destino);
        $foto_destinoData = mysqli_real_escape_string($conn, $foto_destinoData);
        $updateFoto = ", foto_destino = '$foto_destinoData'";
    } else {
        $updateFoto = "";
    }

    // Comprobar si se ha subido un archivo y procesar la imagen
    if (isset($_FILES['foto_destino']) && $_FILES['foto_destino']['error'] === UPLOAD_ERR_OK) {
        $foto_destino = addslashes(file_get_contents($_FILES['foto_destino']['tmp_name']));
        $sql = "UPDATE destinos SET nombre_destino = ?, descripcion = ?, foto_destino = ?, resenas = ? WHERE id_destino = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            $message = 'Error al preparar la consulta: ' . mysqli_error($conn);
            error_log($message);
        } else {
            mysqli_stmt_bind_param($stmt, 'sssii', $nombre_destino, $descripcion, $foto_destino, $resenas, $id_destino);
            if (mysqli_stmt_execute($stmt)) {
                $message = 'Destino actualizado correctamente.';
            } else {
                $message = 'No se realizaron cambios: ' . mysqli_stmt_error($stmt);
                error_log($message);
            }
            mysqli_stmt_close($stmt);
        }
    } else {
        // Si no se sube una nueva imagen, mantener la existente
        $sql = "UPDATE destinos SET nombre_destino = ?, descripcion = ?, resenas = ? WHERE id_destino = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if (!$stmt) {
            $message = 'Error al preparar la consulta: ' . mysqli_error($conn);
            error_log($message);
        } else {
            mysqli_stmt_bind_param($stmt, 'ssii', $nombre_destino, $descripcion, $resenas, $id_destino);
            if (mysqli_stmt_execute($stmt)) {
                $message = 'Destino actualizado correctamente.';
            } else {
                $message = 'No se realizaron cambios: ' . mysqli_stmt_error($stmt);
                error_log($message);
            }
            mysqli_stmt_close($stmt);
        }
    }
} else {
    $message = 'Datos del destino incompletos.';
    error_log('Datos del formulario no proporcionados: ' . json_encode($_POST));
}

// Redirigir a "modificar_Destino.php" enviando el mensaje como parÃ¡metro en la URL
header("Location: ../Vista/modificar_Destino.php?message=" . urlencode($message));
exit;
?>

