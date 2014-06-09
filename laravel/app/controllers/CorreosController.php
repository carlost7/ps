<?php

use CorreosRepository as Correo;

class CorreosController extends \BaseController {

      protected $Correo;

      public function __construct(Correo $correo)
      {
            $this->Correo = $correo;
            $this->Correo->set_attributes(Session::get('dominio'));
      }

      /**
       * Display a listing of the resource.
       *
       * @return Response
       */
      public function index()
      {
            $correos = $this->Correo->listarCorreos();
            $total = sizeof($correos);
            $quotas = $this->Correo->listarQuotas();
            return View::make('correos.index')->with(array('correos' => $correos, 'quotas' => $quotas, 'total' => $total));
      }

      /**
       * Show the form for creating a new resource.
       *
       * @return Response
       */
      public function create()
      {
            return View::make('correos.create');
      }

      /**
       * Store a newly created resource in storage.
       *
       * @return Response
       */
      public function store()
      {
            $correos = $this->Correo->listarCorreos();
            $total = sizeof($correos);
            if ($total > Session::get('dominio')->plan->numero_correos)
            {
                  Session::flash('error', 'Se alcanzó el número máximo de correos para el plan');
                  return Redirect::to('correos');
            }
            $validator = $this->getCorreosValidator();
            if ($validator->passes())
            {
                  $nombre = Input::get('nombre');
                  if (strpos(Input::get('correo'), '@'))
                  {
                        Session::flash('error', 'El campo correo solo debe contener el nombre de usuario');
                        return Redirect::to('correos/create')->withInput();
                  }
                  $correo = Input::get('correo') . '@' . Session::get('dominio')->dominio;
                  $redireccion = Input::get('redireccion');
                  $password = Input::get('password');
                  if ($this->Correo->agregarCorreo($nombre, $correo, $redireccion, $password))
                  {
                        Session::flash('message', 'Correo Agregado con exito');
                        return Redirect::to('correos');
                  }
                  else
                  {
                        Session::flash('error', 'Error al crear el correo');
                  }
            }
            return Redirect::to('correos/create')->withErrors($validator)->withInput();
      }

      /**
       * Display the specified resource.
       *
       * @param  int  $id
       * @return Response
       */
      public function show($id)
      {
            $correo = $this->Correo->obtenerCorreo($id);
            if ($this->isIdDomain($correo))
            {
                  $used_quota = $this->Correo->obtenerUsedQuota($correo);
                  return View::make('correos.show')->with(array('correo' => $correo, 'used_quota' => $used_quota));
            }
            else
            {
                  Session::flash('El Correo no pertenece al dominio');
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

            $correo = $this->Correo->obtenerCorreo($id);
            if ($this->isIdDomain($correo))
            {
                  return View::make('correos.edit')->with('correo', $correo);
            }
            else
            {
                  Session::flash('El Correo no pertenece al dominio');
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
            $correo = $this->Correo->obtenerCorreo($id);
            if ($this->isIdDomain($correo))
            {
                  $validator = $this->getEditCorreosValidator();
                  if ($validator->passes())
                  {
                        $redireccion = Input::get('redireccion');
                        $password = Input::get('password');
                        if ($this->Correo->editarCorreo($correo, $password, $redireccion))
                        {
                              Session::flash('message', 'Correo editado con exito');
                              return Redirect::to('correos');
                        }
                        else
                        {
                              Session::flash('error', 'Error al editar el correo');
                        }
                  }
                  return Redirect::to('correos/' . $id . '/edit')->withErrors($validator)->withInput();
            }
            else
            {
                  Session::flash('El Correo no pertenece al dominio');
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
            $correo = $this->Correo->obtenerCorreo($id);
            if ($this->isIdDomain($correo))
            {
                  if ($this->Correo->eliminarCorreo($correo))
                  {
                        Session::flash('message', 'Se elimino el correo');
                        return Redirect::to('correos');
                  }
                  else
                  {
                        Session::flash('error', 'Error al eliminar el correo');
                        return Redirect::to('correos');
                  }
            }
            else
            {
                  Session::flash('error', 'El correo no pertenece al dominio');
                  return Redirect::to('correos');
            }
      }

      /*
       * Validacion de agregar correo
       */

      protected function getCorreosValidator()
      {
            return Validator::make(Input::all(), array(
                        'nombre' => 'required|min:4',
                        'correo' => 'required',
                        'password' => 'required|min:9',
                        'password' => array('regex:/^.*(?=.{8,15})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).*$/'),
                        'password_confirmation' => 'required|same:password',
                        'redireccion' => 'email',
                        ), array(
                        'password.regex' => 'La contraseña debe ser mayor de 9 caracteres. puedes utilizar mayúsculas, minúsculas, números y ¡ # $ *',
                        'password_confirmation.same' => 'Las contraseñas no concuerdan'
            ));
      }

      /*
       * Validacion de editar Correo
       */

      protected function getEditCorreosValidator()
      {
            return Validator::make(Input::all(), array(
                        'password' => 'min:9',
                        'password' => array('regex:/^.*(?=.{8,15})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).*$/'),
                        'password_confirmation' => 'same:password',
                        'redireccion' => 'email',
                        ), array(
                        'password.regex' => 'La contraseña debe ser mayor de 9 caracteres. puedes utilizar mayúsculas, minúsculas, números y ¡ # $ *',
                        'password_confirmation.same' => 'Las contraseñas no concuerdan'
            ));
      }

      /*
       * Validar id perteneciente al usuario
       */

      public function isIdDomain($correo_model)
      {
            return $correo_model->dominio->id == Session::get('dominio')->id;
      }

}
