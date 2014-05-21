<!DOCTYPE html>
<html lang="es">
      <head>
            <meta charset="utf-8">
      </head>
      <body>
            <h2>Bienvenido a t7marketing</h2>
            <div>
                  <p>
                        Hemos creado una cuenta en T7marketing para que pueda configurar sus correos electrónicos
                  </p>

                  <p>
                        Para hacerlo de click en el siguiente enlace <a href="{{ URL::route("usuario/login") }}">T7marketing</a>                        
                  </p>

                  <ul>
                        <li>
                              Dominio: {{ $dominio }}
                        </li>
                        <li>
                              Usuario: {{ $usuario }}
                        </li>
                        <li>
                              Password: {{ $password }}
                        </li>
                  </ul>
                  
                  <p>El equipo de T7Marketing</p>
            </div>
      </body>
</html>
