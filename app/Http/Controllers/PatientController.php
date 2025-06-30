<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class PatientController extends Controller
{

   public function storePatients(Request $request): JsonResponse
   {
      $validator = Validator::make($request->all(), [
         'name'    => ['required', 'string', 'max:255'],
         'email'   => ['required', 'email', 'max:255', 'unique:patients,email'],
         'phone'   => ['required', 'string', 'max:20'],
         'address' => ['required', 'string', 'max:255'],
      ]);

      if ($validator->fails()) {
         return response()->json([
            'message' => 'Validation failed',
            'errors'  => $validator->errors(),
         ], 422);
      }

      $patient = Patient::create($validator->validated());

      return response()->json([
         'message' => 'Patient created successfully',
         'patient' => $patient,
      ], 201);
   }


   public function getPatients()
   {
      $patients = Patient::all();
      return response()->json([
         'message' => 'Patients fetched successfully',
         'patients' => $patients
      ], 200);
   }

   public function getPatientById($id)
   {
      $patient = Patient::find($id);
      return response()->json([
         'message' => 'Patient fetched successfully',
         'patient' => $patient
      ]);
   }
   public function updatePatient(Request $request, $id)
   {
      $validator = Validator::make($request->all(), [
         'name'    => 'required',
         'email'   => 'required|email',
         'phone'   => 'required',
         'address' => 'required',
      ]);

      if ($validator->fails()) {
         return response()->json([
            'message' => 'Validation failed',
            'errors'  => $validator->errors(),
         ], 422);
      }

      $patient = Patient::find($id);
      if (!$patient) {
         return response()->json([
            'message' => "Patient not found against this $id",
         ], 404);
      } else {
         $patient->update($validator->validated());
         return response()->json([
            'message' => 'Patient updated successfully',
            'patient' => $patient,
         ]);
      }
   }

   public function deletePatient($id)
   {
      $patient = Patient::find($id);
      $patient->delete();
      return response()->json([
         'message' => 'Patient deleted successfully',
      ], 200);
   }
}
