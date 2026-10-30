<?php

namespace App\Services;

use App\Models\Appointment;
use App\Models\User;
use App\Models\Doctor;
use Carbon\Carbon;
use DomainException;
use Illuminate\Support\Collection;

class AppointmentService
{
    /**
     * Retorna os horários disponíveis para um médico em uma data.
     */
    public function availableSlots(int $doctorId,string $date,int $patientId): array {
        $doctor = Doctor::where('is_active', true)->find($doctorId);

        if (!$doctor) {
            throw new DomainException('Médico não encontrado ou inativo.');
        }

        if (!$doctor->work_start_time || !$doctor->work_end_time) {
            throw new DomainException(
                'O médico não possui expediente configurado.'
            );
        }

        $duration = (int) $doctor->slot_duration;

        if ($duration <= 0) {
            throw new DomainException(
                'A duração da consulta do médico é inválida.'
            );
        }

        $day = Carbon::createFromFormat('!Y-m-d', $date);

        if ($day->isPast() && !$day->isToday()) {
            throw new DomainException(
                'Não é possível consultar horários de datas passadas.'
            );
        }

        $start = Carbon::parse($date . ' ' . $doctor->work_start_time);
        $end = Carbon::parse($date . ' ' . $doctor->work_end_time);

        if (!$end->gt($start)) {
            throw new DomainException(
                'O horário de encerramento deve ser posterior ao início.'
            );
        }

        $doctorAppointments = Appointment::with('doctor')
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'canceled')
            ->get();

        $patientAppointments = Appointment::with('doctor')
            ->where('patient_id', $patientId)
            ->whereDate('appointment_date', $date)
            ->where('status', '!=', 'canceled')
            ->get();

        $slots = [];

        for (
            $slot = $start->copy();
            $slot->copy()->addMinutes($duration)->lte($end);
            $slot->addMinutes($duration)
        ) {
            $slotEnd = $slot->copy()->addMinutes($duration);

            if ($slot->lte(now())) {
                continue;
            }

            if (
                $this->hasOverlap($doctorAppointments, $slot, $slotEnd) ||
                $this->hasOverlap($patientAppointments, $slot, $slotEnd)
            ) {
                continue;
            }

            $slots[] = $slot->format('H:i');
        }

        return $slots;
    }

    /**
     * Verifica se algum agendamento ocupa parte do intervalo.
     */
    private function hasOverlap(
        Collection $appointments,
        Carbon $start,
        Carbon $end
    ): bool {
        foreach ($appointments as $appointment) {
            $existingStart = Carbon::parse(
                $appointment->appointment_date
            );

            $duration = (int) $appointment->doctor->slot_duration;

            if ($duration <= 0) {
                $duration = 30;
            }

            $existingEnd = $existingStart->copy()
                ->addMinutes($duration);

            if (
                $existingStart->lt($end) &&
                $existingEnd->gt($start)
            ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Agenda uma consulta após validar as regras de negócio.
     */
    public function schedule(array $data): Appointment
    {
        $appointmentDate = Carbon::parse($data['appointment_date']);

        if ($appointmentDate->lte(now())) {
            throw new DomainException(
                'A consulta deve ser agendada para uma data e horário futuros.'
            );
        }

        $doctor = Doctor::where('is_active', true)
            ->find($data['doctor_id']);

        if (!$doctor) {
            throw new DomainException(
                'Médico não encontrado ou inativo.'
            );
        }

        if (!$doctor->work_start_time || !$doctor->work_end_time) {
            throw new DomainException(
                'O médico não possui expediente configurado.'
            );
        }

        $duration = (int) $doctor->slot_duration;

        if ($duration <= 0) {
            throw new DomainException(
                'A duração da consulta do médico é inválida.'
            );
        }

        $workStart = Carbon::parse(
            $appointmentDate->format('Y-m-d') . ' ' .
            $doctor->work_start_time
        );

        $workEnd = Carbon::parse(
            $appointmentDate->format('Y-m-d') . ' ' .
            $doctor->work_end_time
        );

        $appointmentEnd = $appointmentDate->copy()
            ->addMinutes($duration);

        if (
            $appointmentDate->lt($workStart) ||
            $appointmentEnd->gt($workEnd)
        ) {
            throw new DomainException(
                'O horário escolhido está fora do expediente do médico.'
            );
        }

        if (
            $workStart->diffInMinutes($appointmentDate) % $duration !== 0
        ) {
            throw new DomainException(
                'O horário escolhido não corresponde aos intervalos de atendimento.'
            );
        }

        $doctorAppointments = Appointment::with('doctor')
            ->where('doctor_id', $doctor->id)
            ->whereDate(
                'appointment_date',
                $appointmentDate->format('Y-m-d')
            )
            ->where('status', '!=', 'canceled')
            ->get();

        if (
            $this->hasOverlap(
                $doctorAppointments,
                $appointmentDate,
                $appointmentEnd
            )
        ) {
            throw new DomainException(
                'O médico já possui uma consulta nesse intervalo.'
            );
        }

        $patientAppointments = Appointment::with('doctor')
            ->where('patient_id', $data['patient_id'])
            ->whereDate(
                'appointment_date',
                $appointmentDate->format('Y-m-d')
            )
            ->where('status', '!=', 'canceled')
            ->get();

        if (
            $this->hasOverlap(
                $patientAppointments,
                $appointmentDate,
                $appointmentEnd
            )
        ) {
            throw new DomainException(
                'Você já possui outra consulta nesse intervalo.'
            );
        }

        return Appointment::create([
            'doctor_id' => $doctor->id,
            'patient_id' => $data['patient_id'],
            'appointment_date' => $appointmentDate,
            'status' => 'scheduled',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function cancel(
        int $appointmentId,
        User $userRequesting,
        ?Doctor $doctorRequesting = null
    ): Appointment {
        $appointment = Appointment::findOrFail($appointmentId);

        $isPatient =
            $appointment->patient_id === $userRequesting->id &&
            $userRequesting->role === 'patient';

        $isDoctor =
            $doctorRequesting !== null &&
            $appointment->doctor_id === $doctorRequesting->id;

        $isAdmin = $userRequesting->role === 'admin';

        if (!$isPatient && !$isDoctor && !$isAdmin) {
            throw new DomainException(
                'Você não tem permissão para cancelar esta consulta.'
            );
        }

        if ($appointment->status === 'canceled') {
            throw new DomainException('Esta consulta já foi cancelada.');
        }

        if (
            now()->addHours(24)->greaterThan(
                $appointment->appointment_date
            )
        ) {
            throw new DomainException(
                'A consulta só pode ser cancelada com no mínimo 24 horas de antecedência.'
            );
        }

        $appointment->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);

        return $appointment;
    }
}