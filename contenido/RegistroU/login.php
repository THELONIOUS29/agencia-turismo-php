<?php
require_once __DIR__ . '/../../Config/conexion_pdo.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario'] ?? '');
    $contra = $_POST['contra'] ?? '';

    try {
        $stmt = $pdo->prepare("SELECT id_user, usuario, rol, contra FROM usuarios WHERE usuario = :usuario");
        $stmt->execute(['usuario' => $usuario]);
        $row = $stmt->fetch();

        if ($row && password_verify($contra, $row['contra'])) {
            require_once __DIR__ . '/../../Config/session.php';
agencia_session_start();
            $_SESSION['id_user'] = $row['id_user'];
            $_SESSION['usuario'] = $row['usuario'];
            $_SESSION['rol'] = $row['rol'];

            if ($row['rol'] == 'admin') {
                header("Location: /Agencia/Vista/admin.php");
            } elseif ($row['rol'] == 'usuario') {
                header("Location: /Agencia/Vista/Inicio.php");
            } else {
                header("Location: ../index.php");
            }
            exit();
        }

        $message = $row
            ? "Contrasena incorrecta."
            : "No existe una cuenta con ese nombre de usuario.";

        header("Location: ./ingreso.php?message=" . urlencode($message));
        exit();
    } catch (PDOException $e) {
        $message = "Error de conexion o consulta.";
        header("Location: ./ingreso.php?message=" . urlencode($message));
        exit();
    }
}
?>


