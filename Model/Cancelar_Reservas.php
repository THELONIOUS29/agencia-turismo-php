<?php
include("../Config/conexion.php");
require_once __DIR__ . '/../Config/session.php';
agencia_session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    if (!isset($_SESSION['usuario'])) {
        echo 'No hay sesiÃ³n activa.';
        exit();
    }

   
    if (isset($_POST['reserva_id']) && isset($_POST['action']) && $_POST['action'] === 'cancelar') {
        $reserva_id = intval($_POST['reserva_id']);
        
       
        $stmt = $conn->prepare("SELECT cantidad_personas, id_paquete FROM reservas WHERE id_reserva = ?");
        $stmt->bind_param("i", $reserva_id);
        $stmt->execute();
        $resultado = $stmt->get_result();

        if ($resultado && $resultado->num_rows > 0) {
            $reserva = $resultado->fetch_assoc();
            $cantidad_personas = $reserva['cantidad_personas'];
            $id_paquete = $reserva['id_paquete'];
            
            
            $stmt = $conn->prepare("DELETE FROM reservas WHERE id_reserva = ?");
            $stmt->bind_param("i", $reserva_id);
            if ($stmt->execute()) {
              
                $stmt = $conn->prepare("UPDATE paquetes SET cantidad_personas_max = cantidad_personas_max + ? WHERE id_paquete = ?");
                $stmt->bind_param("ii", $cantidad_personas, $id_paquete);
                if ($stmt->execute()) {
                    echo 'Reserva cancelada con Ã©xito';
                } else {
                    echo 'Error al actualizar el paquete';
                }
            } else {
                echo 'Error al cancelar la reserva';
            }
            $stmt->close();
        } else {
            echo 'Reserva no encontrada';
        }
    } else {
        echo 'Datos de formulario invÃ¡lidos';
    }
} else {
    echo 'MÃ©todo de solicitud no vÃ¡lido';
}
?>

