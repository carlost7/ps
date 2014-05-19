<?php

class HomeController extends BaseController {
      /*
       * Mostrar la pagina inicial
       */

      public function showWelcome()
      {
            return View::make('inicio');
      }

      /*
       * Mostrar la pagina acerca de nosotros
       */

      public function showAbout()
      {
            return View::make('acerca');
      }

      /*
       * get: mostrar la pagina de contacto
       * post: enviar un correo al dueño
       */

      public function showContacto()
      {
            if (Input::server("REQUEST_METHOD") == 'POST')
            {
                  //sendMessage
                  echo "mensaje enviado";
            }
            else
            {
                  return View::make('contacto');
            }
      }

      /*
       * Mostrar la pagina de Costos
       */

      public function showCostos()
      {
            return View::make('costos');
      }

      /*
       * Mostrar la pagina de politicas
       */

      public function showTerminos()
      {
            return View::make('terminos');
      }

      public function obtenerPass()
      {
            $length = 9;
            $available_sets = 'luds';
            $sets = array();
            if (strpos($available_sets, 'l') !== false)
            {
                  $sets[] = 'abcdefghjkmnpqrstuvwxyz';
            }
            if (strpos($available_sets, 'u') !== false)
            {
                  $sets[] = 'ABCDEFGHJKMNPQRSTUVWXYZ';
            }
            if (strpos($available_sets, 'd') !== false)
            {
                  $sets[] = '23456789';
            }
            if (strpos($available_sets, 's') !== false)
            {
                  $sets[] = '!#$*';
            }


            $all = '';
            $password = '';
            foreach ($sets as $set)
            {
                  $password .= $set[array_rand(str_split($set))];
                  $all .= $set;
            }

            $all = str_split($all);
            for ($i = 0; $i < $length - count($sets); $i++)
            {
                  $password .= $all[array_rand($all)];
            }


            $password = str_shuffle($password);

            $response = array('password'=>$password);
            
            return Response::json($response);
      }

}
