<?php
namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use Carbon\Carbon;
use DomainException;

class AppointmentService
{
   
    public function schedule(array $data):  Appointment
    {
        $appointmentDate = Carbon::parse($data['appointment_date']);

        //Validar horário retroativo
        if ($appointmentDate->isPast()) {
            throw new DomainException("Não é possível agendar consultas em datas passadas.");
        }

        // Validar horário na agenda do médico
        $doctorHasConflict = Appointment::where('doctor_id', $data['doctor_id'])
            ->where('appointment_date', $appointmentDate)
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($doctorHasConflict) {
            throw new DomainException("O médico já possui uma consulta agendada para este horário.");
        }

        //Validar choque de horário na agenda do paciente
        $patientHasConflict = Appointment::where('patient_id', $data['patient_id'])
            ->where('appointment_date', $appointmentDate)
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($patientHasConflict) {
            throw new DomainException("O paciente já possui outra consulta agendada neste mesmo horário.");
        }

        // Registrar a consulta
        return Appointment::create([
            'doctor_id'        => $data['doctor_id'],
            'patient_id'          => $data['patient_id'],
            'appointment_date' => $appointmentDate,
            'status'           => 'scheduled',
        ]);
    }

    
    public function cancel(int $appointmentId, User $userRequesting, ?Doctor $doctorRequesting = null): Appointment
    {
        $appointment = Appointment::findOrFail($appointmentId);

        // Validar quem está solicitando o cancelamento 
        $isPatient = $appointment->patient_id === $userRequesting->id && $userRequesting->role === 'patient';;
        $isDoctor = $doctorRequesting !== null && $appointment->doctor_id === $doctorRequesting->id;
        $isAdmin = $userRequesting->role === 'admin';

        if (!$isPatient && !$isDoctor && !$isAdmin) {
            throw new DomainException("Você não tem permissão para cancelar esta consulta.");
        }

        // Validar a regra das 24 horas de antecedência
        if ($appointment->status === 'canceled') { throw new DomainException("Esta consulta já foi cancelada.");
        }
        if (now()->addHours(24)->greaterThan($appointment->appointment_date)) {
            throw new DomainException("A consulta só pode ser cancelada com no mínimo 24 horas de antecedência.");
        }

        // Atualizar o status
        $appointment->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);

        return $appointment;
    }
}