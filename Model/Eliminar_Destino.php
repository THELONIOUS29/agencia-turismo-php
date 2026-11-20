<?php
require_once __DIR__ . '/../Config/session.php';
agencia_session_start();
include("../Config/conexion.php");

$id_destino = isset($_GET['id_destino']) ? intval($_GET['id_destino']) : null;

if ($id_destino) {
    // Primero, eliminar los registros en la tabla `paquetes` que referencian al destino
    $sql_delete_paquetes = "DELETE FROM paquetes WHERE id_destino = ?";
    $stmt_delete_paquetes = mysqli_prepare($conn, $sql_delete_paquetes);
    
    if (!$stmt_delete_paquetes) {
        error_log("Error al preparar la consulta para eliminar paquetes: " . mysqli_error($conn));
        die("Error al preparar la consulta para eliminar paquetes.");
    }
    
    mysqli_stmt_bind_param($stmt_delete_paquetes, 'i', $id_destino);
    $resultado_paquetes = mysqli_stmt_execute($stmt_delete_paquetes);
    
    if (!$resultado_paquetes) {
        error_log("Error al eliminar paquetes: " . mysqli_stmt_error($stmt_delete_paquetes));
        mysqli_stmt_close($stmt_delete_paquetes);
        die("Error al eliminar paquetes.");
    }
    
    mysqli_stmt_close($stmt_delete_paquetes);

    // Ahora eliminar el destino
    $sql = "DELETE FROM destinos WHERE id_destino = ?";
    $stmt = mysqli_prepare($conn, $sql);
    
    if (!$stmt) {
        error_log("Error al preparar la consulta: " . mysqli_error($conn));
        die("Error al preparar la consulta.");
    }

    mysqli_stmt_bind_param($stmt, 'i', $id_destino);
    $resultado = mysqli_stmt_execute($stmt);
    
    if ($resultado) {
        header("Location: ../Controlador/Controlador.php?campo=5&message=Destino eliminado exitosamente");
    } else {
        error_log("Error al eliminar el destino: " . mysqli_stmt_error($stmt));
        header("Location: ../Controlador/Controlador.php?campo=5&message=Error al eliminar el destino");
    }

    mysqli_stmt_close($stmt);
} else {
    header("Location: ../Controlador/Controlador.php?campo=5&message=ID de destino no proporcionado");
}

mysqli_close($conn);
?>

