<?php

use UsuariosRepository as Usuario;
use DominioRepository as Dominio;
use FtpsRepository as Ftp;
use CorreoRepository as Correo;

class AdminUsersController extends \BaseController {

      protected $Usuario;
      protected $Dominio;
      protected $Ftp;
      protected $Correo;

      public function __construct(Usuario $usuario, Dominio $dominio, Ftp $ftp, Correo $correo)
      {
            $this->Usuario = $usuario;
            $this->Dominio = $dominio;
            $this->Ftp = $ftp;
            $this->Correo = $correo;
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

            return View::make('admin.usuarios.create');
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

}
