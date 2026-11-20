<?php

$valorRecibido = isset($_GET['user']) ? intval($_GET['user']) : 0;

switch($valorRecibido) {
    case 1:
        include("../index.php");
        break;
    case 2:
        include("../Vista/usuario/inicio.php");
        break;
    case 3:
        include("../Vista/usuario/PerfilUser.php");
        break;
    case 4:
        include("../Vista/usuario/Nosotros.php");
        break;
    case 5:
        include("../Vista/mostrar_Destinos.php");
        break;
    case 6:
        include("../Vista/mostrar_Paquetes.php");
        break;
    case 7:
        include("../Vista/#");  // Asegúrate de reemplazar '#' con el archivo correcto
        break;
    case 8:
        include("../Vista/perfilAD.php");
        break;
    case 9:
        include("../Vista/#");  // Asegúrate de reemplazar '#' con el archivo correcto
        break;
    case 10:
        $id_paquete = isset($_GET['id_paquete']) ? intval($_GET['id_paquete']) : null;
        include("../Vista/modificar_Paquete.php");
        break;
    case 11:
        $id_destino = isset($_GET['id_destino']) ? intval($_GET['id_destino']) : null;
        include("../Vista/modificar_Destino.php");
        break;
    default:
        echo "Error: valor de usuario no válido.";
}

?>

<?php
define('BASE_URL', '/Agencia/');
?>
