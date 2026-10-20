<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Illuminate\Support\Facades\Auth;

class ClientVehicleController extends Controller
{
    public function MyVehicles()
    {
        $vehicles = Purchase::forUser((int) Auth::id())
            ->with('users')
            ->latest()
            ->get();

        return view('client.vehicles.all_vehicles', compact('vehicles'));
    }

    public function MyVehiclesView(int $id)
    {
        $vehicle = Purchase::forUser((int) Auth::id())
            ->with('users')
            ->where('purchases.id', $id)
            ->firstOrFail();

        $myShare = $vehicle->buyerShares()
            ->first(fn ($share) => (int) $share['user']->id === (int) Auth::id());

        return view('client.vehicles.vehicles_details', compact('vehicle', 'myShare'));
    }
}
