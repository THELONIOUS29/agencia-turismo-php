<?php
require_once __DIR__ . '/../Config/conexion_pdo.php';

require_once __DIR__ . '/../Config/session.php';
agencia_session_start();

if (!isset($_SESSION['id_user'])) {
    echo 'error';
    exit();
}

$id_user = (int) $_SESSION['id_user'];
$id_paquete = isset($_POST['id_paquete']) ? (int) $_POST['id_paquete'] : 0;
$cantidad_personas = isset($_POST['cantidad_personas']) ? (int) $_POST['cantidad_personas'] : 0;

if ($id_paquete <= 0 || $cantidad_personas <= 0) {
    echo 'error';
    exit();
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare(
        "SELECT cantidad_personas_max
         FROM paquetes
         WHERE id_paquete = :id_paquete
         FOR UPDATE"
    );
    $stmt->execute(['id_paquete' => $id_paquete]);
    $paquete = $stmt->fetch();

    if (!$paquete) {
        throw new Exception('Paquete no encontrado');
    }

    $cantidadDisponible = (int) $paquete['cantidad_personas_max'];

    if ($cantidadDisponible - $cantidad_personas < 0) {
        throw new Exception('No hay suficientes espacios disponibles');
    }

    $stmt = $pdo->prepare(
        "INSERT INTO reservas (id_user, id_paquete, cantidad_personas)
         VALUES (:id_user, :id_paquete, :cantidad_personas)"
    );
    $stmt->execute([
        'id_user' => $id_user,
        'id_paquete' => $id_paquete,
        'cantidad_personas' => $cantidad_personas,
    ]);

    $stmt = $pdo->prepare(
        "UPDATE paquetes
         SET cantidad_personas_max = cantidad_personas_max - :cantidad_personas
         WHERE id_paquete = :id_paquete"
    );
    $stmt->execute([
        'cantidad_personas' => $cantidad_personas,
        'id_paquete' => $id_paquete,
    ]);

    $pdo->commit();
    echo 'success';
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }

    echo 'error';
}
?>

