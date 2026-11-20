<?php
require_once __DIR__ . '/../Config/session.php';
agencia_session_start();
include("../Config/conexion.php");

$id_paquete = isset($_GET['id_paquete']) ? intval($_GET['id_paquete']) : null;

if ($id_paquete) {
    // Preparar la consulta de eliminaciÃ³n
    $sql = "DELETE FROM paquetes WHERE id_paquete = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if (!$stmt) {
        error_log("Error al preparar la consulta: " . mysqli_error($conn));
        die("Error al preparar la consulta.");
    }

    mysqli_stmt_bind_param($stmt, 'i', $id_paquete);
    $resultado = mysqli_stmt_execute($stmt);

    if ($resultado) {
        header("Location: ../Controlador/Controlador.php?campo=6&message=Paquete eliminado exitosamente");
    } else {
        error_log("Error al eliminar el paquete: " . mysqli_stmt_error($stmt));
        header("Location: ../Controlador/Controlador.php?campo=6&message=Error al eliminar el paquete");
    }
} else {
    header("Location: ../Controlador/Controlador.php?campo=6&message=ID de paquete no proporcionado");
}

mysqli_close($conn);
?>
