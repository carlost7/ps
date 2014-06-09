<?php

use DatabaseRepository as Database;

class DbsController extends \BaseController {

      protected $Database;

      public function __construct(Database $database)
      {
            $this->Database = $database;
            $this->Database->set_attributes(Session::get('dominio'));
      }

      /**
       * Display a listing of the resource.
       *
       * @return Response
       */
      public function index()
      {
            $dbs = $this->Database->listarDatabases();
            $total = $dbs->count();
            $quotas = array();
            foreach($dbs as $db){
                  $size = $this->Database->listarQuotaDB($db->nombre);
                  $quotas[$db->nombre] = $size;
            }            
            return View::make('dbs.index')->with(array('dbs' => $dbs, 'total' => $total,'quotas'=>$quotas));
      }

      /**
       * Show the form for creating a new resource.
       *
       * @return Response
       */
      public function create()
      {
            return View::make('dbs.create');
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
            if ($total >= Session::get('dominio')->plan->numero_dbs)
            {
                  Session::flash('error', 'Se alcanzó el número máximo de Bases de datos para el plan');
                  return Redirect::to('dbs');
            }
            $validator = $this->getDbsValidator();
            if ($validator->passes())
            {
                  $username = $dbs->count()+1;
                  $dbname = $dbs->count()+1;
                  $password = Input::get('password');
                  if ($this->Database->agregarDatabase($username, $password, $dbname))
                  {
                        Session::flash('message', 'Base de datos agregada con exito');
                        return Redirect::to('dbs');
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
                  return View::make('dbs.show')->with('database', $Db_model);
            }
            else
            {
                  Session::flash('la base de datos no pertenece al dominio');
                  return Redirect::to('dbs');
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
                        return Redirect::to('dbs');
                  }
                  else
                  {
                        Session::flash('error', 'Error al eliminar la base de datos');
                        return Redirect::to('dbs');
                  }
            }
            else
            {
                  Session::flash('error', 'La base de datos no pertenece al dominio');
                  return View::make('dbs');
            }
      }

      protected function getDbsValidator()
      {
            return Validator::make(Input::all(), array(
                        'password' => 'required|min:9',
                        'password' => array('regex:/^.*(?=.{8,15})(?=.*[a-z])(?=.*[A-Z])(?=.*[\d\W]).*$/'),
                        'password_confirmation' => 'required|same:password',
            ), array(
                        'password.regex' => 'La contraseña debe ser mayor de 9 caracteres. puedes utilizar mayúsculas, minúsculas, números y ¡ # $ *',
                        'password_confirmation.same' => 'Las contraseñas no concuerdan'
            ));
      }

      protected function isIdDomain($db_model)
      {
            return $db_model->dominio->id == Session::get('dominio')->id;
      }

}
