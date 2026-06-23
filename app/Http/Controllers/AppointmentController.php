<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\User;


class AppointmentController extends Controller
{



    public function appointmentBook(Request $request)
    {
    try {
       $userId = $request->user()->id;

        $request->validate([
            'docId' => 'required',
            'date' => 'required',
            'time' => 'required',
        ]);

        $docId = $request->docId;
        $date = $request->date;
        $time = $request->time;

        $doctorData = Doctor::find($docId);

        if (!$doctorData) {
            return response()->json([
                'success' => false,
                'msg' => 'Doctor not found'
            ], 404);
        }

        $slots_booked = $doctorData->slots_booked ?? [];

        if (isset($slots_booked[$date])) {
            if (in_array($time, $slots_booked[$date])) {
                return response()->json([
                    'success' => false,
                    'msg' => 'This time already booked.'
                ], 400);
            } else {
                $slots_booked[$date][] = $time;
            }
        } else {
            $slots_booked[$date] = [$time];
        }

        $userData = User::find($userId);

        $appointment = Appointment::create([
            'userId' => $userId,
            'docId' => $docId,
            'slotDate' => $date,
            'slotTime' => $time,
        ]);

        $doctorData->update([
            'slots_booked' => $slots_booked
        ]);

        return response()->json([
            'success' => true,
            'msg' => 'Appointment booked successfully',
            'data' => $appointment
        ], 201);

    } catch (\Exception $err) {
        return response()->json([
            'success' => false,
            'msg' => 'Appointment failed to save',
            'error' => $err->getMessage()
        ], 500);
    }
}

////////////////////////////////////////////////////////**
/////////////////////// * get  appointment.
////////////////////////// */
public function getUserAppointments(Request $request)
{
    try {
        $userId = $request->user()->id;

        $appointments = Appointment::with([
            'user:id,name,email,image',
            'doctor:id,name,email,image,experience,fees,about'
        ])
        ->where('userId', $userId)
        ->where('cancelled', false)
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $appointments
        ], 200);

    } catch (\Exception $err) {
        return response()->json([
            'success' => false,
            'msg' => 'Failed to fetch appointments',
            'error' => $err->getMessage()
        ], 500);
    }
}



public function Appointments(){
   
  try {
      

          $appointments = Appointment::with([
            'user:id,name,email,image,dob',
            'doctor:id,name,email,image,experience,fees,about'
        ])
        ->latest()
        ->get();

        return response()->json([
            'success' => true,
            'data' => $appointments
        ], 200);

    } catch (\Exception $err) {
        return response()->json([
            'success' => false,
            'msg' => 'Failed to fetch appointments',
            'error' => $err->getMessage()
        ], 500);
    }
}

  
////////////////////////////////////////////////////////**
/////////////////////// * cancel  appointment.
////////////////////////// */
    public function cancelAppointment($id)
    {
        try {
            $appointment = Appointment::find($id);

            if (!$appointment) {
                return response()->json([
                    'success' => false,
                    'msg' => 'Appointment not found'
                ], 404);
            }

            $appointment->update([
                'cancelled' => true
            ]);

            return response()->json([
                'success' => true,
                'msg' => 'Appointment cancelled successfully'
            ], 200);

        } catch (\Exception $err) {
            return response()->json([
                'success' => false,
                'msg' => 'Failed to cancel appointment',
                'error' => $err->getMessage()
            ], 500);
        }
    }
}
