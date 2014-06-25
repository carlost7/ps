<!DOCTYPE html>
<html lang="es">
      <head>
            <meta charset="utf-8">
      </head>
      <body>
            <h2>Recuperación de password</h2>
            <div>
                  Para regenerar tu password entra en el siguiente link: 

                  <a href="{{ URL::route("usuario/reset", compact("token")) }}">Regenerar contraseña</a>
            </div>
      </body>
</html>
