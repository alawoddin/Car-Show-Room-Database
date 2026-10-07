<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function VehicleStatus()
{
    $vehicles = Purchase::latest()->get();

    return view('admin.vehicle.vehicle_status', compact('vehicles'));
}
}
