<?php

namespace App\Http\Controllers\Backend;

use App\Exports\PurchasesExport;
use App\Http\Controllers\Controller;
use App\Imports\PurchasesImport;
use App\Models\Purchase;
use App\Models\User;
use App\Support\PurchaseCostCalculator;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PurchaseController extends Controller
{
    public function AllPurchases()
    {
        $purchases = Purchase::with(['user', 'users'])->latest()->get();

        return view('admin.purchase.all_purchases', compact('purchases'));
    }

    public function AddPurchase()
    {
        $users = User::whereIn('role', ['admin', 'user'])->orderBy('role')->latest()->get();

        return view('admin.purchase.add_purchase', compact('users'));
    }

    public function StorePurchase(Request $request)
    {
        $userIds = $this->selectedUserIds($request);

        if ($userIds === []) {
            return back()
                ->withInput()
                ->withErrors(['user_ids' => 'Select 1, 2, or 3 users (admin can be included).']);
        }

        $costs = PurchaseCostCalculator::calculate([
            'buying_fee' => $request->buying_fee,
            'towing_fee' => $request->towing_fee,
            'shipping' => $request->shipping,
            'commission' => $request->commission,
            'clearing' => $request->clearing,
            'extra_charges' => $request->extra_charges,
        ]);

        $purchase = Purchase::create([
            'user_id' => $userIds[0],
            'buying_date' => $request->buying_date,
            'lot_number' => $request->lot_number,
            'vin' => $request->vin,
            'cylinder' => $request->cylinder,
            'color' => $request->color,
            'make' => $request->make,
            'model' => $request->model,
            'shipping_company' => $request->shipping_company,
            'commission' => $request->commission ?? 0,
            'buying_fee' => $request->buying_fee ?? 0,
            'towing_fee' => $request->towing_fee ?? 0,
            'shipping' => $request->shipping ?? 0,
            'total_aed' => $costs['total_aed'],
            'clearing' => $request->clearing ?? 0,
            'extra_charges' => $request->extra_charges ?? 0,
            'custom_duty' => $costs['custom_duty'],
            'grand_total' => $costs['grand_total'],
            'location' => $request->location,
            'container_number' => $request->container_number,
            'arrival_date' => $request->arrival_date,
            'vin_link' => $request->vin_link,
            'status' => $request->status ?? 'Purchased',
            'description' => $request->description,
            'selling_price' => 0,
            'profit' => 0,
            'bill_no' => null,
            'date_of_arriving' => null,
            'customer_name' => null,
        ]);

        $purchase->users()->sync($userIds);

        return redirect()
            ->route('all.purchases')
            ->with([
                'message' => 'Purchase Added Successfully',
                'alert-type' => 'success',
            ]);
    }

    public function SalePurchase(int $id)
    {
        $purchase = Purchase::with('users')->findOrFail($id);
        $users = User::whereIn('role', ['admin', 'user'])->orderBy('role')->get();

        return view('admin.purchase.add_purchase', compact('purchase', 'users'));
    }

    public function StoreSale(Request $request, int $id)
    {
        $purchase = Purchase::findOrFail($id);

        $costs = PurchaseCostCalculator::calculate([
            'buying_fee' => $purchase->buying_fee,
            'towing_fee' => $purchase->towing_fee,
            'shipping' => $purchase->shipping,
            'commission' => $purchase->commission,
            'clearing' => $purchase->clearing,
            'extra_charges' => $purchase->extra_charges,
            'selling_price' => $request->selling_price,
        ]);

        $purchase->selling_price = $request->selling_price ?? 0;
        $purchase->profit = $costs['profit'];
        $purchase->bill_no = $request->bill_no;
        $purchase->date_of_arriving = $request->date_of_arriving;
        $purchase->customer_name = $request->customer_name;
        $purchase->status = $request->status;
        $purchase->description = $request->description;
        $purchase->save();

        return redirect()
            ->route('all.purchases')
            ->with([
                'message' => 'Sale Information Updated Successfully',
                'alert-type' => 'success',
            ]);
    }

    public function EditPurchase(int $id)
    {
        $purchase = Purchase::with('users')->findOrFail($id);
        $users = User::whereIn('role', ['admin', 'user'])->orderBy('role')->latest()->get();

        return view('admin.purchase.edit_purchase', compact('purchase', 'users'));
    }

    public function UpdatePurchase(Request $request)
    {
        $purchase = Purchase::findOrFail($request->id);
        $userIds = $this->selectedUserIds($request);

        if ($userIds === []) {
            return back()
                ->withInput()
                ->withErrors(['user_ids' => 'Select 1, 2, or 3 users (admin can be included).']);
        }

        $costs = PurchaseCostCalculator::calculate([
            'buying_fee' => $request->buying_fee,
            'towing_fee' => $request->towing_fee,
            'shipping' => $request->shipping,
            'commission' => $request->commission,
            'clearing' => $request->clearing,
            'extra_charges' => $request->extra_charges,
            'selling_price' => $request->selling_price,
        ]);

        $purchase->user_id = $userIds[0];
        $purchase->buying_date = $request->buying_date;
        $purchase->lot_number = $request->lot_number;
        $purchase->vin = $request->vin;
        $purchase->cylinder = $request->cylinder;
        $purchase->color = $request->color;
        $purchase->make = $request->make;
        $purchase->model = $request->model;
        $purchase->shipping_company = $request->shipping_company;
        $purchase->commission = $request->commission ?? 0;
        $purchase->buying_fee = $request->buying_fee ?? 0;
        $purchase->towing_fee = $request->towing_fee ?? 0;
        $purchase->shipping = $request->shipping ?? 0;
        $purchase->total_aed = $costs['total_aed'];
        $purchase->clearing = $request->clearing ?? 0;
        $purchase->extra_charges = $request->extra_charges ?? 0;
        $purchase->custom_duty = $costs['custom_duty'];
        $purchase->grand_total = $costs['grand_total'];
        $purchase->location = $request->location;
        $purchase->container_number = $request->container_number;
        $purchase->arrival_date = $request->arrival_date;
        $purchase->vin_link = $request->vin_link;
        $purchase->status = $request->status;
        $purchase->description = $request->description;
        $purchase->selling_price = $request->selling_price ?? 0;
        $purchase->profit = $costs['profit'];
        $purchase->bill_no = $request->bill_no;
        $purchase->date_of_arriving = $request->date_of_arriving;
        $purchase->customer_name = $request->customer_name;
        $purchase->save();

        $purchase->users()->sync($userIds);

        return redirect()
            ->route('all.purchases')
            ->with([
                'message' => 'Purchase Updated Successfully',
                'alert-type' => 'success',
            ]);
    }

    public function DeletePurchase(int $id)
    {
        $purchase = Purchase::findOrFail($id);
        $purchase->users()->detach();
        $purchase->delete();

        return redirect()
            ->route('all.purchases')
            ->with([
                'message' => 'Purchase Deleted Successfully',
                'alert-type' => 'success',
            ]);
    }

    public function ExportPurchases()
    {
        return Excel::download(
            new PurchasesExport,
            'purchases.xlsx'
        );
    }

    public function ImportPurchases(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv',
        ]);

        Excel::import(
            new PurchasesImport,
            $request->file('file')
        );

        return redirect()
            ->route('all.purchases')
            ->with([
                'message' => 'Purchases Imported Successfully',
                'alert-type' => 'success',
            ]);
    }

    /**
     * @return array<int, int>
     */
    private function selectedUserIds(Request $request): array
    {
        $ids = collect($request->input('user_ids', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values()
            ->take(3)
            ->all();

        if ($ids === [] && $request->filled('user_id')) {
            $ids = [(int) $request->user_id];
        }

        return $ids;
    }
}
