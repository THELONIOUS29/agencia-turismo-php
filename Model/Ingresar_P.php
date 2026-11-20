<?php
include("../Config/conexion.php"); // Actualiza la ruta de conexión según tu estructura

// Verificar si se pudo conectar a la base de datos
if (mysqli_connect_errno()) {
    die("Falló la conexión a MySQL: " . mysqli_connect_error());
}

// Función para validar el formato de la fecha
function validarFecha($fecha, $formato = 'Y-m-d') {
    $d = DateTime::createFromFormat($formato, $fecha);
    return $d && $d->format($formato) === $fecha;
}

// Verificar si se recibieron los datos del formulario
if (
    isset($_POST['nombre_paquete']) &&
    isset($_POST['descripcion']) &&
    isset($_POST['precio']) &&
    isset($_POST['fecha_inicio']) &&
    isset($_POST['fecha_fin']) &&
    isset($_POST['destino']) &&
    isset($_POST['cantidad_personas_max'])
) {
    // Recuperar los datos del formulario
    $nombre_paquete = $_POST['nombre_paquete'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $id_destino = $_POST['destino'];
    $cantidad_personas_max = $_POST['cantidad_personas_max'];

    // Validar formato de fechas
    if (!validarFecha($fecha_inicio) || !validarFecha($fecha_fin)) {
        $message = 'Formato de fecha no válido.';
    } else {
        // Depuración: Verificar los valores antes de la inserción
        error_log("Datos a insertar: nombre_paquete=$nombre_paquete, descripcion=$descripcion, precio=$precio, fecha_inicio=$fecha_inicio, fecha_fin=$fecha_fin, id_destino=$id_destino, cantidad_personas_max=$cantidad_personas_max");

        // Insertar los datos en la base de datos
        $sql = "INSERT INTO paquetes (nombre_paquete, descripcion, precio, fecha_inicio, fecha_fin, id_destino, cantidad_personas_max) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);

        // Corregir los tipos de datos en la vinculación de parámetros
        mysqli_stmt_bind_param($stmt, 'ssdssii', $nombre_paquete, $descripcion, $precio, $fecha_inicio, $fecha_fin, $id_destino, $cantidad_personas_max);

        if (mysqli_stmt_execute($stmt)) {
            // La inserción fue exitosa
            $message = 'Paquete registrado correctamente.';
        } else {
            // Ocurrió un error en la inserción
            $message = 'No se realizaron cambios: ' . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }
} else {
    // Mostrar mensaje de error si no se recibieron todos los datos del formulario
    $message = 'Datos del paquete incompletos.';
}

// Redirigir a "ingresar_P.php" enviando el mensaje como parámetro en la URL
header("Location: ../Vista/ingresar_P.php?message=" . urlencode($message));
exit;
?>
