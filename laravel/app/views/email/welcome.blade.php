<!DOCTYPE html>
<html lang="es">
      <head>
            <meta charset="utf-8">
      </head>
      <body>
            <h2>Tu cuenta esta lista para usarse</h2>
            <br />
            <p>Tus datos son los siguientes:</p>
            <ul>
                  <li>Dominio: {{ $dominio }} </li>
                  <li>Usuario: {{ $usuario }}</li>                  
            </ul>
            <br />
            <p>Los datos del Ftp, para que puedas empezar a subir tus archivos y mostrarlos en internet son los siguientes</p>
            <br />
            <ul>
                  <li>Hostname: primerserver.com</li>
                  <li>Usuario: {{ $ftp_user }}</li>
                  <li>Contraseña: {{ $ftp_pass }}</li>
                  <li>Puerto: 21</li>
            </ul>
            <p>Para entrar a la aplicación y comenzar a crear tus correos electrónicos puedes usar el siguiente link</p>
            <p>{{ HTML::linkRoute('usuario/login','Primer Server') }}</p>
            <br />
            <p>El equipo de PrimerServer</p>
      </body>
</html>