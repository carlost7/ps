<?php

/**
 * Modelo para manejar las funciones que realiza el WHM sdk
 *
 * @author carlos
 */
class WHMFunciones {

      protected $xmlapi;
      protected $plan;

      public function __construct($plan)
      {
            $this->xmlapi = new xmlapi(Config::get('whm.host'));
            $this->xmlapi->set_user(Config::get('whm.username'));
            $this->xmlapi->set_hash(Config::get('whm.hash'));
            $this->xmlapi->set_output('json');
            $this->plan = $plan;
      }

      /*
        |--------------------------------------------------
        |    Seccion para agregar elementos al servidor
        |--------------------------------------------------
       */

      /*
       * Funcion para agregar un dominio/addon al servidor 
       */

      public function agregarDominioServidor($domain, $subdomain, $password)
      {

            if (!isset($domain) || !isset($subdomain) || !isset($password))
            {
                  Log::error('AgregarDominioServidor, Faltan datos en la funcion');
                  return false;
            }

            $response = $this->xmlapi->api2_query($this->plan->name_server, "AddonDomain", "addaddondomain", array('newdomain' => $domain, 'dir' => "public_html/" . $domain,
                  'subdomain' => $subdomain, 'pass' => $password)
            );

            $resultado = json_decode($response, true);
            if ($resultado['cpanelresult']['data'][0]['result'] == 1)
            {
                  Log::error('Agregado el dominio exitoso');
                  return true;
            }
            else
            {
                  Log::error('Error whm: AgregarDominioServidor ' . $resultado['cpanelresult']['data'][0]['reason']);
                  return false;
            }
      }

      /*
       * Agregar Correos al servidor
       */

      public function agregarCorreoServidor($domain, $email, $password)
      {

            if (!isset($domain) || !isset($email) || !isset($password))
            {
                  Log::error('AgregarCorreoServidor, Faltan datos en la funcion');
                  return false;
            }
            else
            {
                  $response = $this->xmlapi->api2_query($this->plan->name_server, 'Email', 'addpop', array('domain' => $domain, 'email' => $email, 'password' => $password, 'quota' => $this->plan->quota_correos));

                  $resultado = json_decode($response, true);
                  if ($resultado['cpanelresult']['data'][0]['result'] == 1)
                  {
                        return true;
                  }
                  else
                  {
                        Log::error('Error en WHM: AgregarCorreoServidor ' . $resultado['cpanelresult']['data'][0]['reason']);
                        Log::error('Error' . $email);
                        return false;
                  }
            }
      }

      /*
       * Agregar Forwarder al servidor;
       */

      public function agregarForwardServidor($domain, $email, $redireccion)
      {

            if (!isset($domain) || !isset($email) || !isset($redireccion))
            {
                  Log::error('AgregarForwardServidor, Faltan datos en la funcion');
                  return false;
            }
            else
            {
                  $response = $this->xmlapi->api2_query($this->plan->name_server, 'Email', 'addforward', array('domain' => $domain, 'email' => $email, 'fwdopt' => 'fwd', 'fwdemail' => $redireccion));

                  $resultado = json_decode($response, true);
                  Log::error($resultado);
                  if (!isset($resultado['cpanelresult']['error']))
                  {
                        return true;
                  }
                  else
                  {
                        Log::error('Error en WHM: AgregarForwarderServidor ' . $resultado['cpanelresult']);
                        return false;
                  }
            }
      }

      /*
       * Funcion para agregar un dominio/addon al servidor 
       */

      public function agregarFtpServidor($user_name, $home_dir, $pass)
      {
            if (!isset($user_name) || !isset($pass) || !isset($home_dir))
            {
                  Log::error('AgregarFTPServidor, Faltan datos en la funcion ' . $user_name . ' ' . $home_dir . ' ' . $pass);
                  return false;
            }
            else
            {

                  $response = $this->xmlapi->api2_query($this->plan->name_server, "Ftp", "addftp", array('pass' => $pass, 'user' => $user_name, 'quota' => $this->plan->quota_ftps, 'homedir' => $home_dir));

                  $resultado = json_decode($response, true);


                  if ($resultado['cpanelresult']['data'][0]['result'] == 1)
                  {
                        return true;
                  }
                  else
                  {
                        Log::error('Error whm: AgregarFtpServidor ' . $resultado['cpanelresult']['data'][0]['reason']);
                        return false;
                  }
            }
      }

      /*
       * Funcion para agregar un dominio/addon al servidor 
       */

      public function agregarDbServidor($username, $password, $dbname)
      {
            if (!isset($username) || !isset($password) || !isset($dbname))
            {
                  Log::error('AgregarDbServidor, Faltan datos en la funcion ' . $username . ' ' . $password . ' ' . $dbname);
                  return false;
            }


            $response = $this->xmlapi->api1_query($this->plan->name_server, "Mysql", "adduser", array($username, $password));

            if ($response != false)
            {
                  $response = $this->xmlapi->api1_query($this->plan->name_server, "Mysql", "adddb", array($dbname));
                  if ($response != false)
                  {
                        $response = $this->xmlapi->api1_query($this->plan->name_server, "Mysql", "adduserdb", array($dbname, $username, 'all'));
                        if ($response != false)
                        {
                              return true;
                        }
                        else
                        {
                              Log::error('Error whm: agregarDbServidor Error al agregar la base de datos');
                              return false;
                        }
                  }
            }

            $resultado = json_decode($response, true);
            if ($resultado['cpanelresult']['data'][0]['result'] == 1)
            {
                  return true;
            }
            else
            {
                  Log::error('Error whm: AgregarDbServidor ' . $resultado['cpanelresult']['data'][0]['reason']);
                  return false;
            }
      }

      /*
        |-----------------------------------------------
        |    Seccion para editar elementos del servidor
        |-----------------------------------------------
       */

      /*
       * Funciones para editar el password del servidor
       */

      public function editarPasswordCorreoServidor($domain, $email, $password)
      {

            if (!isset($domain) || !isset($email) || !isset($password))
            {
                  Session::flash('falta un argumento');
                  return false;
            }
            else
            {
                  $response = $this->xmlapi->api2_query($this->plan->name_server, 'Email', 'passwdpop', array('domain' => $domain, 'email' => $email, 'password' => $password));

                  $resultado = json_decode($response, true);
                  if ($resultado['cpanelresult']['data'][0]['result'] == 1)
                  {
                        return true;
                  }
                  else
                  {
                        Log::error('Error en WHM: AgregarPasswordCorreoServidor ' . $resultado['cpanelresult']['data'][0]['reason']);
                        return false;
                  }
            }
      }

      /*
       * Funciones para editar el password del servidor
       */

      public function editarPasswordFtpServidor($user, $pass)
      {

            if (!isset($user) || !isset($pass))
            {
                  Session::flash('falta un argumento');
                  return false;
            }
            else
            {
                  $response = $this->xmlapi->api2_query($this->plan->name_server, 'Ftp', 'passwd', array('user' => $user, 'pass' => $pass));

                  $resultado = json_decode($response, true);
                  if ($resultado['cpanelresult']['data'][0]['result'] == 1)
                  {
                        return true;
                  }
                  else
                  {
                        Log::error('Error en WHM: editarPasswordFtpServidor ' . $resultado['cpanelresult']['data'][0]['reason']);
                        return false;
                  }
            }
      }

      /*
        |---------------------------------------------------
        |    Seccion para eliminar del servidor
        |---------------------------------------------------
       */

      public function eliminarDominioServidor($domain, $subdomain)
      {

            if (!isset($domain) || !isset($subdomain))
            {
                  Log::error('WHMFunciones eliminarDominioServidor: faltan datos para eliminar el dominio');
                  return false;
            }
            else
            {
                  $response = $this->xmlapi->api2_query($this->plan->name_server, 'AddonDomain', 'deladdondomain', array('domain' => $domain, 'subdomain' => $subdomain));

                  $resultado = json_decode($response, true);
                  if ($resultado['cpanelresult']['data'][0]['result'] == 1)
                  {
                        return true;
                  }
                  else
                  {
                        Log::error('Error en WHM: eliminarDominioServidor ' . $resultado['cpanelresult']['data'][0]['reason']);
                        return false;
                  }
            }
      }

      /*
       * Funcion para eliminar un correo del servidor
       */

      public function eliminarCorreoServidor($domain, $username)
      {
            if (!isset($domain) || !isset($username))
            {
                  Session::flash('falta un argumento');
                  return false;
            }
            else
            {
                  $response = $this->xmlapi->api2_query($this->plan->name_server, 'Email', 'delpop', array('domain' => $domain, 'email' => $username,));

                  $resultado = json_decode($response, true);
                  if ($resultado['cpanelresult']['data'][0]['result'] == 1)
                  {
                        return true;
                  }
                  else
                  {
                        Log::error('Error en WHM: EliminarCorreoServidor ' . $resultado['cpanelresult']['data'][0]['reason']);
                        return false;
                  }
            }
      }

      /*
       * Funcion para eliminar forwarders del servidor
       */

      public function eliminarFwdServidor($email, $forward)
      {
            if (!isset($email) || !isset($forward))
            {
                  Session::flash('error', 'falta un argumento');
                  return false;
            }
            else
            {
                  $this->xmlapi->api1_query($this->plan->name_server, 'Email', 'delforward', array($email . '=' . $forward));
                  return true;
            }
      }

      /*
       * Funcion para eliminar un correo del servidor
       */

      public function eliminarFtpServidor($user, $borrar)
      {
            if (!isset($user) || !isset($borrar))
            {
                  Log::error('AgregarFTPServidor, Faltan datos en la funcion');
                  return false;
            }
            else
            {
                  $response = $this->xmlapi->api2_query($this->plan->name_server, 'Ftp', 'delftp', array('user' => $user, 'destroy' => $borrar));

                  $resultado = json_decode($response, true);
                  if ($resultado['cpanelresult']['data'][0]['result'] == 1)
                  {
                        return true;
                  }
                  else
                  {
                        Log::error('Error en WHM: EliminarFTPServidor ' . $resultado['cpanelresult']['data'][0]['reason']);
                        return false;
                  }
            }
      }

      /*
       * Funcion para eliminar un correo del servidor
       */

      public function eliminarDbServidor($username, $dbname)
      {
            if (!isset($username) || !isset($dbname))
            {
                  Log::error('AgregarDbServidor, Faltan datos en la funcion');
                  return false;
            }


            $response = $this->xmlapi->api1_query($this->plan->name_server, "Mysql", "deluserdb", array($dbname, $username));

            if ($response != false)
            {

                  $response = $this->xmlapi->api1_query($this->plan->name_server, "Mysql", "deluser", array($username));
                  if ($response != false)
                  {
                        $response = $this->xmlapi->api1_query($this->plan->name_server, "Mysql", "deldb", array($dbname));
                        if ($response != false)
                        {
                              return true;
                        }
                        else
                        {
                              Log::error('Error whm: EliminarDBServidor Error al eliminar la base de datos');
                              return false;
                        }
                  }
                  else
                  {
                        Log::error('Error whm: EliminarDBServidor Error al eliminar el usuario');
                        return false;
                  }
            }
            else
            {
                  Log::error('Error whm: EliminarDBServidor Error al eliminar el usuario de la base de datos');
                  return false;
            }
      }

      /*
        |--------------------------------
        | Obtener quotas
        |--------------------------------
       */

      public function obtenerQuotaCorreoServidor($username, $domain)
      {
            $response = $this->xmlapi->api2_query($this->plan->name_server, 'Email', 'getdiskusage', array('user' => $username, 'domain' => $domain));

            $resultado = json_decode($response, true);
            return $resultado['cpanelresult']['data']['0']['diskused'];
      }

      public function obtenerQuotaCorreosServidor($domain)
      {
            $response = $this->xmlapi->api2_query($this->plan->name_server, 'Email', 'listpopswithdisk', array('domain' => $domain));

            $resultado = json_decode($response, true);
            $quotas = array();
            if ($resultado['cpanelresult']['data'] != null)
            {
                  foreach ($resultado['cpanelresult']['data'] as $result)
                  {
                        $correo = array('diskquota' => $result['diskquota'], 'diskused' => $result['diskused']);
                        $quotas[$result['login']] = $correo;
                  }
                  return $quotas;
            }
            else
            {
                  return null;
            }
      }

}
