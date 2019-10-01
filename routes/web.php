<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('email', function(){
    return new App\Mail\DatosOrdenCreada(App\User::first(), '1');
});*/

Route::auth();
Route::get('/', function () {
    return redirect()->route('login');
});
Route::get('registro', function () {
    return view('auth.register');
});

/*Route::post('usaurios.almacenarUsuarioCliente', function(){
    almacenarUsuarioCliente();
});*/

Route::post('admin/RegistrarUsuario/nuevo', 'Admin\UsuariosController@almacenarNuevoUsuarioCliente')->name('usuarios.almacenarNuevoUsuarioCliente');


Route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>'auth'], function(){

    Route::get('/', 'UsuariosController@panel');
    Route::get('usuarios', 'UsuariosController@index')->name('usuarios.index');
    Route::get('usuarios/idexCliente', 'UsuariosController@indexCliente')->name('usuarios.indexCliente');
    Route::get('usuarios/crear','UsuariosController@crear')->name('usuarios.crear');
    Route::get('usuarios/Cliente/crear','UsuariosController@crearCliente')->name('usuarios.crearCliente');

    Route::get('usuarios/activar', 'UsuariosController@activar')->name('usuarios.activar');
    Route::post('usuarios/actuzalizarActivar', 'UsuariosController@actualizarActivar')->name('usuarios.actualizarActivar');
    Route::get('usuarios/desactivar/{usuario_id}', 'UsuariosController@desactivar')->name('usuarios.desactivar');
    Route::post('usuarios/actualizar','UsuariosController@actualizar_usuario')->name('usuarios.actualizar');
    Route::post('RegistrarUsuario', 'UsuariosController@almacenarUsuario')->name('almacenarUsuario');
    Route::post('RegistrarUsuario/Cliente', 'UsuariosController@almacenarUsuarioCliente')->name('usuarios.almacenarUsuarioCliente');
    /*Formulario para cambiar la contraseña*/
    Route::get('usuarios/cambioContraseña', 'UsuariosController@cambioContraseña')->name('usuarios.cambioContraseña');
    /*Envio de datos para actualizar los datos de la contraseña*/
    Route::post('usuario/actualizarContraseña', 'UsuariosController@actualizarContraseña')->name('usuarios.actualizarContraseña');
    
    /**/
});


Route::group(['prefix'=>'trabajos','namespace'=>'Trabajos','middleware'=>'auth'], function(){

    //Rutas de las ordenes
    Route::get('ordenes/editar/{orden_id}','OrdenesController@editar')->name('ordenes.editar');
    Route::get('ordenes/editarDetalle/{orden}','OrdenesController@editarDetalle')->name('ordenes.editarDetalle');
    Route::post('ordenes/Actualizar','OrdenesController@actualizarEdicion')->name('ordenes.actualizarEdicion');
    //Consulta de todas las ordenes
    Route::get('ordenes/index','OrdenesController@index')->name('ordenes.index');
    //Consulta de ordenes creadas por el usuario logueado
    Route::get('ordenes/misOrdenes','OrdenesController@porUsuario')->name('ordenes.misOrdenes');
    //Consulta de las ordenes asignadas al usuario logueado
    Route::get('ordenes/misOrdenesAsignadas','OrdenesController@misAsignadas')->name('ordenes.misAsignadas');
    Route::get('ordenes/negociadas','OrdenesController@negociadas')->name('ordenes.negociadas');
    /*Consulta detalle de la vista por el usuario cliente*/
    Route::get('ordenes/detalleUsuario/{orden_id}','OrdenesController@detalleUsuario')->name('ordenes.detalleUsuario');

    Route::get('ordenes/crear','OrdenesController@crear')->name('ordenes.crear');
    Route::post('ordenes/almacenar','OrdenesController@almacenar')->name('ordenes.almacenar');
    Route::get('ordenes/sinAsignar', 'OrdenesController@sinAsignar')->name('ordenes.sinAsignar');
    Route::get('detalleOrden/{orden_id}','OrdenesController@detalleCotizadas')->name('ordenes.detalle');
    Route::post('asignarUsuarioOrden/', 'OrdenesController@asignarUsuarioOrden')->name('ordenes.asignarUsuarioOrden');
    Route::get('ordenes/asignadas', 'OrdenesController@asignadas')->name('ordenes.asignadas');
    Route::get('detalleAsignada/{orden_id}', 'OrdenesController@detalleAsignada')->name('detalle.asignadas');
    Route::get('detalleAsignada/OrdenAsignada/{orden_id}', 'OrdenesController@detalleAsignadasOrden')->name('detalle.asignadasOrden');

    Route::post('ordenes/actualizar','OrdenesController@update')->name('ordenes.update');

    /*Actualizar las variables para la orden*/
    Route::post('ordenes/VariablesEditablesOrden','OrdenesController@actualizarVariableOrden')->name('ordenes.actualizarVariableOrden');
    
    /*Eliminar los item de la orden*/
    Route::get('detalleAsignada/eliminarItem/{detalle_id}', 'OrdenesController@eliminarItem')->name('ordenes.eliminarItem');
    //Se actualizan los datos de la Orden como fecha entrega y feche recepcion.
    Route::post('ordenes/ordenes.actualizarOrden','OrdenesController@actualizarOrden')->name('ordenes.actualizarOrden');
    //Cerrar la Orden para poder Facturar
     Route::post('ordenes/ordenes.actualizarParaFacturar','OrdenesController@actualizarParaFacturar')->name('ordenes.actualizarParaFacturar');


    Route::post('ordenes/cotizarOrden/{orden_id}','OrdenesController@cotizarOrden')->name('ordenes.cotizarOrden');

    //Rutas de las sedes
    Route::get('sedes','SedesController@index')->name('sedes.index');
    Route::get('sedes/crear','SedesController@crear')->name('sedes.crear');
    Route::post('sedes/almacenar','SedesController@almacenar')->name('trabajos.sedes.almacenar');
    Route::post('sedes/{sede_id}','SedesController@actualizar')->name('trabajos.sedes.update');

    //Variables Editables -----------------------------------------------------------
    Route::get('variables', 'VariableController@index')->name('variables.index');
    Route::post('variables/{variable_id}', 'VariableController@update')->name('trabajos.variables.update');

    //Vista Facturación
    Route::get('facturas', 'FacturasController@index')->name('facturas.index');
    Route::get('facturas/orden', 'FacturasController@facturasOrden')->name('facturas.orden');
    Route::get('facturas/crearPorOrden/{orden_id}', 'FacturasController@crearFacturaOrden')->name('facturas.crearFacturaOrden');
    Route::post('facturas/guardarDatosFacturaPorOrden', 'FacturasController@almacenarFacturaOrden')->name('facturas.almacenar.facturaOrden');
    Route::post('facturas/genararFactura', 'FacturasController@generarFacturaOrden')->name('facturas.generar.facturaOrden');

    Route::get('facturas/detalleFactura/{orden_id}','FacturasController@detalleFactura')->name('facturas.detalleFactura');
    Route::get('facturas/misFacturas','FacturasController@misFacturas')->name('facturas.misFacturas');

});

Route::get('/clearcache', function(){
      Artisan::call('cache:clear');
      Artisan::call('config:clear');
      Artisan::call('route:clear');
      Artisan::call('view:clear');
      // Artisan::call('event:generate ');
      // Artisan::call('key:generate');
      return '<h1>se ha borrado el cache</h1>';
});
