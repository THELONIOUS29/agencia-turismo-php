<?php
require_once __DIR__ . '/../Config/session.php';
agencia_session_start(); // Inicia la sesiÃ³n
include("../Config/conexion.php");

function imagenBinariaSrc($foto, $fallback)
{
    if (is_resource($foto)) {
        $foto = stream_get_contents($foto);
    }

    if (is_string($foto) && $foto !== '') {
        return 'data:image/jpeg;base64,' . base64_encode($foto);
    }

    return $fallback;
}

// Verifica si hay una sesiÃ³n activa
if (isset($_SESSION['usuario'])) {
    $usuario = $_SESSION['usuario'];
    //echo 'Usuario de sesiÃ³n: ' . htmlspecialchars($usuario); // Verifica el valor de la sesiÃ³n
    
    // Usa una consulta preparada para evitar inyecciones SQL
    $stmt = $conn->prepare("SELECT usuario, rol, fotoU FROM usuarios WHERE usuario = ?");
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado && $resultado->num_rows > 0) {
        $datosUsuario = $resultado->fetch_assoc();
        // Obtiene los datos del usuario
        $_SESSION['usuario'] = isset($datosUsuario['usuario']) ? $datosUsuario['usuario'] : '';
        $_SESSION['rol'] = isset($datosUsuario['rol']) ? $datosUsuario['rol'] : '';
        $_SESSION['foto'] = isset($datosUsuario['fotoU']) ? imagenBinariaSrc($datosUsuario['fotoU'], '../Imagenes/logo2.png') : '../Imagenes/logo2.png';
    } else {
        echo 'No se encontrÃ³ el usuario en la base de datos.';
    }
    $stmt->close(); // Cierra el statement
} else {
    echo 'No hay sesiÃ³n activa.';
}
/*
// Imprime el contenido de la sesiÃ³n
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
*/



// Obtener los nuevos usuarios registrados
$stmtNewUsers = $conn->prepare("SELECT usuario, fotoU, FLOOR(EXTRACT(EPOCH FROM (NOW() - fecha_registro)) / 60) AS tiempo FROM usuarios ORDER BY fecha_registro DESC LIMIT 4");

if ($stmtNewUsers === false) {
    die('Error al preparar la consulta: ' . $conn->error);
}

$stmtNewUsers->execute();
$resultadoNewUsers = $stmtNewUsers->get_result();
$nuevosUsuarios = [];

if ($resultadoNewUsers && $resultadoNewUsers->num_rows > 0) {
    while ($row = $resultadoNewUsers->fetch_assoc()) {
        $nuevosUsuarios[] = $row;
    }
} else {
    echo 'No se encontraron nuevos usuarios.';
}
$stmtNewUsers->close(); // Cierra el statement
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="../contenido/admin.css">

</head>
<body>

    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo">
                    <img src="../Imagenes/logo2.png">
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">close</span>
                </div>
            </div>
            <div class="sidebar">
                <a href="../Controlador/Controlador.php?campo=1" class="sidebar-link" id="link-dashboard">
                    <span class="material-icons-sharp">dashboard</span>
                    <h3>Panel Principal</h3>
                </a>
                <a href="../Controlador/Controlador.php?campo=2" class="sidebar-link" id="link-ingresar">
                <span class="material-symbols-outlined">add_location_alt</span>
                    <h3>Ingresar Destinos</h3>
                </a>
                <a href="../Controlador/Controlador.php?campo=3" class="sidebar-link" id="link-paquete">
                <span class="material-symbols-outlined">assignment_add</span>
                    <h3>Ingresar Paquetes</h3>
                </a>
                <a href="../Controlador/Controlador.php?campo=4" class="sidebar-link" id="link-buscar">
                    <span class="material-symbols-outlined">search</span>
                    <h3>Buscar</h3>
                    <!-- <span class="message-count">27</span>  -->
                </a>
                <a href="../Controlador/Controlador.php?campo=5" class="sidebar-link" id="link-modificar">
                    <span class="material-icons-sharp">inventory</span>
                    <h3>Editar Destinos</h3>
                </a>
                <a href="../Controlador/Controlador.php?campo=6" class="sidebar-link" id="link-modificar_paq">
                <span class="material-symbols-outlined">edit_square</span>
                    <h3>Editar Paquetes</h3>
                </a>
                <a href="../Controlador/Controlador.php?campo=7" class="sidebar-link" id="link-reportes">
                    <span class="material-icons-sharp">report_gmailerrorred</span>
                    <h3>Reportes</h3>
                </a>
                <a href="../Controlador/Controlador.php?campo=8" class="sidebar-link" id="link-salir">
                    <span class="material-symbols-outlined">person</span>
                    <h3>Perfil</h3>
                </a>
                <a href="../index.php" class="sidebar-link" id="link-salir">
                    <span class="material-icons-sharp">logout</span>
                    <h3>Salir</h3>
                </a>
            </div>
        </aside>

        <!-- fin Sidebar Section -->

        <!-- Main Content -->
        <main>
             <!-- Analyses 
            <h1>Analytics</h1>
           
            <div class="analyse">
                <div class="sales">
                    <div class="status">
                        <div class="info">
                            <h3>Total Sales</h3>
                            <h1>$65,024</h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>+81%</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="visits">
                    <div class="status">
                        <div class="info">
                            <h3>Site Visit</h3>
                            <h1>24,981</h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>-48%</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="searches">
                    <div class="status">
                        <div class="info">
                            <h3>Searches</h3>
                            <h1>14,147</h1>
                        </div>
                        <div class="progresss">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="percentage">
                                <p>+21%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            -->
            <!-- End of Analyses -->

            <!-- New Users Section -->
<div class="new-users">
    <h2>Nuevos Usuarios</h2>
    <div class="user-list">
        <?php foreach ($nuevosUsuarios as $usuario): ?>
            <div class="user">
                <img src="<?= isset($usuario['fotoU']) ? imagenBinariaSrc($usuario['fotoU'], '../Imagenes/logo2.png') : '../Imagenes/logo2.png' ?>" alt="User Image">
                <h2><?= htmlspecialchars($usuario['usuario']) ?></h2>
                <p>
                    <?php 
                    $tiempo = intval($usuario['tiempo']);
                    if ($tiempo < 60) {
                        echo ' Hace ' . $tiempo . ' minutos';
                    } elseif ($tiempo < 1440) {
                        echo ' Hace ' . floor($tiempo / 60) . ' horas';
                    } else {
                        echo ' Hace ' . floor($tiempo / 1440) . ' dÃ­as';
                    }
                    ?>
                </p>
            </div>
        <?php endforeach; ?>
        
    </div>
</div>
<!-- fin Users Section -->

<!-- Recent Orders Table -->
<div class="recent-orders">
    <h2>Paquetes Nuevos</h2>
    <table>
        <thead>
            <tr>
                <th>Nombre del Paquete</th>
                <th>Destino</th>
                <th>Costo</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            <?php

            
            if ($conn->connect_error) {
                die("ConexiÃ³n fallida: " . $conn->connect_error);
            }

            // Consulta para obtener los paquetes y sus destinos
            $sql = "SELECT paquetes.nombre_paquete, destinos.nombre_destino, paquetes.precio
                    FROM paquetes
                    JOIN destinos ON paquetes.id_destino = destinos.id_destino";
            $resultado = $conn->query($sql);

            if ($resultado->num_rows > 0) {
                while ($fila = $resultado->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($fila['nombre_paquete']) . "</td>";
                    echo "<td>" . htmlspecialchars($fila['nombre_destino']) . "</td>";
                    echo "<td>$" . number_format($fila['precio'], 2) . "</td>";
                    echo "<td>Activo</td>"; // Texto "Activo" aÃ±adido
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='4'>No se encontraron paquetes.</td></tr>";
            }

            $conn->close();
            ?>
        </tbody>
    </table>
    <a href="#">Mostrar todos</a>
</div>



        </main>
        <!-- fin Main Content -->

        <!-- Right Section -->
        <div class="right-section">
            <div class="nav">
                <button id="menu-btn">
                    <span class="material-icons-sharp">
                        menu
                    </span>
                </button>
                <div class="dark-mode">
                    <span class="material-icons-sharp active">
                        light_mode
                    </span>
                    <span class="material-icons-sharp">
                        dark_mode
                    </span>
                </div>

                <!-- Contenedor del perfil -->
                <div class="profile" onmouseover="showOptions()" onmouseleave="hideOptions(event)">
                    <div class="info">
                        <p>Hola, <b><?php echo isset($_SESSION['usuario']) ? htmlspecialchars($_SESSION['usuario']) : 'Usuario'; ?></b></p>
                        <small class="text-muted"><?php echo isset($_SESSION['rol']) ? htmlspecialchars($_SESSION['rol']) : 'Rol desconocido'; ?></small>
                    </div>
                    <div class="profile-photo">
                        <img src="<?php echo isset($_SESSION['foto']) ? $_SESSION['foto'] : 'default-profile.png'; ?>" alt="Profile Photo" id="profile-image">
                    </div>
                    <div class="profile-menu" id="profile-menu">
                        <a href="../Controlador/Controlador.php?campo=8">Perfil</a>
                        <a href="../Controlador/Controlador.php?campo=18">Modo Usuario</a>
                        <a href="../index.php">Salir</a>
                       
                    </div>
                </div>




                

                 <!-- Contenedor emergente para opciones del perfil -->
                

            </div>
            <!-- fin Nav -->

        
                <div class="reminders">
                    <div class="header">
                        <h2>Recordatorios de Viaje</h2>
                        <span class="material-icons-sharp">
                            notifications_none
                        </span>
                    </div>

                    <div class="notification">
                        <div class="icon">
                            <span class="material-icons-sharp">
                                volume_up
                            </span>
                        </div>
                        <div class="content">
                            <div class="info">
                                <h3>Vuelo</h3>
                                <small class="text_muted">
                                    08:00 AM - 12:00 PM
                                </small>
                            </div>
                            <span class="material-icons-sharp">
                                more_vert
                            </span>
                        </div>
                    </div>

                    <div class="notification deactive">
                        <div class="icon">
                            <span class="material-icons-sharp">
                                edit
                            </span>
                        </div>
                        <div class="content">
                            <div class="info">
                                <h3>Hotel</h3>
                                <small class="text_muted">
                                    02:00 PM - 03:00 PM
                                </small>
                            </div>
                            <span class="material-icons-sharp">
                                more_vert
                            </span>
                        </div>
                    </div>

                    <div class="notification add-reminder">
                        <div>
                            <span class="material-icons-sharp">
                                add
                            </span>
                            <h3>Agregar Recordatorio</h3>
                        </div>
                    </div>
                </div>


            </div>

        </div>


    </div>

    
    
</body>
</html>


<style>
@import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap');

:root{
    --color-primary: #6C9BCF;
    --color-danger: #FF0060;
    --color-success: #1B9C85;
    --color-ig:#2c4d2a;;
    --color-warning: #F7D060;
    --color-white: #fff;
    --color-info-dark: #7d8da1;
    --color-dark: #363949;
    --color-light: rgba(132, 139, 200, 0.18);
    --color-dark-variant: #677483;
    --color-background: #f6f6f9;

    --card-border-radius: 2rem;
    --border-radius-1: 0.4rem;
    --border-radius-2: 1.2rem;

    --card-padding: 1.8rem;
    --padding-1: 1.2rem;

    --box-shadow: 0 2rem 3rem var(--color-light);
}

.dark-mode-variables{
    --color-background: #181a1e;
    --color-white: #202528;
    --color-dark: #edeffd;
    --color-dark-variant: #a3bdcc;
    --color-light: rgba(0, 0, 0, 0.4);
    --box-shadow: 0 2rem 3rem var(--color-light);
}

*{
    margin: 0;
    padding: 0;
    outline: 0;
    appearance: 0;
    border: 0;
    text-decoration: none;
    box-sizing: border-box;
}

html{
    font-size: 16px;
}

body{
    width: 100vw;
    height: 100vh;
    font-family: 'Poppins', sans-serif;
    font-size: 0.88rem;
    overflow-x: hidden;
    color: var(--color-dark);
    background-color: var(--color-background);
}

a{
    color: var(--color-dark);
}

img{
    display: block;
    width: 100%;
    /*object-fit: cover;*/
}

h1{
    font-weight: 800;
    font-size: 1.8rem;
}

h2{
    font-weight: 600;
    font-size: 1.4rem;
}

h3{
    font-weight: 500;
    font-size: 1rem;
}

small{
    font-size: 0.76rem;
}

p{
    color: var(--color-dark-variant);
    font-size: 17px;
}

b{
    color: var(--color-dark);
}

.text-muted{
    color: var(--color-info-dark);
}

.primary{
    color: var(--color-primary);
}

.danger{
    color: var(--color-danger);
}

.success{
    color: var(--color-success);
}

.warning{
    color: var(--color-warning);
}

.container{
    display: grid;
    width: 90%;
    margin: 0 auto;
    gap: 4.8rem;
    grid-template-columns: 16rem auto 23rem;
}



/* Estilos para el contenedor del perfil y el menÃº de opciones */
.profile {
    position: relative;
    display: inline-block;
}

.profile-menu {
    display: none;
    position: absolute;
    top: 100%; /* Posiciona el menÃº justo debajo de la imagen */
    right: 0; /* Alinea el menÃº a la derecha del contenedor */
    background-color: white;
    border: 1px solid #ddd;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    border-radius: 5px;
    padding: 10px;
    z-index: 1000;
    min-width: 150px;
}

.profile-menu a {
    display: block;
    padding: 10px;
    color: black;
    text-decoration: none;
}

.profile-menu a:hover {
    background-color: #f5f5f5;
}

/* Estilos para el perfil */
.profile-photo img {
    cursor: pointer;
}







aside {
    height: 100vh;
}

aside .toggle {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-top: 1.4rem;
}

aside .toggle .logo {
    display: flex;
    gap: 0.5rem;
    margin: auto;
}

aside .toggle .logo img {
    width: 11rem;
    height: 5rem;
}

aside .toggle .close {
    padding-right: 1rem;
    display: none;
}

aside .sidebar {
    display: flex;
    flex-direction: column;
    background-color: var(--color-white);
    box-shadow: var(--box-shadow);
    border-radius: 15px;
    height: 80vh;
    position: relative;
    top: 1.5rem;
    transition: all 0.3s ease;
}

aside .sidebar:hover {
    box-shadow: none;
}

aside .sidebar a {
    display: flex;
    align-items: center;
    color: var(--color-info-dark);
    height: 2.7rem;
    gap: 1rem;
    position: relative;
    margin-left: 2rem;
    transition: all 0.3s ease;
    margin-top: 7%;
}

aside .sidebar a span {
    font-size: 1.6rem;
    transition: all 0.3s ease;
}

aside .sidebar a:last-child {
    position: absolute;
    bottom: 2rem;
    width: 100%;
}

aside .sidebar a.active {
    width: 100%;
    color: var(--color-primary);
    background-color: var(--color-light);
    margin-left: 0;
}

aside .sidebar a.active::before {
    content: '';
    width: 6px;
    height: 18px;
    background-color: var(--color-primary);
}

aside .sidebar a.active span {
    color: var(--color-primary);
    margin-left: calc(1rem - 3px);
}

aside .sidebar a:hover {
    color: var(--color-primary);
}

aside .sidebar a:hover span {
    margin-left: 0.6rem;
}

aside .sidebar .message-count {
    background-color: var(--color-danger);
    padding: 2px 6px;
    color: var(--color-white);
    font-size: 11px;
    border-radius: var(--border-radius-1);
}



main{
    margin-top: 6.0rem;
}

main .analyse{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.6rem;
}

main .analyse > div{
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    margin-top: 1rem;
    box-shadow: var(--box-shadow);
    cursor: pointer;
    transition: all 0.3s ease;
}

main .analyse > div:hover{
    box-shadow: none;
}

main .analyse > div .status{
    display: flex;
    align-items: center;
    justify-content: space-between;
}

main .analyse h3{
    margin-left: 0.6rem;
    font-size: 1rem;
}

main .analyse .progresss{
    position: relative;
    width: 92px;
    height: 92px;
    border-radius: 50%;
}

main .analyse svg{
    width: 7rem;
    height: 7rem;
}

main .analyse svg circle{
    fill: none;
    stroke-width: 10;
    stroke-linecap: round;
    transform: translate(5px, 5px);
}

main .analyse .sales svg circle{
    stroke: var(--color-success);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .visits svg circle{
    stroke: var(--color-danger);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .searches svg circle{
    stroke: var(--color-primary);
    stroke-dashoffset: -30;
    stroke-dasharray: 200;
}

main .analyse .progresss .percentage{
    position: absolute;
    top: -3px;
    left: -1px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
}

main .new-users{
    margin-top: 1.3rem;
}

main .new-users .user-list{
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    margin-top: 1rem;
    box-shadow: var(--box-shadow);
    display: flex;
    justify-content: space-around;
    flex-wrap: wrap;
    gap: 1.4rem;
    cursor: pointer;
    transition: all 0.3s ease;
}

main .new-users .user-list:hover{
    box-shadow: none;
}

main .new-users .user-list .user{
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

main .new-users .user-list .user img{
    width: 5rem;
    height: 5rem;
    margin-bottom: 0.4rem;
    border-radius: 50%;
}

main .recent-orders{
    margin-top: 1.3rem;
}

main .recent-orders h2{
    margin-bottom: 0.8rem;
}

main .recent-orders table{
    background-color: var(--color-white);
    width: 100%;
    padding: var(--card-padding);
    text-align: center;
    box-shadow: var(--box-shadow);
    border-radius: var(--card-border-radius);
    transition: all 0.3s ease;
}

main .recent-orders table:hover{
    box-shadow: none;
}

main table tbody td{
    height: 2.8rem;
    border-bottom: 1px solid var(--color-light);
    color: var(--color-dark-variant);
}

main table tbody tr:last-child td{
    border: none;
    width: 2.5%;
    margin-bottom: 0 auto;
}

main .recent-orders a{
    text-align: center;
    display: block;
    margin: 1rem auto;
    color: var(--color-primary);
}

.right-section{
    margin-top: 3.4rem;
}

.right-section .nav{
    display: flex;
    justify-content: end;
    gap: 2rem;
}

.right-section .nav button{
    display: none;
}

.right-section .dark-mode{
    background-color: var(--color-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
    height: 1.6rem;
    width: 4.2rem;
    cursor: pointer;
    border-radius: var(--border-radius-1);
}

.right-section .dark-mode span{
    font-size: 1.2rem;
    width: 50%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.right-section .dark-mode span.active{
    background-color: var(--color-primary);
    color: white;
    border-radius: var(--border-radius-1);
}

.right-section .nav .profile{
    display: flex;
    gap: 2rem;
    text-align: right;
}

.right-section .nav .profile .profile-photo{
    width: 3.8rem;
    height: 3.8rem;
    border-radius: 50%;
    overflow: hidden;
}

.right-section .user-profile{
    display: flex;
    justify-content: center;
    text-align: center;
    margin-top: 1rem;
    background-color: var(--color-white);
    padding: var(--card-padding);
    border-radius: var(--card-border-radius);
    box-shadow: var(--box-shadow);
    cursor: pointer;
    transition: all 0.3s ease;
}

.right-section .user-profile:hover{
    box-shadow: none;
}

.right-section .user-profile img{
    width: 11rem;
    height: auto;
    margin-bottom: 0.8rem;
    border-radius: 50%;
}

.right-section .user-profile h2{
    margin-bottom: 0.2rem;
}

.right-section .reminders{
    margin-top: 2rem;
}

.right-section .reminders .header{
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 0.8rem;
}

.right-section .reminders .header span{
    padding: 10px;
    box-shadow: var(--box-shadow);
    background-color: var(--color-white);
    border-radius: 50%;
}

.right-section .reminders .notification{
    background-color: var(--color-white);
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 0.7rem;
    padding: 1.4rem var(--card-padding);
    border-radius: var(--border-radius-2);
    box-shadow: var(--box-shadow);
    cursor: pointer;
    transition: all 0.3s ease;
}

.right-section .reminders .notification:hover{
    box-shadow: none;
}

.right-section .reminders .notification .content{
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin: 0;
    width: 100%;
}

.right-section .reminders .notification .icon{
    padding: 0.6rem;
    color: var(--color-white);
    background-color: var(--color-success);
    border-radius: 20%;
    display: flex;
}

.right-section .reminders .notification.deactive .icon{
    background-color: var(--color-danger);
}

.right-section .reminders .add-reminder{
    background-color: var(--color-white);
    border: 2px dashed var(--color-primary);
    color: var(--color-primary);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.right-section .reminders .add-reminder:hover{
    background-color: var(--color-primary);
    color: white;
}

.right-section .reminders .add-reminder div{
    display: flex;
    align-items: center;
    gap: 0.6rem;
}

@media screen and (max-width: 1200px) {
    .container{
        width: 95%;
        grid-template-columns: 7rem auto 23rem;
    }

    aside .logo h2{
        display: none;
    }

    aside .sidebar h3{
        display: none;
    }

    aside .sidebar a{
        width: 5.6rem;
    }

    aside .sidebar a:last-child{
        position: relative;
        margin-top: 1.8rem;
    }

    main .analyse{
        grid-template-columns: 1fr;
        gap: 0;
    }

    main .new-users .user-list .user{
        flex-basis: 40%;
    }

    main .recent-orders {
        width: 94%;
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        margin: 2rem 0 0 0.8rem;
    }

    main .recent-orders table{
        width: 83vw;
    }

    main table thead tr th:last-child,
    main table thead tr th:first-child{
        display: none;
    }

    main table tbody tr td:last-child,
    main table tbody tr td:first-child{
        display: none;
    }

}

@media screen and (max-width: 768px) {
    .container{
        width: 100%;
        grid-template-columns: 1fr;
        padding: 0 var(--padding-1);
    }

    aside{
        position: fixed;
        background-color: var(--color-white);
        width: 15rem;
        z-index: 3;
        box-shadow: 1rem 3rem 4rem var(--color-light);
        height: 100vh;
        left: -100%;
        display: none;
        animation: showMenu 0.4s ease forwards;
    }

    @keyframes showMenu {
       to{
        left: 0;
       } 
    }

    aside .logo{
        margin-left: 1rem;
    }

    aside .logo h2{
        display: inline;
    }

    aside .sidebar h3{
        display: inline;
    }

    aside .sidebar a{
        width: 100%;
        height: 3.4rem;
    }

    aside .sidebar a:last-child{
        position: absolute;
        bottom: 5rem;
    }

    aside .toggle .close{
        display: inline-block;
        cursor: pointer;
    }

    main{
        margin-top: 8rem;
        padding: 10 6rem;
       
    }

    main .new-users .user-list .user{
        flex-basis: 35%;
    }

    main .recent-orders{
        position: relative;
        margin: 3rem 0 0 0;
        width: 100%;
    }

    main .recent-orders table{
        width: 100%;
        margin: 0;
    }

    .right-section{
        width: 94%;
        margin: 0 auto 4rem;
    }

    .right-section .nav{
        position: fixed;
        top: 0;
        left: 0;
        align-items: center;
        background-color: var(--color-white);
        padding: 0 var(--padding-1);
        height: 4.6rem;
        width: 100%;
        z-index: 2;
        box-shadow: 0 1rem 1rem var(--color-light);
        margin: 0;
    }

    .right-section .nav .dark-mode{
        width: 4.4rem;
        position: absolute;
        left: 66%;
    }

    .right-section .profile .info{
        display: none;
    }

    .right-section .nav button{
        display: inline-block;
        background-color: transparent;
        cursor: pointer;
        color: var(--color-dark);
        position: absolute;
        left: 1rem;
    }

    .right-section .nav button span{
        font-size: 2rem;
    }

}

</style>




<script>
///////// BARRA LATERAL/////
document.addEventListener('DOMContentLoaded', function () {
    const sidebarLinks = document.querySelectorAll('.sidebar-link');
    const currentUrl = window.location.href;

    sidebarLinks.forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add('active');
        }
    });
});



//////////////////////////

const sideMenu = document.querySelector('aside');
const menuBtn = document.getElementById('menu-btn');
const closeBtn = document.getElementById('close-btn');

const darkMode = document.querySelector('.dark-mode');

menuBtn.addEventListener('click', () => {
    sideMenu.style.display = 'block';
});

closeBtn.addEventListener('click', () => {
    sideMenu.style.display = 'none';
});

darkMode.addEventListener('click', () => {
    document.body.classList.toggle('dark-mode-variables');
    darkMode.querySelector('span:nth-child(1)').classList.toggle('active');
    darkMode.querySelector('span:nth-child(2)').classList.toggle('active');
})


Orders.forEach(order => {
    const tr = document.createElement('tr');
    const trContent = `
        <td>${order.productName}</td>
        <td>${order.productNumber}</td>
        <td>${order.paymentStatus}</td>
        <td class="${order.status === 'Declined' ? 'danger' : order.status === 'Pending' ? 'warning' : 'primary'}">${order.status}</td>
        <td class="primary">Details</td>
    `;
    tr.innerHTML = trContent;
    document.querySelector('table tbody').appendChild(tr);
});





//////////////
//////////////
function showOptions() {
    document.getElementById('profile-menu').style.display = 'block';
}

function hideOptions(event) {
    var optionsContainer = document.getElementById('profile-menu');
    var profile = document.querySelector('.profile');
    
    // Verifica si el mouse estÃ¡ fuera del contenedor del perfil y del menÃº de opciones
    if (!profile.contains(event.relatedTarget) && !optionsContainer.contains(event.relatedTarget)) {
        optionsContainer.style.display = 'none';
    }
}

// Ocultar el menÃº si se hace clic fuera de Ã©l
document.addEventListener('click', function(event) {
    var optionsContainer = document.getElementById('profile-menu');
    var profile = document.querySelector('.profile');
    
    
    if (!profile.contains(event.target) && !optionsContainer.contains(event.target)) {
        optionsContainer.style.display = 'none';
    }
});
    
    
</script>

