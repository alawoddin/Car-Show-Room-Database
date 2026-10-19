<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientVehicleController extends Controller
{
    public function MyVehicles() {
        $vehicles = Purchase::forUser(Auth::id())
            ->latest()
            ->get();

        return view('client.vehicles.all_vehicles', compact('vehicles'));
    }

    public function MyVehiclesView(int $id) {
         $vehicle = Purchase::forUser(Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        return view('client.vehicles.vehicles_details', compact('vehicle'));
    }
}
