<?php

use FtpsRepository as Ftp;

class FtpsController extends \BaseController {

      protected $Ftp;
      
      public function __construct(Ftp $ftp)
      {
            $this->Ftp = $ftp;
            $this->Ftp->set_attributes(Session::get('dominio'));
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
            return View::make('ftps.index')->with(array('ftps' => $ftps, 'total' => $total));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		return View::make('ftps.create');
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
            if ($total > Session::get('dominio')->plan->numero_ftps)
            {
                  Session::flash('error', 'Se alcanzó el número máximo de correos para el plan');
                  return Redirect::to('ftps');
            }
            $validator = $this->getFtpsValidator();
            if ($validator->passes())
            {
                  $username = Input::get('nombre_usuario');
                  $hostname='primerserver.com';
                  $home_dir=Input::get('homedir');
                  $password=Input::get('password');                  
                  if ($this->Ftp->agregarFtp($username, $hostname, $home_dir, $password))
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
		$ftp = $this->Ftp->obtenerFtp($id);
            if ($this->isIdDomain($ftp))
            {
                  return View::make('ftps.show')->with('ftp', $ftp);
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
		$ftp = $this->Ftp->obtenerFtp($id);
            if ($this->isIdDomain($ftp))
            {
                  return View::make('ftps.edit')->with('ftp', $ftp);
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
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

      
      protected function getFtpsValidator(){
            
      }
      
      protected function getEditarFtpValidator(){
            
      }
      
      protected function isIdDomain($id_ftp){
            
      }

}
