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
      if(!$patient){
         return response()->json([
            'message' => "Patient not found against this $id",
         ], 404);
      }
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
         ], 200);
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
   public function searchPatientByName($name)
   {
      $patient = Patient::where('name', 'like', '%' . $name . '%')->get();
      return response()->json([
         'message' => 'Patient searched successfully',
         'patient' => $patient
      ], 200);
   }
   public function searchPatientByEmail($email)
   {
      $patient = Patient::where('email', 'like', '%' . $email . '%')->get();
      return response()->json([
         'message' => 'Patient searched successfully',
         'patient' => $patient
      ], 200);
   }
   public function searchPatientByPhone($phone)
   {
      $patient = Patient::where('phone', 'like', '%' . $phone . '%')->get();
      return response()->json([
         'message' => 'Patient searched successfully',
         'patient' => $patient
      ], 200);
   }
   public function searchPatientByAddress($address)
   {
      $patient = Patient::where('address', 'like', '%' . $address . '%')->get();
      return response()->json([
         'message' => 'Patient searched successfully',
         'patient' => $patient
      ], 200);
   }
   public function searchPatientByPhoneAndAddress($phone, $address)
   {
      $patient = Patient::where('phone', 'like', '%' . $phone . '%')->where('address', 'like', '%' . $address . '%')->get();
      if ($patient->isEmpty()) {
         return response()->json([
            'message' => 'No patient found with this phone and address',
         ], 404);
      }
      return response()->json([
         'message' => 'Patient searched successfully',
         'patient' => $patient
      ], 200);
   }
   public function searchPatientByPhoneAndName($phone, $name)
   {
      $patient = Patient::where('phone', 'like', '%' . $phone . '%')->where('name', 'like', '%' . $name . '%')->get();
      if($patient->isEmpty()){
         return response()->json([
            'message' => 'No patient found with this phone and name',
         ], 404);
      }
      return response()->json([
         'message' => 'Patient searched successfully',
         'patient' => $patient
      ], 200);
   }
}
