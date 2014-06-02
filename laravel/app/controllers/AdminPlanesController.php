<?php

use PlanRepository as Plan;

class AdminPlanesController extends \BaseController {

      protected $Plan;

      public function __construct(Plan $plan)
      {
            parent::__construct();
            $this->Plan = $plan;
      }

      /**
       * Display a listing of the resource.
       *
       * @return Response
       */
      public function index()
      {
            $planes = $this->Plan->listarPlanes();
            return View::make('admin.planes.index')->with('planes', $planes);
      }

      /**
       * Show the form for creating a new resource.
       *
       * @return Response
       */
      public function create()
      {
            return View::make('admin.planes.create');
      }

      /**
       * Store a newly created resource in storage.
       *
       * @return Response
       */
      public function store()
      {
            $validator = $this->getValidatorPlan();
            if ($validator->passes())
            {
                  $nombre = Input::get('nombre');
                  $dominio = Input::get('domain');
                  $name_server = Input::get('name_server');
                  $numero_correos = Input::get('numero_correos');
                  $quota_correos = Input::get('quota_correos');
                  $numero_ftps = Input::get('numero_ftps');
                  $quota_ftps = Input::get('quota_ftps');
                  $numero_dbs = Input::get('numero_dbs');
                  $quota_dbs = Input::get('quota_dbs');                  

                  if ($this->Plan->agregarPlan($nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs))
                  {
                        Session::flash('message', 'Plan agregado con exito');
                        return Redirect::to('admin/planes');
                  }
                  else
                  {
                        Session::flash('error', 'Ocurrio un error al agregar el plan');
                  }
            }
            return Redirect::back()->withErrors($validator)->withInput();
      }

      /**
       * Display the specified resource.
       *
       * @param  int  $id
       * @return Response
       */
      public function show($id)
      {
            $plan = $this->Plan->Usuario->obtenerUsuario($id);
            return View::make('admin.planes.show')->with('plan', $plan);
      }

      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return Response
       */
      public function edit($id)
      {
            $plan = $this->Plan->mostrarPlan($id);
            if ($plan->id)
            {
                  return View::make('admin.planes.edit')->with('plan', $plan);
            }
            else
            {
                  Session::flash('error', 'no existe el plan');
                  return Redirect::back();
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
            $plan = $this->Plan->mostrarPlan($id);
            if ($plan->id)
            {
                  $validator = $this->getValidatorPlan();
                  if ($validator->passes())
                  {
                        $nombre = Input::get('nombre');
                        $dominio = Input::get('domain');
                        $name_server = Input::get('name_server');
                        $numero_correos = Input::get('numero_correos');
                        $quota_correos = Input::get('quota_correos');
                        $numero_ftps = Input::get('numero_ftps');
                        $quota_ftps = Input::get('quota_ftps');
                        $numero_dbs = Input::get('numero_dbs');
                        $quota_dbs = Input::get('quota_dbs');                        


                        if ($this->Plan->editarPlan($id, $nombre, $dominio, $name_server, $numero_correos, $quota_correos, $numero_ftps, $quota_ftps, $numero_dbs, $quota_dbs))
                        {
                              Session::flash('message', 'Plan editado con exito');
                              return Redirect::to('admin/planes');
                        }
                        else
                        {
                              Session::flash('error', 'Ocurrio un error al editar el plan');
                        }
                  }
                  return Redirect::back()->withErrors($validator)->withInput();
            }
            else
            {
                  Session::flash('error', 'no existe el plan');
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
            $plan = $this->Plan->mostrarPlan($id);
            if ($plan->id)
            {
                  if ($this->Plan->eliminarPlan($id))
                  {
                        Session::flash('message', 'Plan eliminado con exito');
                        return Redirect::to('admin/planes');
                  }
                  else
                  {
                        Session::flash('error', 'Ocurrio un error al eliminar el plan');
                  }
            }
            else
            {
                  Session::flash('error', 'no existe el plan');
                  return Redirect::back();
            }
      }

      protected function getValidatorPlan()
      {
            return Validator::make(Input::all(), array(
                        'nombre' => 'required',
                        'domain' => 'required',
                        'name_server' => 'required',
                        'numero_correos' => 'required',
                        'quota_correos' => 'required',
                        'numero_ftps' => 'required',
                        'quota_ftps' => 'required',
                        'numero_dbs' => 'required',
                        'quota_dbs' => 'required'                        
            ));
      }

}
