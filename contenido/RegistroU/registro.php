<?php
require_once __DIR__ . '/../../Config/session.php';
agencia_session_start();

require_once __DIR__ . '/../../Config/conexion_pdo.php';

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = trim($_POST['usuario'] ?? '');
    $correo = trim($_POST['correo'] ?? '');
    $passwordPlano = $_POST['contra'] ?? '';
    $contra = password_hash($passwordPlano, PASSWORD_BCRYPT);

    $nombre = $usuario;
    $apellido = '';
    $cedula = '';
    $direccion = '';

    if ($usuario === '' || $passwordPlano === '' || !filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $message = "Datos de registro invalidos.";
    } else {
        $emailValido = true;
        $apiKey = agencia_env('HUNTER_API_KEY', '');

        if ($apiKey !== '' && function_exists('curl_init')) {
            $url = "https://api.hunter.io/v2/email-verifier?email=" . urlencode($correo) . "&api_key=" . urlencode($apiKey);
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);
            $emailValido = isset($result['data']['result']) && $result['data']['result'] == 'deliverable';
        }

        if ($emailValido) {
            try {
                if ($databaseDriver === 'pgsql' || $databaseDriver === 'postgres' || $databaseDriver === 'postgresql') {
                    $sql = "INSERT INTO usuarios (usuario, correo, contra, nombre, apellido, cedula, direccion)
                            VALUES (:usuario, :correo, :contra, :nombre, :apellido, :cedula, :direccion)";
                } else {
                    $sql = "INSERT INTO usuarios (usuario, correo, contra, nombre, apellido, cedula, direccion, fotoU)
                            VALUES (:usuario, :correo, :contra, :nombre, :apellido, :cedula, :direccion, '')";
                }

                $stmt = $pdo->prepare($sql);
                $stmt->execute([
                    'usuario' => $usuario,
                    'correo' => $correo,
                    'contra' => $contra,
                    'nombre' => $nombre,
                    'apellido' => $apellido,
                    'cedula' => $cedula,
                    'direccion' => $direccion,
                ]);

                $message = "Registro exitoso. Ahora puedes iniciar sesion.";
            } catch (PDOException $e) {
                $message = "No se pudo registrar el usuario.";
            }
        } else {
            $message = "El correo electronico no es valido.";
        }
    }
}

header("Location: ./ingreso.php?message=" . urlencode($message));
exit;
?>


