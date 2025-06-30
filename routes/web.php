<?php

use Illuminate\Support\Facades\Route;
use App\Models\Patients;

Route::get('/', function () {
    return view('welcome');
});

ROUTE::get('/abc', function () {
    echo "Hello World";
});

// ROUTE::get('/patients', [patientController::class, 'storePatients']);

