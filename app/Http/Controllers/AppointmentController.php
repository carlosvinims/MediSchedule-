<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentRequest;
use App\Http\Requests\DoctorRequest;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use DomainException;
use App\Services\AppointmentService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AppointmentController extends Controller
{
    protected AppointmentService $appointmentService;
    
    public function __construct(AppointmentService $appointmentService)
    {
         $this->appointmentService = $appointmentService;
    }
    public function index(): View
    {
        $appointments = Appointment::where('patient_id', auth()->id())->get();
        
        return view('appointments.index', compact('appointments'));
    }

    public function create(): View
    {
        $doctors = Doctor::all();

        return view('appointments.create', compact('doctors'));
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'doctor_id' => ['required', 'exists:doctors,id'],
            'appointment_date' => ['required', 'date'],
        ]);

        $data['patient_id'] = auth()->id();

        try {
            $this->appointmentService->schedule($data);
        } catch (DomainException $exception) {
            return back()->withInput()->with('error', $exception->getMessage());
        }

        return redirect()->route('appointments.index')->with('success', 'Consulta agendada com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Appointment $appointment): View
    {
        $appointment = Appointment::where('patient_id', auth()->id())->findOrFail($appointment->id);
        
        return view('appointments.show', compact('appointment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment): RedirectResponse
    {
        abort_unless($appointment->patient_id === auth()->id(),403);
        
        try {
            $this->appointmentService->cancel($appointment->id, auth()->user());
        } catch (DomainException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return redirect()->route('appointments.index')->with('success', 'Consulta cancelada com sucesso!');
    }
}
