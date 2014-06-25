<?php

/*
  |--------------------------------------------------------------------------
  | Application & Route Filters
  |--------------------------------------------------------------------------
  |
  | Below you will find the "before" and "after" events for the application
  | which may be used to do any work before or after a request into your
  | application. Here you may also register your custom route filters.
  |
 */

App::before(function($request) {
      //
});


App::after(function($request, $response) {
      //
});

/*
  |--------------------------------------------------------------------------
  | Authentication Filters
  |--------------------------------------------------------------------------
  |
  | The following filters are used to verify that the user of the current
  | session is logged into this application. The "basic" filter easily
  | integrates HTTP Basic authentication for quick, simple checking.
  |
 */

Route::filter('auth', function() {
      if (Auth::guest())
            return Redirect::route('usuario/login');
});


Route::filter('auth.basic', function() {
      return Auth::basic();
});

/*
  |--------------------------------------------------------------------------
  | Guest Filter
  |--------------------------------------------------------------------------
  |
  | The "guest" filter is the counterpart of the authentication filters as
  | it simply checks that the current user is not logged in. A redirect
  | response will be issued if they are, which you may freely change.
  |
 */

Route::filter('guest', function() {
      if (Auth::check())
            return Redirect::to('/');
});

/*
  |--------------------------------------------------------------------------
  | CSRF Protection Filter
  |--------------------------------------------------------------------------
  |
  | The CSRF filter is responsible for protecting your application against
  | cross-site request forgery attacks. If this special token in a user
  | session does not match the one given in this request, we'll bail.
  |
 */

Route::filter('csrf', function() {
      if (Session::token() != Input::get('_token'))
      {
            throw new Illuminate\Session\TokenMismatchException;
      }
});

/*
  |--------------------------------------------------------------------------
  | Is Admin
  |--------------------------------------------------------------------------
  |
  | Determina si el usuario es administrdor, si no lo es, lo redirige a la pagina principal
  |
 */

Route::filter('is_admin', function() {
      if (!Auth::user()->is_admin)
      {
            return Redirect::to('inicio');
      }
});


/*
  |-------------------------------------------
  | is_activo
  |-------------------------------------------
 */
Route::filter('is_activo', function() {
      if (Session::get('dominio')->dominio->activo)
      {
            return Redirect::to('pagos/faltantes');
      }
});

/*
 *
 * Comprobar usuario
 *  
 */
ROute::filter('comprobar_usuario', function() {
      $usuario = Auth::user();
      if (!$usuario->is_activo)
      {
            if ($usuario->is_deudor)
            {
                  Session::flash('error', 'Revisa por favor los pagos pendientes, antes de poder continuar');
                  return Redirect::to('pagos/faltantes');
            }
            
            if (!$usuario->is_activo){
                  Session::flash('messages', 'El usuario tiene problemas, ponte en contacto con nosotros');
                  return Redirect::to('dominios/seleccionar_nuevo');
            }            
      }
});
