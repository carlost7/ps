<?php

use PlanRepository as Plan;

class AdminCostoPlanController extends \BaseController {

      protected $Plan;

      public function __construct(Plan $planes)
      {
            parent::__construct();

            $this->Plan = $planes;
      }

      /**
       * Show the form for creating a new resource.
       *
       * @return Response
       */
      public function create($id)
      {
            $plan = $this->Plan->mostrarPlan($id);
            return View::make('admin.costo_planes.create')->with(array('plan' => $plan));
      }

      /**
       * Store a newly created resource in storage.
       *
       * @return Response
       */
      public function store()
      {
            $validator = $this->getValidatorCreateCostoPlan();
            if ($validator->passes())
            {

                  $plan_id = Input::get('plan_id');
                  $costo_mensual = Input::get('costo_mensual');
                  $costo_anual = Input::get('costo_anual');
                  $moneda = Input::get('moneda');

                  if ($this->Plan->agregarCostoPlan($plan_id, $costo_mensual, $costo_anual, $moneda))
                  {
                        return Redirect::route('admin.planes');
                  }
                  Session::flash('error', 'Error al agregar el costo del plan');
            }

            return Redirect::back()->withErrors($validator->messages);
      }

      /**
       * Display the specified resource.
       *
       * @param  int  $id
       * @return Response
       */
      public function show($id)
      {
            $costoplan = $this->Plan->mostrarPlan($id);
            return View::make('admin.costo_planes.edit')->with(array('costo_plan' => $costoplan));
      }

      /**
       * Show the form for editing the specified resource.
       *
       * @param  int  $id
       * @return Response
       */
      public function edit($id)
      {
            $costoplan = $this->Plan->mostrarPlan($id);
            return View::make('admin.costo_planes.edit')->with(array('costo_plan' => $costoplan));
      }

      /**
       * Update the specified resource in storage.
       *
       * @param  int  $id
       * @return Response
       */
      public function update($id)
      {
            $validator = $this->getValidatorEditCostoPlan();
            if ($validator->passes())
            {
                  $costo_mensual = Input::get('costo_mensual');
                  $costo_anual = Input::get('costo_anual');
                  $moneda = Input::get('moneda');


                  if ($this->Plan->editarCostoPlan($id, $costo_mensual, $costo_anual, $moneda))
                  {
                        return Redirect::route('admin.planes');
                  }
                  Session::flash('error', 'Error al editar el costo del plan');
            }

            return Redirect::back()->withErrors($validator->messages);
      }

      /**
       * Remove the specified resource from storage.
       *
       * @param  int  $id
       * @return Response
       */
      public function destroy($id)
      {

            if ($this->Plan->eliminarCostoPlan($id))
            {
                  Session::flash('message','Eliminado el costo del plan');                  
            }else{
                  Session::flash('error','Error al eliminar el costo del plan');                  
            }
            
            return Redirect::back();
      }

      protected function getValidatorCreateCostoPlan()
      {
            return Validator::make(Input::all(), array(
                        'plan_id' => 'required',
                        'costo_mensual' => 'required',
                        'costo_anual' => 'required',
                        'moneda' => 'required|min:3',
            ));
      }

      protected function getValidatorEditCostoPlan()
      {
            return Validator::make(Input::all(), array(
                        'costo_mensual' => 'required',
                        'costo_anual' => 'required',
                        'moneda' => 'required|min:3',
            ));
      }

}
