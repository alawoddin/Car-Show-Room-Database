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
        return view('admin.capital.capital_transactions' , compact('capitalTransactions'));
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

   

    
}
