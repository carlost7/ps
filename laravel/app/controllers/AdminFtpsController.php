<?php

use FtpsRepository as Ftp;

class AdminFtpsController extends \BaseController {

      protected $Ftp;

      public function __construct(Ftp $ftp)
      {
            $this->Ftp = $ftp;
            $this->Ftp->set_attributes(Session::get('dominio_usuario'));
      }

      /**
       * Display a listing of the resource.
       *
       * @return Response
       */
      public function index()
      {
            $ftps = $this->Ftp->listarFtps();
            $total = sizeof($ftps);
            return View::make('admin.ftps.index')->with(array('ftps' => $ftps, 'total' => $total));
      }

      /**
       * Show the form for creating a new resource.
       *
       * @return Response
       */
      public function create()
      {
            return View::make('admin.ftps.create');
      }

      /**
       * Store a newly created resource in storage.
       *
       * @return Response
       */
      public function store()
      {
            $ftps = $this->Ftp->listarFtps();
            $total = sizeof($ftps);
            if ($total >= Session::get('dominio_usuario')->plan->numero_ftps)
            {
                  Session::flash('error', 'Se alcanzó el número máximo de ftps para el plan');
                  return Redirect::to('admin/ftps');
            }
            $validator = $this->getFtpsValidator();
            if ($validator->passes())
            {
                  $username = Input::get('username');
                  $hostname = 'primerserver.com';
                  $home_dir = Session::get('dominio_usuario')->dominio . '/' . Input::get('home_dir');
                  $password = Input::get('password');
                  if ($this->Ftp->agregarFtp($username, $hostname, $home_dir, $password, false))
                  {
                        Session::flash('message', 'FTP agregado con exito');
                        return Redirect::to('admin/ftps');
                  }
                  else
                  {
                        Session::flash('error', 'Error al crear el FTP');
                  }
            }
            return Redirect::to('admin/ftps/create')->withErrors($validator)->withInput();
      }

      /**
       * Display the specified resource.
       *
       * @param  int  $id
       * @return Response
       */
      public function show($id)
      {
            $ftp = $this->Ftp->obtenerFtp($id);
            if ($this->isIdDomain($ftp))
            {
                  return View::make('admin.ftps.show')->with('ftp', $ftp);
            }
            else
            {
                  Session::flash('El FTP no pertenece al dominio');
                  return Redirect::back();
            }
      }

      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return Response
       */
      public function edit($id)
      {
            $ftp = $this->Ftp->obtenerFtp($id);
            if ($this->isIdDomain($ftp))
            {
                  return View::make('admin.ftps.edit')->with('ftp', $ftp);
            }
            else
            {
                  Session::flash('El Ftp no pertenece al dominio');
            }
      }

      /**
       * Update the specified resource in storage.
       *
       * @param  int  $id
       * @return Response
       */
      public function update($id)
      {
            $ftp = $this->Ftp->obtenerFtp($id);
            if ($this->isIdDomain($ftp))
            {
                  $validator = $this->getEditarFtpValidator();
                  if ($validator->passes())
                  {
                        $password = Input::get('password');
                        if ($this->Ftp->editarFtp($ftp->username, $password))
                        {
                              Session::flash('message', 'Cambio de contraseña exitoso');
                              return Redirect::to('admin/ftps');
                        }
                        else
                        {
                              Session::flash('error', 'Error al cambiar la contraseña del servidor');
                        }
                  }
                  return Redirect::back()->withErrors($validator);
            }
            else
            {
                  Session::flash('error', 'El Ftp no pertenece al dominio');
                  return Redirect::back();
            }
      }

      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return Response
       */
      public function destroy($id)
      {
            $ftp = $this->Ftp->obtenerFtp($id);
            if ($this->isIdDomain($ftp))
            {

                  if ($ftp->is_principal)
                  {
                        Session::flash('No se puede eliminar el FTP principal');
                        return Redirect::to('admin/ftps');
                  }

                  if ($this->Ftp->eliminarFtp($ftp, true))
                  {
                        Session::flash('message', 'El Ftp se elimino con exito');
                  }
                  else
                  {
                        Session::flash('error', 'Error al eliminar el FTP del servidor');
                  }
                  return Redirect::to('admin/ftps');
            }
            else
            {
                  Session::flash('error', 'El Ftp no pertenece al dominio');
                  return Redirect::to('admin/ftps');
            }
      }

      protected function getFtpsValidator()
      {
            return Validator::make(Input::all(), array(
                        'username' => 'required',
                        'home_dir' => '',
                        'password' => 'required|min:4',
                        'password_confirmation' => 'required|same:password',
            ));
      }

      protected function getEditarFtpValidator()
      {
            return Validator::make(Input::all(), array(
                        'password' => 'min:4',
                        'password_confirmation' => 'same:password',
            ));
      }

      protected function isIdDomain($ftp_model)
      {
            return $ftp_model->dominio->id == Session::get('dominio_usuario')->id;
      }

}
