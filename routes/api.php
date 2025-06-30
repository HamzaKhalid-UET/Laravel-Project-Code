<?php
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Anything defined here is automatically prefixed with /api
| and placed in the `api` middleware group (stateless, no CSRF).
|
*/
Route::post('/patients', [PatientController::class, 'storePatients']);
Route::get('/patients',  [PatientController::class, 'getPatients']);
Route::get('/getPatientById/{id}',  [PatientController::class, 'getPatientById']);
Route::put('/updatePatient/{id}',  [PatientController::class, 'updatePatient']);
Route::delete('/deletePatient/{id}',  [PatientController::class, 'deletePatient']);
Route::get('/searchPatientByName/{name}',  [PatientController::class, 'searchPatientByName']);
Route::get('/searchPatientByEmail/{email}',  [PatientController::class, 'searchPatientByEmail']);
Route::get('/searchPatientByPhone/{phone}',  [PatientController::class, 'searchPatientByPhone']);
Route::get('/searchPatientByAddress/{address}',  [PatientController::class, 'searchPatientByAddress']);
Route::get('/searchPatientByPhoneAndAddress/{phone}/{address}',  [PatientController::class, 'searchPatientByPhoneAndAddress']);
