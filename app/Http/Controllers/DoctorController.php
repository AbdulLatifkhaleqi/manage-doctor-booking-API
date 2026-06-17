<?php

namespace App\Http\Controllers;




use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    //


    public function create(Request $request):JsonResponse{

    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        "image" => ['nullable' ,'image'],
        'email' => ['required', 'email', 'unique:users,email'],
        'password' => ['required', 'min:8', 'confirmed'],
        "experience" => ['required', 'string'],
        'fees' => ['required'],
        'about' => ['required','string'],
    ]);

        $doctor = Doctor::created([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'experience' => $validated['experience'],
            'fees' => $validated['fees'],
            'about' => $validated['about'],
        ]);

        if($request->hash_file("image")){
            $path = $request->file('image')->store('doctors' , 'public');
        }

    
     

          return response()->json([
            'success' => true,
            'message' => 'Doctor created successfully',
            'data' => $doctor,
        ], 201);
    }


}
