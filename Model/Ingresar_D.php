<?php
include("../Config/conexion.php"); // Actualiza la ruta de conexión según tu estructura

// Verificar si se pudo conectar a la base de datos
if (mysqli_connect_errno()) {
    die("Falló la conexión a MySQL: " . mysqli_connect_error());
}

// Función para generar un ID de destino (auto-incremental)
function generarIDDestino()
{
    global $conn;

    // Obtener el siguiente ID auto-incremental
    $sql = "SHOW TABLE STATUS LIKE 'destinos'";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);
    return $row['Auto_increment'];
}

// Verificar si se recibieron los datos del formulario
if (
    isset($_POST['nombre_destino']) &&
    isset($_FILES['foto_destino']) &&
    isset($_POST['descripcion']) &&
    isset($_POST['resenas'])
) {
    // Recuperar los datos del formulario
    $nombre_destino = $_POST['nombre_destino'];
    $descripcion = $_POST['descripcion'];
    $resenas = $_POST['resenas'];

    // Procesar la imagen recibida
    if ($_FILES['foto_destino']['error'] == UPLOAD_ERR_OK) {
        $foto_destino_name = $_FILES['foto_destino']['name']; // Nombre del archivo
        $foto_destino_tmp = $_FILES['foto_destino']['tmp_name']; // Ruta temporal del archivo
        $ruta_foto_destino = "../imagen/" . basename($foto_destino_name); // Ruta donde se guardará la imagen

        // Mover la imagen temporal a la carpeta de uploads
        if (move_uploaded_file($foto_destino_tmp, $ruta_foto_destino)) {
            $foto_destino = file_get_contents($ruta_foto_destino); // Leer el contenido del archivo para guardarlo como LONGBLOB
        } else {
            $foto_destino = null; // O puedes manejar un error en el proceso de carga
            $message = 'Error al mover la imagen. ';
        }
    } else {
        $foto_destino = null; // En caso de que no se cargue ninguna imagen
    }

    // Generar ID de destino
    $id_destino = generarIDDestino();

    // Insertar los datos en la base de datos
    $sql = "INSERT INTO destinos (id_destino, nombre_destino, foto_destino, descripcion, resenas) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    // Corregir los tipos de datos en la vinculación de parámetros
    mysqli_stmt_bind_param($stmt, 'isssi', $id_destino, $nombre_destino, $foto_destino, $descripcion, $resenas);

    if (mysqli_stmt_execute($stmt)) {
        // La inserción fue exitosa
        $message = 'Destino registrado correctamente.';
    } else {
        // Ocurrió un error en la inserción
        $message = 'No se realizaron cambios: ' . mysqli_error($conn);
    }

    mysqli_stmt_close($stmt);
} else {
    // Mostrar mensaje de error si no se recibieron todos los datos del formulario
    $message = 'Datos del destino incompletos.';
}

// Redirigir a "ingresar.php" enviando el mensaje como parámetro en la URL
header("Location: ../Vista/ingresar_D.php?message=" . urlencode($message));
exit;
?>
