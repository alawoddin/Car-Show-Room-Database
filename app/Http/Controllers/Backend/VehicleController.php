<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Purchase;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    public function VehicleStatus()
    {
        $vehicles = Purchase::latest()->get();

        return view('admin.vehicle.vehicle_status', compact('vehicles'));
    }

    public function UpdateVehicleStatus(Request $request, int $id)
    {
        $purchase = Purchase::findOrFail($id);

        $request->validate([
            'status' => 'required|in:Purchased,Loaded,Shipped,Delivered,On Hand,At UAE,Sold',
        ]);

        $purchase->status = $request->status;

        $purchase->save();

        $notification = [
            'message' => 'Vehicle status updated successfully.',
            'alert-type' => 'success',
        ];

        return redirect()
            ->route('vehicle.status')
            ->with($notification);
    }

    public function VehicleStatusView(int $id)
    {
        $vehicle = Purchase::with('user')->findOrFail($id);

        return view('admin.vehicle.vehicle_details', compact('vehicle'));
    }

   public function DownloadInvoice(int $id)
{
    $vehicle = Purchase::with('user')->findOrFail($id);

    $pdf = Pdf::loadView(
        'admin.vehicle.vehicle_details',
        compact('vehicle')
    )->setPaper('a4', 'portrait');

    return $pdf->download(
        'vehicle-invoice-' . $vehicle->id . '.pdf'
    );
}
}
