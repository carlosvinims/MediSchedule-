<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Specialty;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        $specialties = Specialty::orderBy('name')->get();

        $doctors = Doctor::with('specialty')
            ->where('is_active', true)
            ->when($request->filled('specialty'), function ($query) use ($request) {
                $query->where('specialty_id', $request->specialty);
            })
            ->orderBy('name')
            ->limit(6)
            ->get();

        return view('doctors.index', compact(
            'specialties',
            'doctors'
        ));
    }
}
