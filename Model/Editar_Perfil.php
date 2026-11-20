<?php
require_once __DIR__ . '/../Config/session.php';
agencia_session_start(); 
include("../Config/conexion.php");

$message = ''; // Inicializar el mensaje

if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];

    // Validar y sanear los datos del formulario
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $telefono = $_POST['telefono'];
    $cedula = $_POST['cedula'];

    // Manejo de la foto de perfil
    $fotoU = $_FILES['fotoU']['tmp_name'];
    if ($fotoU) {
        $fotoUData = file_get_contents($fotoU);
        $fotoUData = mysqli_real_escape_string($conn, $fotoUData);
        $updateFoto = ", fotoU = '$fotoUData'";
    } else {
        $updateFoto = "";
    }

    // Consulta para actualizar el perfil
    $sql = "UPDATE usuarios SET nombre = ?, apellido = ?, correo = ?, direccion = ?, telefono = ?, cedula = ? $updateFoto WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        $message = 'Error al preparar la consulta: ' . $conn->error;
    } else {
        // Vincula los parÃ¡metros y ejecuta la consulta
        $stmt->bind_param("sssssss", $nombre, $apellido, $correo, $direccion, $telefono, $cedula, $usuario);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "Perfil actualizado con Ã©xito.";
        } else {
            $message = "No se realizaron cambios en el perfil.";
        }
        $stmt->close();
    }
} else {
    $message = "La sesiÃ³n no ha sido iniciada o el usuario no estÃ¡ establecido.";
}

$conn->close();

// Redirige a la pÃ¡gina de ediciÃ³n de perfil con el mensaje
header("Location: ../Vista/editar_perfil.php?message=" . urlencode($message));
exit;
?>

