<?php

use DatabaseRepository as Database;

class DbsController extends \BaseController
{

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
            return View::make('dbs.index')->with('dbs', $dbs);
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
            $dbs = $this->Database->listarDatabases;
            $total = sizeof($dbs);
            if ($total >= Session::get('dominio')->plan->numero_dbs)
            {
                  Session::flash('error', 'Se alcanzó el número máximo de Bases de datos para el plan');
                  return Redirect::to('dbs');
            }
            $validator = $this->getDbsValidator();
            if ($validator->passes())
            {
                  $username = Input::get('nombre_usuario');
                  $dbname = Input::get('db_name');
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
            if ($this->isIdDomain($db))
            {
                  if ($this->Database->eliminarDatabase($Db_model))
                  {
                        Session::flash('La base de datos fue eliminada con exito');
                        return Redirect::to('dbs');
                  }
                  else
                  {
                        Session::flash('Error al eliminar la base de datos');
                        return Redirect::to('dbs');
                  }
            }
            else
            {
                  Session::flash('La base de datos no pertenece al dominio');
                  return View::make('dbs');
            }
      }

      protected function getDbsValidator()
      {
            return Validator::make(Input::all(), array(
                          'username' => 'required',
                          'dbname' => 'required',
                          'password' => 'required|min:2',
                          'password_confirmation' => 'required|same:password',
            ));
      }

      protected function isIdDomain($db_model)
      {
            return $db_model->dominio->id == Session::get('dominio')->id;
      }

}
