<?php
//session_start(); // Asegúrate de iniciar la sesión

$servername = "localhost";
$username = "root";
$password = '';
$databasename = "agencia";


$conn = mysqli_connect($servername, $username, $password, $databasename);

if (isset($_SESSION['usuario'])) { // Verifica si la variable $_SESSION['usuario'] está establecida
    $usuario = $_SESSION['usuario'];

    // Usa consultas preparadas para evitar inyecciones SQL
    $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario = ?");
    if ($stmt === false) {
        die('Error al preparar la consulta: ' . $conn->error);
    }

    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $usuario = $resultado->fetch_assoc();
        
        // Obtener los datos del usuario
        $nombre = isset($usuario['nombre']) ? $usuario['nombre'] : '';
        $apellido = isset($usuario['apellido']) ? $usuario['apellido'] : '';
        $correo = isset($usuario['correo']) ? $usuario['correo'] : '';
        $direccion = isset($usuario['direccion']) ? $usuario['direccion'] : '';
        $telefono = isset($usuario['telefono']) ? $usuario['telefono'] : '';
        $cedula = isset($usuario['cedula']) ? $usuario['cedula'] : '';
        $fotoU = isset($usuario['fotoU']) ? 'data:image/jpeg;base64,' . base64_encode($usuario['fotoU']) : '../img/default_profile_image.jpg'; // Cambia a imagen por defecto si no hay foto
        
        // Cierra el statement
        $stmt->close();
    } else {
        echo "Error al obtener la información del usuario.";
    }
} else {
    echo "La sesión no ha sido iniciada o el usuario no está establecido.";
}
?>
