<?php

/**
 * Controlador que permite crear dominios
 *
 * @author carlos
 */
use UsuariosRepository as Usuario;
use DominioRepository as Dominio;
use FtpsRepository as Ftp;

class DominiosController extends BaseController
{

      protected $Usuario;
      protected $Dominio;
      protected $Ftp;

      public function __construct(Usuario $usuario, Dominio $dominio, Ftp $ftp)
      {
            $this->Usuario = $usuario;
            $this->Dominio = $dominio;
            $this->Ftp = $ftp;
      }

      /*
       * Pagina inicial de dominios (inicial de la aplicación
       */

      public function iniciarDominios()
      {
            return View::make('dominios.dominios');
      }

      /*
       * Carga la vista de nuevo dominio 
       */

      public function dominioNuevo()
      {
            return View::make('dominios.nuevo');
      }

      /*
       * Carga la vista de dominio existente
       */

      public function dominioExistente()
      {
            return View::make('dominios.existente');
      }

      /*
       * Comprobar si se puede agregar el dominio, se tiene que usar ajax
       */

      public function comprobarDominio()
      {
            $validator = $this->getValidatorComprobarNombreDominio();
            if ($validator->passes())
            {
                  $dominio = Input::get('dominio');
                  if (filter_var(gethostbyname($dominio), FILTER_VALIDATE_IP))
                  {
                        $resultado = false;
                        $mensaje = "El dominio " . $dominio . " ya esta siendo utilizado";
                  }
                  else
                  {
                        $resultado = true;
                        $mensaje = "El dominio " . $dominio . " es correcto";
                  }
            }
            else
            {
                  $resultado = false;
                  $mensaje = $validator->messages()->first('dominio');
            }


            $response = array('resultado' => $resultado, 'mensaje' => $mensaje);

            return Response::json($response);
      }

      protected function agregarUsuarioSistema()
      {
            DB::beginTransaction();
            $validator = $this->getValidatorConfirmUser();

            if ($validator->passes())
            {
                  $usuario = $this->Usuario->agregarUsuario(Input::get('nombre'), Input::get('password'), Input::get('correo'), false);
                  if ($usuario->id != null)
                  {
                        $plan = Plan::where('nombre', '=', Input::get('plan'))->first();
                        $dominio = $this->Dominio->agregarDominio(Input::get('dominio'), Input::get('password'), $usuario->id, $plan->id);
                        if (isset($dominio->id))
                        {
                              $this->Ftp->set_attributes($dominio);
                              $user = explode('.', $dominio->dominio);
                              $username = $user[0];
                              $hostname = 'primerserver.com';
                              $home_dir = 'public_html/' . $dominio->dominio;
                              $ftp = $this->Ftp->agregarFtp($username, $hostname, $home_dir, Input::get('password'), true);
                              if ($ftp->id)
                              {
                                    Session::put('message', 'La cuenta esta lista para usarse');
                                    DB::commit();
                                    $data = array('dominio' => $dominio->dominio,
                                          'usuario' => $usuario->email,
                                          'password' => Input::get('password'),
                                          'ftp_user' => $ftp->username,
                                          'ftp_pass' => Input::get('password'));

                                    Mail::queue('email.welcome', $data, function($message) {
                                          $message->to(Input::get('correo'), Input::get('nombre'))->subject('Bienvenido a PrimerServer');
                                    });

                                    return Redirect::to('usuario/login');
                              }
                              else
                              {
                                    Session::put('error', 'Error al agregar el FTP');
                              }
                        }
                        else
                        {
                              Session::flash('error', 'Error al agregar el dominio al servidor');
                        }
                  }
                  else
                  {
                        Session::flash('error', 'Error al agregar usuario');
                  }
            }
            DB::rollback();
            return Redirect::back()->withInput()->withErrors($validator->messages());
      }

      protected function getValidatorConfirmUser()
      {
            return Validator::make(Input::all(), array(
                          'nombre' => 'required|min:4',
                          'password' => 'required|min:2',
                          'password_confirmation' => 'required|same:password',
                          'dominio' => 'required',
                          'correo' => 'required|email|unique:user,email',
                          'plan' => 'required|exists:planes,nombre',
                          'aceptar' => 'required|accepted'
            ));
      }

      protected function getValidatorComprobarNombreDominio()
      {
            return Validator::make(Input::all(), array(
                          'dominio' => array('required'),
                          'dominio' => array('regex:/^([a-z0-9]([-a-z0-9]*[a-z0-9])?\\.)+((a[cdefgilmnoqrstuwxz]|aero|arpa)|(b[abdefghijmnorstvwyz]|biz)|(c[acdfghiklmnorsuvxyz]|cat|com|coop)|d[ejkmoz]|(e[ceghrstu]|edu)|f[ijkmor]|(g[abdefghilmnpqrstuwy]|gov)|h[kmnrtu]|(i[delmnoqrst]|info|int)|(j[emop]|jobs)|k[eghimnprwyz]|l[abcikrstuvy]|(m[acdghklmnopqrstuvwxyz]|mil|mobi|museum)|(n[acefgilopruz]|name|net)|(om|org)|(p[aefghklmnrstwy]|pro)|qa|r[eouw]|s[abcdeghijklmnortvyz]|(t[cdfghjklmnoprtvwz]|travel)|u[agkmsyz]|v[aceginu]|w[fs]|y[etu]|z[amw])$/'),
                            ), array(
                          'dominio.required' => 'Es necesario especificar un dominio',
                          'dominio.regex' => 'El dominio tiene que ser de la forma [nombredominio].[com|pais].[pais]',
                            )
            );
      }

}
