<?php
session_start(); 
$servername = "localhost";
$username = "root";
$password = '';
$databasename = "agencia";


$conn = mysqli_connect($servername, $username, $password, $databasename);

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
        // Vincula los parámetros y ejecuta la consulta
        $stmt->bind_param("sssssss", $nombre, $apellido, $correo, $direccion, $telefono, $cedula, $usuario);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $message = "Perfil actualizado con éxito.";
        } else {
            $message = "No se realizaron cambios en el perfil.";
        }
        $stmt->close();
    }
} else {
    $message = "La sesión no ha sido iniciada o el usuario no está establecido.";
}

$conn->close();

// Redirige a la página de edición de perfil con el mensaje
header("Location: ../edit_perfil.php?message=" . urlencode($message));
exit;
?>
