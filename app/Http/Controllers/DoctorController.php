<?php

namespace App\Http\Controllers;




use App\Models\Doctor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{



//////////////////////////////////////////////////////////////////
////////////////////////////////////////////
//////////////////// create doctor.
public function create(Request $request): JsonResponse
{
    $validated = $request->validate([
        'name' => ['required', 'string', 'max:255'],
        'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        'email' => ['required', 'email', 'unique:doctors,email'],
        'password' => ['required', 'min:6'],
        'experience' => ['required', 'string'],
        'fees' => ['required'],
        'about' => ['required', 'string'],
    ]);

    $imagePath = null;

    if ($request->hasFile('image')) {
        $imagePath = $request->file('image')->store('doctors', 'public');
    }

    $doctor = Doctor::create([
        'name' => $validated['name'],
        'image' => $imagePath,
        'email' => $validated['email'],
        'password' => Hash::make($validated['password']),
        'experience' => $validated['experience'],
        'fees' => $validated['fees'],
        'about' => $validated['about'],
    ]);

    return response()->json([
        'success' => true,
        'message' => 'Doctor created successfully',
        'data' => $doctor,
    ], 201);
}


//////////////////////////////////////////////////////////////////
////////////////////////////////////////////
//////////////////// get doctors.
public function doctors(): JsonResponse
{
    $doctors = Doctor::latest()->get();

    return response()->json([
        'success' => true,
        'count' => $doctors->count(),
        'data' => $doctors,
    ]);
}


//////////////////////////////////////////////////////////////////
////////////////////////////////////////////
//////////////////// delete doctor.
public function destroy($id)
{
    $doctor = Doctor::find($id);

    if (!$doctor) {
        return response()->json([
            'message' => 'Doctor not found'
        ], 404);
    }

    // delete image if exists
    if ($doctor->image) {
        $path = public_path("storage/doctors/" . $doctor->image);

        if (file_exists($path)) {
            unlink($path);
        }
    }

    $doctor->delete();

    return response()->json([
        'message' => 'Doctor deleted successfully'
    ]);
}


//////////////////////////////////////////////////////////////////
////////////////////////////////////////////
//////////////////// update doctor.
public function update(Request $request, $id)
{
    $doctor = Doctor::find($id);

    if (!$doctor) {
        return response()->json([
            'message' => 'Doctor not found'
        ], 404);
    }

    $validated = $request->validate([
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|unique:doctors,email,' . $id,
        'password' => 'nullable|min:6',
        'image' => 'nullable|image',
        'experience' => 'nullable|string',
        'fees' => 'nullable',
        'about' => 'nullable|string',
    ]);

    // update fields
    if (isset($validated['name'])) {
        $doctor->name = $validated['name'];
    }

    if (isset($validated['email'])) {
        $doctor->email = $validated['email'];
    }

    if (!empty($validated['password'])) {
        $doctor->password = bcrypt($validated['password']);
    }

    if (isset($validated['experience'])) {
        $doctor->experience = $validated['experience'];
    }

    if (isset($validated['fees'])) {
        $doctor->fees = $validated['fees'];
    }

    if (isset($validated['about'])) {
        $doctor->about = $validated['about'];
    }

    // IMAGE UPDATE (important part)
    if ($request->hasFile('image')) {

        // delete old image
        if ($doctor->image) {
            $oldPath = public_path("storage/doctors/" . $doctor->image);

            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // upload new image
        $file = $request->file('image');
        $fileName = time() . '_' . $file->getClientOriginalName();

        $file->move(public_path('storage/doctors'), $fileName);

        $doctor->image = $fileName;
    }

    $doctor->save();

    return response()->json([
        'message' => 'Doctor updated successfully',
        'data' => $doctor
    ]);
}

}
