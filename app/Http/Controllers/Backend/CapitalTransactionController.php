<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\CapitalTransactions;
use Illuminate\Http\Request;

class CapitalTransactionController extends Controller
{

    public function CapitalTransactions()
    {
        $capitalTransactions = CapitalTransactions::all();
        return view('admin.capital.capital_transactions', compact('capitalTransactions'));
    }

    public function AddCapitalTransaction()
    {
        return view('admin.capital.add_capital_transaction');
    }

    public function StoreCapitalTransaction(Request $request)
    {


        CapitalTransactions::create([
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        $notification = array(
            'message' => 'Capital transaction added successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('capital.transactions')->with($notification);
    }

    public function EditCapitalTransaction(int $id)
    {
        $capitalTransaction = CapitalTransactions::findOrFail($id);
        return view('admin.capital.edit_capital_transaction', compact('capitalTransaction'));
    }

    public function UpdateCapitalTransaction(Request $request)
    {
        $capitalTransaction = CapitalTransactions::findOrFail($request->id);

        $capitalTransaction->update([
            'amount' => $request->amount,
            'description' => $request->description,
            'date' => $request->date,
        ]);

        $notification = array(
            'message' => 'Capital transaction updated successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('capital.transactions')->with($notification);
    }

    public function DeleteCapitalTransaction(int $id)
    {
        $capitalTransaction = CapitalTransactions::findOrFail($id);
        $capitalTransaction->delete();

        $notification = array(
            'message' => 'Capital transaction deleted successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('capital.transactions')->with($notification);
    }
}
