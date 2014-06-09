<?php

use DatabaseRepository as Database;

class AdminDbsController extends \BaseController {

      protected $Database;

      public function __construct(Database $database)
      {
            $this->Database = $database;
            $this->Database->set_attributes(Session::get('dominio_usuario'));
      }

      /**
       * Display a listing of the resource.
       *
       * @return Response
       */
      public function index()
      {
            if (Session::get('dominio_usuario')->plan->numero_dbs > 0)
            {
                  $dbs = $this->Database->listarDatabases();
                  $total = $dbs->count();
                  $quotas = array();
                  foreach ($dbs as $db)
                  {
                        $size = $this->Database->listarQuotaDB($db->nombre);
                        $quotas[$db->nombre] = $size;
                  }
                  return View::make('admin.dbs.index')->with(array('dbs' => $dbs, 'total' => $total, 'quotas' => $quotas));
            }
            else
            {
                  Session::flash('error' . 'El plan del usuario no tiene bases de datos');
                  return Redirect::back();
            }
      }

      /**
       * Show the form for creating a new resource.
       *
       * @return Response
       */
      public function create()
      {
            return View::make('admin.dbs.create');
      }

      /**
       * Store a newly created resource in storage.
       *
       * @return Response
       */
      public function store()
      {
            $dbs = $this->Database->listarDatabases();
            $total = sizeof($dbs);
            if ($total >= Session::get('dominio_usuario')->plan->numero_dbs)
            {
                  Session::flash('error', 'Se alcanzó el número máximo de Bases de datos para el plan');
                  return Redirect::to('admin/dbs');
            }
            $validator = $this->getDbsValidator();
            if ($validator->passes())
            {
                  $username = $dbs->count() + 1;
                  $dbname = $dbs->count() + 1;
                  $password = Input::get('password');
                  if ($this->Database->agregarDatabase($username, $password, $dbname))
                  {
                        Session::flash('message', 'Base de datos agregada con exito');
                        return Redirect::to('admin/dbs');
                  }
                  else
                  {
                        Session::flash('error', 'Error al crear la base de datos');
                  }
            }
            return Redirect::to('dbs/create')->withErrors($validator)->withInput();
      }

      /**
       * Display the specified resource.
       *
       * @param  int  $id
       * @return Response
       */
      public function show($id)
      {
            $Db_model = $this->Database->obtenerDatabase($id);
            if ($this->isIdDomain($Db_model))
            {
                  return View::make('admin.dbs.show')->with('database', $Db_model);
            }
            else
            {
                  Session::flash('la base de datos no pertenece al dominio');
                  return Redirect::to('admin/dbs');
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
            $Db_model = $this->Database->obtenerDatabase($id);
            if ($this->isIdDomain($Db_model))
            {
                  if ($this->Database->eliminarDatabase($Db_model))
                  {
                        Session::flash('message', 'La base de datos fue eliminada con exito');
                        return Redirect::to('admin/dbs');
                  }
                  else
                  {
                        Session::flash('error', 'Error al eliminar la base de datos');
                        return Redirect::to('admin/dbs');
                  }
            }
            else
            {
                  Session::flash('error', 'La base de datos no pertenece al dominio');
                  return View::make('admin.dbs.index');
            }
      }

      protected function getDbsValidator()
      {
            return Validator::make(Input::all(), array(
                        'password' => 'required|min:9',
                        'password' => array('regex:/^.*(?=.{8,15})(?=.*[a-z])(?=.*[A-Z]).*$/'),
                        'password_confirmation' => 'required|same:password',
            ), array(
                        'password.regex' => 'La contraseña debe ser mayor de 9 caracteres. puedes utilizar mayúsculas, minúsculas, números y ¡ # $ *',
                        'password_confirmation.same' => 'Las contraseñas no concuerdan'
            ));
      }

      protected function isIdDomain($db_model)
      {
            return $db_model->dominio->id == Session::get('dominio_usuario')->id;
      }

}
