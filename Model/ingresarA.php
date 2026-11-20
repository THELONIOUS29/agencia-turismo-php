<?php
include("../Config/conexion.php"); // Actualiza la ruta de conexión según tu estructura

// Verificar si se pudo conectar a la base de datos
if (mysqli_connect_errno()) {
    die("Falló la conexión a MySQL: " . mysqli_connect_error());
}

// Función para generar un ID de viaje (auto-incremental)
function generarIDViaje()
{
    global $conn;

    // Obtener el siguiente ID auto-incremental
    $sql = "SHOW TABLE STATUS LIKE 'viajes'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['Auto_increment'];
}

// Verificar si se recibieron los datos del formulario
if (
    isset($_POST['nombre']) &&
    isset($_FILES['foto_destino']) &&
    isset($_POST['descripcion']) &&
    isset($_POST['precio']) &&
    isset($_POST['personas']) &&
    isset($_POST['duracion']) &&
    isset($_POST['transporte']) &&
    isset($_POST['destino']) &&
    isset($_POST['resenas'])
) {
    // Recuperar los datos del formulario
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $personas = $_POST['personas'];
    $duracion = $_POST['duracion'];
    $transporte = $_POST['transporte'];
    $destino = $_POST['destino'];
    $resenas = $_POST['resenas'];

    // Procesar la imagen recibida
    $foto_destino = $_FILES['foto_destino']['name']; // Nombre del archivo
    $foto_destino_tmp = $_FILES['foto_destino']['tmp_name']; // Ruta temporal del archivo
    $ruta_foto_destino = "../imagen/" . $foto_destino; // Ruta donde se guardará la imagen

    // Mover la imagen temporal a la carpeta de uploads
    move_uploaded_file($foto_destino_tmp, $ruta_foto_destino);

    // Generar ID de viaje
    $id_viaje = generarIDViaje();

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO viajes (id_viajes, nombre, foto_destino, descripcion, precio, personas, duracion, transporte, destino, resenas) VALUES ('$id_viaje', '$nombre', '$foto_destino', '$descripcion', '$precio', '$personas', '$duracion', '$transporte', '$destino', '$resenas')";
    if (mysqli_query($conn, $sql)) {
        // La inserción fue exitosa
        $message = 'Viaje registrado correctamente.';
    } else {
        // Ocurrió un error en la inserción
        $message = 'No se realizaron cambios.: ' . mysqli_error($conn);
    }
} else {
    // Mostrar mensaje de error si no se recibieron todos los datos del formulario
    $message = 'Datos del viaje incompletos.';
}

// Redirigir a "Restaurant.php" enviando el mensaje como parámetro en la URL
header("Location: ../Vista/ingresar.php?message=" . urlencode($message));
exit;
?>
