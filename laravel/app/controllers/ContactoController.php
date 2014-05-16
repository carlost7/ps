<?php

/**
 * Controlador para enviar y recibir datos de contacto de los clientes
 *
 * @author carlos
 */
class ContactoController extends BaseController
{

      public function enviarMensajeContacto()
      {
            $nombre = Input::get('nombre');
            $correo = Input::get('correo');
            $mensaje = Input::get('mensaje');

            $this->enviarCorreo($nombre, $correo, $mensaje);
            return Redirect::to('/');
      }

      private function enviarCorreo($nombre, $correo, $mensaje)
      {
            pass;
      }

}
