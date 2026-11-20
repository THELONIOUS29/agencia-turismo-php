<?php
require_once __DIR__ . '/../Config/session.php';
agencia_session_start(); // Inicia la sesiÃ³n
include("../Config/conexion.php");







/*
// Imprime el contenido de la sesiÃ³n
echo '<pre>';
print_r($_SESSION);
echo '</pre>';
*/
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
        <!-- fin de Sidebar Section -->

    
        <!-- Mostrar Destino -->
        <div class="contenido">
    <div class="form-container">
        <!-- AquÃ­ puedes agregar el formulario de nuevo destino si es necesario -->
    </div>

    <h3 class="title">Destinos de Viajes</h3>

    <div class="products-container">
        <?php
        // Supongamos que $conn es la conexiÃ³n a la base de datos
        $sql = "SELECT * FROM destinos";
        $resultado = mysqli_query($conn, $sql);

        if ($resultado) {
            while ($destino = mysqli_fetch_array($resultado)) {
                $id_destino = htmlspecialchars($destino['id_destino']);
                $nombre_destino = htmlspecialchars($destino['nombre_destino']);
                $descripcion = htmlspecialchars($destino['descripcion']);
                $resenas = (int) htmlspecialchars($destino['resenas']);
                $foto_destino = $destino['foto_destino']; // El contenido del archivo se almacena en LONGBLOB

                // Crear una imagen temporal para mostrar en la pÃ¡gina
                $foto_destino_src = !empty($foto_destino) ? 'data:image/jpeg;base64,' . base64_encode($foto_destino) : '../imagen/default_destination_image.jpg';

                // Generar estrellas de reseÃ±as (solo estrellas completas)
                $estrellas = '';
                for ($i = 0; $i < $resenas; $i++) {
                    $estrellas .= '<i class="fas fa-star"></i>'; // Estrella llena
                }

                // Completar hasta 5 estrellas con espacios vacÃ­os si es necesario
                for ($i = $resenas; $i < 5; $i++) {
                    $estrellas .= '<i class="far fa-star"></i>'; // Estrella vacÃ­a
                }

                echo "<div class='product' data-name='p-$id_destino'>";
                echo "<img src='$foto_destino_src' alt='Foto de destino'>";
                echo "<h3>$nombre_destino</h3>";
                echo "<div class='stars'>";
                echo $estrellas;
                echo "<span>($resenas)</span>";
                echo "</div>";
                echo "</div>";
            }
        } else {
            echo "No se encontraron destinos.";
        }
        ?>
    </div>

    <div class="products-preview">
        <?php
        if ($resultado) {
            mysqli_data_seek($resultado, 0); // Reset result pointer to the start
            while ($destino = mysqli_fetch_array($resultado)) {
                $id_destino = htmlspecialchars($destino['id_destino']);
                $nombre_destino = htmlspecialchars($destino['nombre_destino']);
                $descripcion = htmlspecialchars($destino['descripcion']);
                $resenas = (int) htmlspecialchars($destino['resenas']);
                $foto_destino = $destino['foto_destino']; // El contenido del archivo se almacena en LONGBLOB

                // Crear una imagen temporal para mostrar en la pÃ¡gina
                $foto_destino_src = 'data:image/jpeg;base64,' . base64_encode($foto_destino);

                // Generar estrellas de reseÃ±as (solo estrellas completas)
                $estrellas = '';
                for ($i = 0; $i < $resenas; $i++) {
                    $estrellas .= '<i class="fas fa-star"></i>'; // Estrella llena
                }

                // Completar hasta 5 estrellas con espacios vacÃ­os si es necesario
                for ($i = $resenas; $i < 5; $i++) {
                    $estrellas .= '<i class="far fa-star"></i>'; // Estrella vacÃ­a
                }

                echo "<div class='preview' data-target='p-$id_destino'>";
                echo "<i class='fas fa-times'></i>";
                echo "<img src='$foto_destino_src' alt='Foto de destino'>";
                echo "<h3>$nombre_destino</h3>";
                echo "<div class='stars'>";
                echo $estrellas;
                echo "<span>($resenas)</span>";
                echo "</div>";
                echo "<p>$descripcion</p>";
                echo "<div class='buttons'>";
                echo "<a href='../Controlador/Controlador.php?campo=11&id_destino=$id_destino' class='buy'>Editar</a>";
                echo "<a href='../Model/Eliminar_Destino.php?id_destino=$id_destino' class='cart' data-id='$id_destino'>Eliminar</a>";
                echo "</div>";
                echo "</div>";
            }
        }
        ?>
    </div>
</div>

 
 




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
                        <a href="../Controlador/controlador_usuario.php?user=2">Modo Usuario</a>
                        <a href="../index.php">Salir</a>
                       
                    </div>
                </div>


                 <!-- Contenedor emergente para opciones del perfil -->
                
        </div>
            <!-- fin Nav -->


    </div>

    
    
</body>

<script>
    let preveiwContainer = document.querySelector('.products-preview');
    let previewBox = preveiwContainer.querySelectorAll('.preview');

    document.querySelectorAll('.products-container .product').forEach(product => {
        product.onclick = () => {
            preveiwContainer.style.display = 'flex';
            let name = product.getAttribute('data-name');
            previewBox.forEach(preview => {
                let target = preview.getAttribute('data-target');
                if (name === target) {
                    preview.classList.add('active');
                }
            });
        };
    });

    previewBox.forEach(close => {
        close.querySelector('.fa-times').onclick = () => {
            close.classList.remove('active');
            preveiwContainer.style.display = 'none';
        };
    });
</script>

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
    --input-background: #202528;
    --input-border: #a3bdcc;
    --input-text: #edeffd;
    --input-placeholder: #a3bdcc;
    --input-focus-border: #edeffd;
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
    color: #000000;
    font-size: 14px;
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
    top: 100%; 
    right: 0; 
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







.login-button {
    display: flex;
    align-items: center;
}

.login-button button {
    border: none;
    background-color: var(--color-danger);
    padding: 0.5rem 1rem;
    border-radius: 5px;
    cursor: pointer;
    color: var(--color-white);
}

.login-button button a {
    text-decoration: none;
    color: var(--color-white);
    font-weight: 500;
}

.input-group {
    width: 100%;
    display: flex;
    flex-direction: column;
}

.input-box {
    margin-bottom: 1.5rem;
}

.input-box label {
    font-size: 1.1rem;
    font-weight: 600;
    color: var(--color-dark-variant);
    margin-bottom: 0.5rem;
    display: block;
}

.input-box input, .input-box textarea, .input-box select {
    width: 100%;
    padding: 0.8rem 1.2rem;
    border: 1px solid #ddd;
    border-radius: 10px;
    box-shadow: 1px 1px 6px #0000001c;
    font-size: 1rem;
    background-color: var(--input-background);
    color: var(--input-text);
    border: 1px solid var(--input-border);
}

.input-box input::placeholder, .input-box textarea::placeholder {
    color: var(--input-placeholder);
}

.input-box input:focus-visible, .input-box textarea:focus-visible, .input-box select:focus-visible {
    outline: 1px solid var(--input-focus-border);
}

.input-box input:hover, .input-box textarea:hover, .input-box select:hover {
    background-color: var(--input-background);
}



@media screen and (max-width: 768px) {
    .form-container {
        width: 90%;
    }

    .input-box input, .input-box textarea {
        font-size: 0.8rem;
    }
}



/*----------------CONTENEDOR 2----------------------------- */
.form-header {
    grid-column: span 2;
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 15px;
    max-width: 90%;
    background-color: var(--color-white);
}

.contenido {
    margin: 0 auto;
    padding: 2rem 1rem;
    font-size: 1rem; 
    top: 7%;
    position: relative;
    
}

.contenido .title {
    font-size: 2rem;
    color: #444;
    margin-bottom: 2%;
    text-transform: uppercase;
    text-align:left;
    /*margin: 0 12px;*/
}

.contenido .products-container {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 2rem;
    width: 125%;
    
    
}

.contenido .products-container .product {
    text-align: center;
    padding: 1.5rem 1rem;
    background: #fff;
    box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .1);
    background-color: var(--color-white);
    cursor: pointer;
    border-radius: 12px
}

.contenido .products-container .product:hover {
    outline: .2rem solid #222;
    outline-offset: 0;
}

.contenido .products-container .product img {
    height: 15rem;
    border-radius: 10px
}

.contenido .products-container .product:hover img {
    transform: scale(.9);
}

.contenido .products-container .product h3 {
    padding: .5rem 0;
    font-size: 1.5rem;
    color: var(--color-info-dark);
}

.contenido .products-container .product:hover h3 {
    color: #27ae60;
}

.contenido .products-container .product .price {
    font-size: 1.2rem; 
    color: var(--color-dark)
}

.products-preview {
    position: fixed;
    top: 0;
    left: 0;
    min-height: 100vh;
    width: 100%;
    background: rgba(0, 0, 0, .8);
    display: none;
    align-items: center;
    justify-content: center;
}

.products-preview .preview {
    display: none;
    padding: 2rem;
    text-align: center;
    background: #fff;
    position: relative;
    margin: 1rem;
    width: 26rem;
    max-width: 90%;
    word-wrap: break-word;
    border-radius: 20px;
}

.products-preview .preview.active {
    display: inline-block;
}

.products-preview .preview img {
    height: 15rem; 
    width: 15rem;
    margin: 0 auto;
    
}

.products-preview .preview .fa-times {
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    cursor: pointer;
    color: #444;
    font-size: 2rem;
}

.products-preview .preview .fa-times:hover {
    transform: rotate(90deg);
}

.products-preview .preview h3 {
    color: #444;
    padding: .5rem 0;
    font-size: 20px;
}

.products-preview .preview .stars {
    padding: 1rem 0;
    font-size: 1.2rem;
}

.products-preview .preview .stars i {
    color: #27ae60;
}

.products-preview .preview .stars span {
    color: #999;
}

.products-preview .preview p {
    line-height: 1.5;
    padding: 2px 0;
    font-size: 18px;
    color: #777;
    text-align: center;
    margin: 0 auto;
    max-width: 80%;
}

.products-preview .preview .price {
    padding: 1rem 0;
    font-size: 19px;
    color: #27ae60;
}

.products-preview .preview .buttons {
    display: flex;
    gap: 1.5rem;
    flex-wrap: wrap;
    margin-top: 1;
    justify-content: center;
    
}

.products-preview .preview .buttons a {
    padding: 5px;
    width: 27%;
    height: 12%;
    font-size: 20px;
    color: #444;
    border: .1rem solid #444;
    border-radius: 8px;
}

.products-preview .preview .buttons a.cart {
    background: #444;
    color: #fff;
}

.products-preview .preview .buttons a.cart:hover {
    background: #111;
}

.products-preview .preview .buttons a.buy:hover {
    background: #444;
    color: #fff;
}

@media (max-width: 991px) {
    html {
        font-size: 55%;
    }
}

@media (max-width: 768px) {
    .products-preview .preview img {
        height: 25rem;
    }
}

@media (max-width: 450px) {
    html {
        font-size: 50%;
    }
}

/*----------------------------------------------- */






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


////// modo oscuro
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





//////////////  perfil opciones
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
    
    // Si el clic es fuera del perfil y del menÃº de opciones, oculta el menÃº
    if (!profile.contains(event.target) && !optionsContainer.contains(event.target)) {
        optionsContainer.style.display = 'none';
    }
});





//////////////////
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.cart').forEach(function(el) {
        el.addEventListener('click', function(event) {
            event.preventDefault();
            const destinoId = this.getAttribute('data-id');
            const confirmacion = confirm('Â¿EstÃ¡s seguro de que deseas eliminar este destino?');
            if (confirmacion) {
                // Redirige a una pÃ¡gina PHP para manejar la eliminaciÃ³n
                window.location.href = `../Model/Eliminar_Destino.php?id_destino=${destinoId}`;
            }
        });
    });
});



    
</script>
