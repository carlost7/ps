<?php

/*
  |--------------------------------------------------------------------------
  | Application Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register all of the routes for an application.
  | It's a breeze. Simply tell Laravel the URIs it should respond to
  | and give it the Closure to execute when that URI is requested.
  |
 */

Route::get('/', array('as' => 'inicio', 'uses' => 'HomeController@showWelcome'));
Route::get('nosotros', array('as' => 'nosotros', 'uses' => 'HomeController@showAbout'));
Route::any('contacto', array('as' => 'contacto', 'uses' => 'HomeController@showContacto'));
Route::get('costos', array('as' => 'costos', 'uses' => 'HomeController@showCostos'));
Route::get('terminos', array('as' => 'terminos', 'uses' => 'HomeController@showTerminos'));
Route::post('obtener_password', array('as' => 'obtener_password', 'uses' => 'HomeController@obtenerPass'));

/*
  |---------------------------------
  | Cuentas de usuario
  |---------------------------------
 */
Route::any('usuario/login', array('as' => 'usuario/login', 'uses' => 'UsuariosController@login'));
Route::any('usuario/recuperar', array('as' => 'usuario/recuperar', 'uses' => 'UsuariosController@recuperarPassword'));
Route::any('usuario/reset/{token}', array('as' => 'usuario/reset', 'uses' => 'UsuariosController@regenerarPassword'));


/*
  |--------------------------------
  | Rutas para agregar dominios
  |--------------------------------
 */
Route::any('dominio', array('as' => 'dominio/inicio', 'uses' => 'DominiosController@iniciarDominios'));
Route::any('dominio/nuevo', array('as' => 'dominio/nuevo', 'uses' => 'DominiosController@dominioNuevo'));
Route::any('dominio/existente', array('as' => 'dominio/existente', 'uses' => 'DominiosController@dominioExistente'));
Route::any('dominio/comprobar', array('as' => 'dominio/comprobar', 'uses' => 'DominiosController@comprobarDominio'));
Route::any('dominio/confirmar', array('as' => 'dominio/confirmar', 'uses' => 'DominiosController@confirmarDominio'));

Route::any('pagos/confirmar_registro', array('as' => 'pagos/confirmar_registro', 'uses' => 'PagosController@confirmarRegistro'));
Route::any('pagos/descripcion', array('as' => 'pagos/descripcion', 'uses' => 'PagosController@getCostoTotal'));
Route::any('pagos/pago_cancelado', array('as' => 'pagos/pago_cancelado', 'uses' => 'PagosController@cancelarPago'));
Route::any('pagos/pago_aceptado', array('as' => 'pagos/pago_aceptado', 'uses' => 'PagosController@aceptarPago'));
Route::any('pagos/pago_pendiente', array('as' => 'pagos/pago_pendiente', 'uses' => 'PagosController@pagoPendiente'));
Route::any('pagos/notificacion_mercadopago',array('as' => 'pagos/notificacion_mercadopago', 'uses'=>'PagosController@obtenerIPNMercadoPago'));

/*
  |--------------------------------------------------------
  | Todas las funciones que el usuario podra realizar
  |-------------------------------------------------------
 */
Route::group(array('before' => 'auth'), function() {

      /*
        |------------------------------------------------------------------------------- -
        | Terminar sesion de usuario, requiere estar loggeado para terminar la sesion
        ----------------------------------------------------------------------------------
       */
      Route::get('usuario/logout', array('as' => 'usuario/logout', 'uses' => 'UsuariosController@logout'));
      Route::any('usuario/cambiar_password', array('as' => 'usuario/cambiar_password', 'uses' => 'UsuariosController@cambiarPasswordUsuario'));
      Route::any('usuario/cambiar_correo', array('as' => 'usuario/cambiar_correo', 'uses' => 'UsuariosController@cambiarCorreoUsuario'));
      Route::any('usuario/problemas', array('as' => 'usuario/problemas', 'uses' => 'UsuariosController@mostrarProblemas'));

      /*
       * Seccion de pagos
       */
      Route::get('pagos/inicio', array('as' => 'pagos/inicio', 'uses' => 'PagosController@mostrarPagos'));
      Route::get('pagos/faltantes', array('as' => 'pagos/faltantes', 'uses' => 'PagosController@mostrarPagos'));

      Route::group(array('before' => 'comprobar_usuario'), function() {

            /*
              |-------------------------------------------
              | Acciondes del usuario
              |-------------------------------------------
             */
            Route::get('usuario/inicio', array('as' => 'usuario/inicio', 'uses' => 'UsuariosController@iniciar'));


            Route::resource('correos', 'CorreosController');

            Route::resource('ftps', 'FtpsController');

            Route::resource('dbs', 'DbsController');
      });

      /*
        |------------------------------------------
        |    Seccion del administrador
        |------------------------------------------
       */

      Route::group(array('before' => 'is_admin', 'prefix' => 'admin'), function() {

            /*
             * Admin / t7marketing
             */
            Route::resource('usuarios', 'AdminUsersController');

            Route::resource('correos', 'AdminCorreosController');

            Route::resource('ftps', 'AdminFtpsController');

            Route::resource('dbs', 'AdminDbsController');

            Route::resource('planes', 'AdminPlanesController');
      });
});


