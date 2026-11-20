<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingreso</title>
    <link rel="stylesheet" href="../RegistroU/ingreso.css">
</head>

<body>
    <main>
      <div class="caja">
        <div class="caja-interna">
          <div class="formularios">
            <form action="./login.php" autocomplete="off" method="post" class="formulario-inicio-sesion">
              <div class="logo">
                  <img src="./img/logo2.png" alt="logo" />
                  <h4></h4>
              </div>

              <div class="encabezado">
                  <h2>Bienvenido</h2>
                  <h6>¿No estás registrado aún?</h6>
                  <a href="#" class="cambiar">Regístrate</a>
              </div>

              <div class="formulario-real">
                  <div class="campo-input">
                      <input type="text" name="usuario" minlength="4" class="campo" autocomplete="off" required />
                      <label>Nombre</label>
                  </div>

                  <div class="campo-input">
                      <input type="password" name="contra" minlength="4" class="campo" autocomplete="off" required />
                      <label>Contraseña</label>
                  </div>

                  <?php
                // Mostrar el mensaje si existe
                if (isset($_GET['message'])) {
                    echo '<p class="mensaje">' . $_GET['message'] . '</p>';
                }
                ?>
                <br>

                  <input type="submit" value="Iniciar Sesión" class="btn-inicio-sesion" />

                  <p class="tex">
                      ¿Olvidaste tu contraseña o tus datos de inicio de sesión?
                      <a href="#">Obtén ayuda</a> para iniciar sesión.
                  </p>
              </div>
          </form>
  
          <form action="./registro.php" autocomplete="off" method="post" class="formulario-registro">
            <div class="logo">
                <img src="./img/logo2.png" alt="" />
                <h4></h4>
            </div>
  
              <div class="encabezado">
                <h2>¡Empieza Aquí!</h2>
                <h6>¿Ya tienes una cuenta?</h6>
                <a href="#" class="cambiar">Inicia Sesión</a>
              </div>
  
              <div class="formulario-real">
                <div class="campo-input">
                  <input type="text" name="usuario" minlength="4" class="campo" autocomplete="off" required />
                  <label>Nombre</label>
              </div>
  
              <div class="campo-input">
                <input type="email" name="correo" class="campo" autocomplete="off" required />
                <label>Email</label>
            </div>

            <div class="campo-input">
                <input type="password" name="contra" minlength="4" class="campo" autocomplete="off" required />
                <label>Contraseña</label>
            </div>

  
              <input type="submit" value="Regístrate" class="btn-registro" />
  
                <p class="texto">
                  Al registrarte, acepto los
                  <a href="#">Términos de Servicio</a> y
                  <a href="#">Política de Privacidad</a>
                </p>
              </div>
            </form>
          </div>
  
          <div class="carrusel">
            <div class="imagenes-envueltas">
              <img src="./img/tr_1.jpg" class="imagen img-1 mostrar" alt="" />
              <img src="./img/tr_2.jpg" class="imagen img-2" alt="" />
              <img src="./img/tr_3.jpg" class="imagen img-3" alt="" />
            </div>
          
            <div class="deslizador-texto">
              <div class="texto-envuelto">
                <div class="grupo-texto">
                  <h2 class="texto texto-1">"La vida es corta y el mundo es amplio."</h2>
                  <h2 class="texto texto-2">"La mejor inversión que puedes hacer es en viajar."</h2>
                  <h2 class="texto texto-3">"Viajar abre tu mente y enriquece tu alma."</h2>
                </div>
              </div>
          
              <div class="flechas">
                <span class="activo" data-value="1"></span>
                <span data-value="2"></span>
                <span data-value="3"></span>
              </div>
            </div>
          </div>
          


        </div>
      </div>
    </main>
  
    <!-- Archivo Javascript -->
    <script src="../RegistroU/ingreso.js"></script>
</body>
  
</html>