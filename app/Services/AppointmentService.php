<?php
namespace App\Services;

use App\Models\Commitment;
use App\Models\User;
use Carbon\Carbon;
use Exception;

class AppointmentService
{
   
    public function schedule(array $data): Commitment
    {
        $appointmentTime = Carbon::parse($data['appointment_time']);

        //Validar horário retroativo
        if ($appointmentTime->isPast()) {
            throw new Exception("Não é possível agendar consultas em datas passadas.");
        }

        // Validar horário na agenda do médico
        $doctorHasConflict = Commitment::where('doctor_id', $data['doctor_id'])
            ->where('appointment_time', $appointmentTime)
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($doctorHasConflict) {
            throw new Exception("O médico já possui uma consulta agendada para este horário.");
        }

        //Validar choque de horário na agenda do paciente
        $patientHasConflict = Commitment::where('user_id', $data['user_id'])
            ->where('appointment_time', $appointmentTime)
            ->where('status', '!=', 'canceled')
            ->exists();

        if ($patientHasConflict) {
            throw new Exception("O paciente já possui outra consulta agendada neste mesmo horário.");
        }

        // Registrar a consulta
        return Commitment::create([
            'doctor_id'        => $data['doctor_id'],
            'user_id'          => $data['user_id'],
            'appointment_time' => $appointmentTime,
            'status'           => 'scheduled',
        ]);
    }

    
    public function cancel(int $appointmentId, User $userRequesting): Commitment
    {
        $appointment = Commitment::findOrFail($appointmentId);

        // Validar quem está solicitando o cancelamento 
        $isPatient = $appointment->user_id === $userRequesting->id;
        $isDoctor = $appointment->doctor->user_id ?? null === $userRequesting->id;
        $isAdmin = $userRequesting->role === 'admin';

        if (!$isPatient && !$isDoctor && !$isAdmin) {
            throw new Exception("Você não tem permissão para cancelar esta consulta.");
        }

        // Validar a regra das 24 horas de antecedência
        $hoursDiff = now()->diffInHours($appointment->appointment_time, false);

        if ($hoursDiff < 24) {
            throw new Exception("A consulta só pode ser cancelada com no mínimo 24 horas de antecedência.");
        }

        // Atualizar o status
        $appointment->update([
            'status' => 'canceled'
        ]);

        return $appointment;
    }
}