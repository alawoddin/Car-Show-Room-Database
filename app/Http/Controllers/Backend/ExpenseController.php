<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function AllExpense()
    {
        $expenses = Expense::latest()->get();

        return view('admin.Expense.all_expense', compact('expenses'));
    }

    public function AddExpense()
    {
        return view('admin.Expense.add_expense');
    }
    
    public function StoreExpense(Request $request)
    {
        Expense::create([
            'expense_name' => $request->expense_name,
            'amount'       => $request->amount,
            'date'         => $request->date,
            'description'  => $request->description,
        ]);

        $notification = [
            'message' => 'Expense Added Successfully',
            'alert-type' => 'success'
        ];
        return redirect()->route('all.expense')->with($notification);
    }
}
