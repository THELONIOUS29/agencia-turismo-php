<?php

$valorRecibido = $_GET['campo']; 
if($valorRecibido == 1)
include("../Vista/admin.php");
if($valorRecibido == 2)
include("../Vista/ingresar_D.php");
if($valorRecibido == 3)
include("../Vista/ingresar_P.php");
if($valorRecibido == 4)
include("../Vista/buscar_admin.php");
if($valorRecibido == 5)
include("../Vista/mostrar_Destinos.php");
if($valorRecibido == 6)
include("../Vista/mostrar _Paquetes.php");
if($valorRecibido == 7)
include("../Vista/reportes.php");
if($valorRecibido == 8)
include("../Vista/perfilAD.php");
if($valorRecibido == 9)
include("../Vista/#");
if($valorRecibido == 10){
 $id_paquete = isset($_GET['id_paquete']) ? intval($_GET['id_paquete']) : null;
 include("../Vista/modificar_Paquete.php");
} 

if ($valorRecibido == 11) {
    $id_destino = isset($_GET['id_destino']) ? intval($_GET['id_destino']) : null;
    include("../Vista/modificar_Destino.php");
} 

if($valorRecibido == 12)
include("../Vista/Inicio.php");
if($valorRecibido == 13)
include("../Vista/Nosotros.php");
if($valorRecibido == 14)
include("../Vista/Destinos.php");
if($valorRecibido == 15)
include("../Vista/Blogs.php");
if($valorRecibido == 16)
include("../Vista/Paquetes.php");
if($valorRecibido == 17)
include("../Vista/Reservaciones.php");
if($valorRecibido == 18)
include("../Vista/Perfil_U.php");
if($valorRecibido == 19)
include("../Vista/#");


?>