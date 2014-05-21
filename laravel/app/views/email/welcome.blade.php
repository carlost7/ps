<!DOCTYPE html>
<html lang="es">
      <head>
            <meta charset="utf-8">
      </head>
      <body>
            <h2>Bienvenido a Primer Server</h2>
            <br />
            <p>Los datos de entrada a la aplicación son los siguientes</p>
            <ul>
                  <li>Dominio: {{ $dominio }} </li>
                  <li>Usuario: {{ $usuario }}</li>
                  <li>Contraseña: {{ $password }}</li>
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
            <p>Que tengas un excelente dia</p>
      </body>
</html>