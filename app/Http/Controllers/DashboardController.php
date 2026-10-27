<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Specialty;
use Illuminate\Http\Request;

class DashboardController extends Controller
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

        return view('dashboard', compact(
            'specialties',
            'doctors'
        ));
    }
}