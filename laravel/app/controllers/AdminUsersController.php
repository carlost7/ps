<?php

use UsuariosRepository as Usuario;
use DominioRepository as Dominio;
use FtpsRepository as Ftp;
use CorreosRepository as Correo;
use PlanRepository as Plan;

class AdminUsersController extends \BaseController {

      protected $Usuario;
      protected $Dominio;
      protected $Ftp;
      protected $Correo;
      protected $Plan;

      public function __construct(Usuario $usuario, Dominio $dominio, Ftp $ftp, Correo $correo, Plan $plan)
      {
            $this->Usuario = $usuario;
            $this->Dominio = $dominio;
            $this->Ftp = $ftp;
            $this->Correo = $correo;
            $this->Plan = $plan;
      }

      /**
       * Display a listing of the resource.
       *
       * @return Response
       */
      public function index()
      {
            $usuarios = $this->Usuario->listarUsuarios();
            return View::make('admin.usuarios.index')->with('usuarios', $usuarios);
      }

      /**
       * Show the form for creating a new resource.
       *
       * @return Response
       */
      public function create()
      {
            $planes = $this->Plan->listarPlanes();
            return View::make('admin.usuarios.create')->with('planes', $planes);
      }

      /**
       * Store a newly created resource in storage.
       *
       * @return Response
       */
      public function store()
      {

            DB::beginTransaction();
            $validator = $this->getValidatorCreateUser();

            if ($validator->passes())
            {
                  $usuario = $this->Usuario->agregarUsuario(Input::get('nombre'), Input::get('password'), Input::get('correo'), false, false, false);
                  if ($usuario->id != null)
                  {
                        $plan = $this->Plan->obtenerPlanNombre(Input::get('plan'));
                        $dominio = $this->Dominio->agregarDominio(Input::get('dominio'), Input::get('password'), $usuario->id, $plan->id);
                        if (isset($dominio->id))
                        {
                              $this->Ftp->set_attributes($dominio);
                              $user = explode('.', $dominio->dominio);
                              $username = $user[0];
                              $hostname = 'primerserver.com';
                              $home_dir = $dominio->dominio;
                              if ($this->Ftp->agregarFtp($username, $hostname, $home_dir, Input::get('password'), true))
                              {
                                    Session::put('message', 'La cuenta esta lista para usarse');
                                    DB::commit();

                                    $data = array('dominio' => $dominio->dominio,
                                          'usuario' => $usuario->email,
                                          'password' => Input::get('password'));

                                    Mail::queue('email.nuevousuario', $data, function($message) use ($usuario) {
                                          $message->to($usuario->email, $usuario->username)->subject('Configuración de correos T7Marketing');
                                    });
                                    return Redirect::to('admin/usuarios');
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

      /**
       * Display the specified resource.
       *
       * @param  int  $id
       * @return Response
       */
      public function show($id)
      {
            $usuario = $this->Usuario->obtenerUsuario($id);
            Session::put('dominio_usuario', $usuario->dominio);
            return View::make('admin.usuarios.show')->with('usuario', $usuario);
      }

      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return Response
       */
      public function edit($id)
      {
            $usuario = $this->Usuario->obtenerUsuario($id);
            Session::put('dominio_usuario', $usuario->dominio);
            return View::make('admin.usuarios.edit')->with('usuario', $usuario);
      }

      /**
       * Update the specified resource in storage.
       *
       * @param  int  $id
       * @return Response
       */
      public function update($id)
      {
            $validator = $this->getValidatorEditUser();

            if ($validator->passes())
            {
                  $nombre = Input::get('nombre');
                  $correo = Input::get('correo');
                  $password = Input::get('password');
                  $is_activo = (Input::get('is_activo') == '1' ? 1 : 0 );
                  $is_deudor = (Input::get('is_deudor') == '1' ? 1 : 0 );
                  if ($this->Usuario->editarUsuario($id, $nombre, $password, $correo, false, $is_activo, $is_deudor))
                  {
                        Session::flash('message', 'La cuenta esta lista para usarse');                        
                        return Redirect::to('admin/usuarios');
                  }
                  else
                  {
                        Session::flash('error', "Error al editar el usuario");
                  }
            }
            return Redirect::back()->withInput()->withErrors($validator->messages());
      }

      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return Response
       */
      public function destroy($id)
      {
            $usuario = $this->Usuario->obtenerUsuario($id);
            $this->Ftp->set_attributes($usuario->dominio);
            foreach ($usuario->dominio->ftps as $ftp)
            {
                  $this->Ftp->eliminarFtp($ftp, true);
            }

            $this->Correo->set_attributes($usuario->dominio);
            foreach ($usuario->dominio->correos as $correo)
            {
                  $this->Correo->eliminarCorreo($correo);
            }

            if ($this->Dominio->eliminarDominio($usuario->dominio))
            {
                  if ($this->Usuario->eliminarUsuario($id))
                  {
                        Session::flash('message', 'Se elimino el usuario con exito');
                        return Redirect::to('admin/usuarios');
                  }
            }
            else
            {
                  Session::flash('error', 'Error al eliminar el dominio');
            }
            return Redirect::to('admin/usuarios');
      }

      protected function getValidatorCreateUser()
      {
            return Validator::make(Input::all(), array(
                        'nombre' => 'required|min:4',
                        'password' => 'required|min:2',
                        'password_confirmation' => 'required|same:password',
                        'dominio' => 'required',
                        'correo' => 'required|email|unique:user,email',
                        'plan' => 'required|exists:planes,nombre',
            ));
      }

      protected function getValidatorEditUser()
      {
            return Validator::make(Input::all(), array(
                        'nombre' => 'min:4',
                        'password' => 'min:2',
                        'password_confirmation' => 'same:password',
                        'correo' => 'email',
            ));
      }

}
