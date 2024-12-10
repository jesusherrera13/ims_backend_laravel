<?php

use App\Http\Controllers\EspecialidadMedicaController;
use App\Http\Controllers\MedicoController;
use App\Http\Controllers\PacienteController;
use App\Http\Controllers\RecetaController;
use App\Http\Controllers\ReligionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRolController;
use App\Http\Controllers\PlazaController;
use App\Http\Controllers\RegimenController;
use App\Http\Controllers\PaisController;
use App\Http\Controllers\EstadoController;
use App\Http\Controllers\MunicipioController;
use App\Http\Controllers\CiudadController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\DepartamentoController;
use App\Http\Controllers\IndicadorController;
use App\Http\Controllers\EquipoController;
use App\Http\Controllers\CompaniaController;
use App\Http\Controllers\PeriodoController;
use App\Http\Controllers\ModuloController;
use App\Http\Controllers\UserModuleController;
use App\Http\Controllers\UserPlazaController;
use App\Http\Controllers\ServiceProviderController;
use App\Http\Controllers\FortiaController;
use App\Http\Controllers\SyncLogController;
use App\Http\Controllers\PerfilesController;
use App\Models\Perfiles;
use App\Http\Controllers\MedicamentosController;
use App\Http\Controllers\MetodosPagoController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\CitasEfectivasController;
use App\Models\MetodosPago;

Route::get('/compania/{empresas}', [CompaniaController::class, 'getEmpresas']);
Route::get('/empresas', [EmpresaController::class, 'index']);
Route::get('/empresas/{empresa}', [EmpresaController::class, 'show']);
Route::post('/empresas', [EmpresaController::class, 'create']);
Route::put('/empresas/{empresa}', [EmpresaController::class, 'update']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/sync-plaza-reproceso', [PlazaController::class, 'syncReproceso']);
Route::get('/acceso-plazas', [PlazaController::class, 'accesoPlazas']);

Route::get('/cloud-provider', [ServiceProviderController::class, 'index']);

Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::get('/user', [UserController::class, 'index']);
    Route::post('/user', [UserController::class, 'create']);
    Route::get('/user/{id}', [UserController::class, 'show']);
    Route::post('/user/{user}', [UserController::class, 'update']);
    Route::put('/user/{user}', [UserController::class, 'update']);
    // Route::patch('/user/{user}', [UserController::class, 'update']);
    // Route::delete('/user/{user}', [UserController::class, 'destroy']);

    Route::delete('/user/{user}', [UserController::class, 'destroy']);

    Route::get('/rol', [UserRolController::class, 'index']);
    Route::get('/rol/{rol}', [UserRolController::class, 'show']);
    Route::post('/rol', [UserRolController::class, 'create']);
    Route::put('/rol/{rol}', [UserRolController::class, 'update']);
    Route::delete('/rol/{rol}', [UserRolController::class, 'destroy']);

    Route::get('/regimen', [RegimenController::class, 'index']);
    Route::get('/regimen/{regimen}', [RegimenController::class, 'show']);
    Route::post('/regimen', [RegimenController::class, 'create']);
    Route::put('/regimen/{regimen}', [RegimenController::class, 'update']);
    Route::delete('/regimen/{regimen}', [RegimenController::class, 'destroy' ]);


    Route::get('/empresas', [EmpresaController::class, 'index']);
    Route::get('/empresas/{empresa}', [EmpresaController::class, 'show']);
    Route::post('/empresas', [EmpresaController::class, 'create']);
    Route::put('/empresas/{empresa}', [EmpresaController::class, 'update']);
    // Route::delete('/empresas/{empresa}', [EmpresaController::class,'destroy']);

    Route::get('/plazas', [PlazaController::class, 'index']);
    Route::get('/plazas/{plaza}', [PlazaController::class, 'show']);
    Route::post('/plazas', [PlazaController::class, 'create']);
    Route::put('/plazas/{plaza}', [PlazaController::class, 'update']);
    // Route::delete('/plazas/{plaza}', [PlazaController::class,'destroy']);

    Route::get('/pais', [PaisController::class, 'index']);
    Route::get('/pais/{pais}', [PaisController::class, 'show']);
    Route::post('/pais', [PaisController::class, 'create']);
    Route::post('/pais/{pais}', [PaisController::class, 'update']);
    Route::put('/pais/{pais}', [PaisController::class, 'update']);
    Route::delete('/pais/{pais}', [PaisController::class, 'destroy']);

    Route::get('/estado', [EstadoController::class, 'index']);
    Route::get('/estado/{estado}', [EstadoController::class, 'show']);
    Route::post('/estado', [EstadoController::class, 'create']);
    Route::post('/estado/{estado}', [EstadoController::class, 'update']);
    Route::put('/estado/{estado}', [EstadoController::class, 'update']);
    Route::delete('/estado/{estado}', [EstadoController::class, 'destroy']);

    Route::get('/municipio', [MunicipioController::class, 'index']);
    Route::get('/municipio/{municipio}', [MunicipioController::class, 'show']);
    Route::post('/municipio', [MunicipioController::class, 'create']);
    Route::post('/municipio/{municipio}', [MunicipioController::class, 'update']);
    Route::put('/municipio/{municipio}', [MunicipioController::class, 'update']);
    Route::delete('/municipio/{municipio}', [MunicipioController::class, 'destroy']);

    Route::get('/ciudad', [CiudadController::class, 'index']);
    Route::get('/ciudad/{ciudad}', [CiudadController::class, 'show']);
    Route::post('/ciudad', [CiudadController::class, 'create']);
    Route::post('/ciudad/{ciudad}', [CiudadController::class, 'update']);
    Route::put('/ciudad/{ciudad}', [CiudadController::class, 'update']);
    Route::delete('/ciudad/{ciudad}', [CiudadController::class, 'destroy']);

    Route::get('/religion', [ReligionController::class, 'index']);
    Route::get('/religion/{religion}', [ReligionController::class, 'show']);
    Route::post('/religion', [ReligionController::class, 'create']);
    Route::put('/religion/{religion}', [ReligionController::class, 'update']);
    Route::post('/religion/{religion}', [ReligionController::class, 'update']);
    Route::delete('/religion/{religion}', [ReligionController::class,'destroy']);

    Route::get('/paciente', [PacienteController::class, 'index']);
    Route::get('/paciente/{paciente}', [PacienteController::class, 'show']);
    Route::post('/paciente', [PacienteController::class, 'create']);
    Route::put('/paciente/{paciente}', [PacienteController::class, 'update']);
    Route::post('/paciente/{paciente}', [PacienteController::class, 'update']);
    Route::delete('/paciente/{paciente}', [PacienteController::class,'destroy']);

    Route::get('/especialidad', [EspecialidadMedicaController::class, 'index']);
    Route::get('/especialidad/{especialidad}', [EspecialidadMedicaController::class, 'show']);
    Route::post('/especialidad', [EspecialidadMedicaController::class, 'create']);
    Route::put('/especialidad/{especialidad}', [EspecialidadMedicaController::class, 'update']);
    Route::post('/especialidad/{especialidad}', [EspecialidadMedicaController::class, 'update']);
    Route::delete('/especialidad/{especialidad}', [EspecialidadMedicaController::class,'destroy']);

    Route::get('/medico', [MedicoController::class, 'index']);
    Route::get('/medico/{medico}', [MedicoController::class, 'show']);
    Route::post('/medico', [MedicoController::class, 'create']);
    Route::put('/medico/{medico}', [MedicoController::class, 'update']);
    //Route::post('/medico/{medico}', [MedicoController::class, 'update']);
    Route::delete('/medico/{medico}', [MedicoController::class,'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::resource('empleado', EmployeeController::class);
    Route::resource('empresa', EmpresaController::class);
    // Route::resource('departamento', DepartamentoController::class);
    Route::resource('indicador', IndicadorController::class);
    Route::resource('equipo', EquipoController::class);
    Route::resource('compania', CompaniaController::class);
    Route::resource('periodo', PeriodoController::class);

    Route::post('/auth-user-modules', [AuthController::class, 'userModules']);
    Route::post('/auth-sidebar', [AuthController::class, 'authSideBar']);
    Route::post('/user-modules', [UserModuleController::class, 'storeUserModules']);
    Route::post('/access-user-modules', [UserModuleController::class, 'accessUserModules']);

    Route::resource('modulo', ModuloController::class);

    Route::get('/acceso-modulos', [ModuloController::class, 'accesoModulos']);
    // Route::get('/acceso-plazas', [ModuloController::class, 'accesoModulos']);
    Route::post('/access-plazas-store', [UserPlazaController::class, 'storeUserPlazas']);

    Route::post('/cloud-provider', [ServiceProviderController::class, 'index']);
    Route::post('/cloud-provider-sync', [ServiceProviderController::class, 'sync']);
    Route::post('/sync-log-unlock', [SyncLogController::class, 'syncLogUnlock']);

    Route::get('/perfiles', [PerfilesController::class, 'index']);
    Route::get('/perfiles/{perfiles}', [PerfilesController::class, 'show']);
    Route::post('/perfiles', [PerfilesController::class, 'create']);
    Route::put('/perfiles/{perfiles}', [PerfilesController::class, 'update']);
    Route::delete('/perfiles/{perfiles}', [PerfilesController::class, 'destroy' ]);

    Route::get('/medicamentos', [MedicamentosController::class, 'index']);
    Route::get('/medicamentos/{medicamentos}', [MedicamentosController::class, 'show']);
    Route::post('/medicamentos', [MedicamentosController::class, 'create']);
    Route::put('/medicamentos/{medicamentos}', [MedicamentosController::class, 'update']);
    Route::delete('/medicamentos/{medicamentos}', [MedicamentosController::class, 'destroy' ]);

    Route::get('/metodoPagos', [MetodosPagoController::class, 'index']);
    Route::get('/metodoPagos/{metodoPagos}', [MetodosPagoController::class, 'show']);
    Route::post('/metodoPagos', [MetodosPagoController::class, 'create']);
    Route::put('/metodoPagos/{metodoPagos}', [MetodosPagoController::class, 'update']);
    Route::delete('/metodoPagos/{metodoPagos}', [MetodosPagoController::class, 'destroy' ]);

    //ruta para obtener la empresa de una compañia 

    //ruta para obtener las citas
    Route::get('/cita', [AppointmentController::class, 'index']);
    //ruta para crear una cita
    Route::post('/cita', [AppointmentController::class, 'create']);
    //ruta para actualizar una cita
    Route::put('/cita/{appointment}', [AppointmentController::class, 'update']);
    //ruta para obtener destruir una cita
    Route::delete('/cita/{appointment}', [AppointmentController::class, 'destroy']);
//ruta para obtener las especialidades de los medicos
    Route::get('/cita/{medico}/especialidades', [AppointmentController::class, 'getEspecialidadesMedicas']);

Route::get('/cita/{medico.id}/especialidades/{especialidad.id}/horarios', [AppointmentController::class, 'getHorarios']); // ruta para obtener los horarios de un medico con una especialidad

Route::get('/cita/{medico.id}/especialidades/{especialidad.id}/horarios/{horario.id}/citas', [AppointmentController::class, 'getCitas']); // ruta para obtener las citas de un medico con una especialidad y un horario 

Route::get('/cita/{medico.id}/especialidades.id/{especialidad.id}/horarios/{horario.id}/citas/{cita.id}', [AppointmentController::class, 'getCita']); // ruta para obtener una cita de un medico con una especialidad y un horario

Route::post('/cita/{medico.id}/especialidades/{especialidad.id}/horarios/{horario.id}/citas', [AppointmentController::class, 'createCita']); // ruta para crear una cita de un medico con una especialidad y un horario

Route::put('/cita/{medico.id}/especialidades/{especialidad.id}/horarios/{horario.id}/citas/{cita.id}', [AppointmentController::class, 'updateCita']); // ruta para actualizar una cita de un medico con una especialidad y un horario

Route::delete('/cita/{medico.id}/especialidades/{especialidad.id}/horarios/{horario.id}/citas/{cita.id}', [AppointmentController::class, 'destroyCita']); // ruta para eliminar una cita de un medico con una especialidad y un horario


// rutas para crear y eliminar horarios de un medico con una especialidad  
//esto sirve para traerlos en el front end con vuejs 
Route::get('/horario/{medico.id}/especialidades/{especialidad.id}/horarios', [HorarioController::class, 'getHorarios']); // ruta para obtener los horarios de un medico con una especialidad 
Route::post('/horario/{medico.id}/especialidades/{especialidad.id}/horarios', [HorarioController::class, 'createHorario']); // ruta para crear un horario de un medico con una especialidad
Route::put('/horario/{medico.id}/especialidades/{especialidad.id}/horarios/{horario.id}', [HorarioController::class, 'updateHorario']); // ruta para actualizar un horario de un medico con una especialidad
Route::delete('/horario/{medico.id}/especialidades/{especialidad.id}/horarios/{horario.id}', [HorarioController::class, 'destroyHorario']); // ruta para eliminar un horario de un medico con una especialidad

    // Rutas para horarios
    Route::get('/horario', [HorarioController::class, 'index']);
    Route::post('/horario', [HorarioController::class, 'create']);
    Route::put('/horario/{horario}', [HorarioController::class, 'update']);
    Route::delete('/horario/{horario}', [HorarioController::class, 'destroy']);

Route::get('/receta', [RecetaController::class, 'index']);
Route::get('/receta/{id}', [RecetaController::class, 'show']);
Route::post('/receta', [RecetaController::class, 'create']);
Route::post('/receta/{receta}', [RecetaController::class, 'update']);
Route::put('/receta/{receta}', [RecetaController::class, 'update']);
Route::delete('/receta/{receta}', [RecetaController::class, 'destroy']);
Route::get('/receta/{id}/pdf', [RecetaController::class, 'generatePDF']);
//Route::get('/receta/{id}/pdf/{size?}', [RecetaController::class, 'generatePDF']);
// routes/api.php

Route::post('/receta/preview', [RecetaController::class, 'previewPDF']);

Route::post('/receta/test', function() {
    return response()->json(['message' => 'Ruta de prueba funcionando'], 200);
});

Route::get('/citasE', [CitasEfectivasController::class, 'index']);
Route::get('/citasE/{citasE}', [CitasEfectivasController::class, 'show']);
Route::post('/citasE', [CitasEfectivasController::class, 'create']);
Route::put('/citasE/{citasE}', [CitasEfectivasController::class, 'update']);
Route::delete('/citasE/{citasE}', [CitasEfectivasController::class, 'destroy' ]);

});
