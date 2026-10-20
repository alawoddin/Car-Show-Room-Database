<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\User;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function AllExpense()
    {
        $expenses = Expense::with('user')->latest()->get();

        return view('admin.Expense.all_expense', compact('expenses'));
    }

    public function AddExpense()
    {
        $users = User::whereIn('role', ['admin', 'user'])->orderBy('role')->latest()->get();

        return view('admin.Expense.add_expense', compact('users'));
    }

    public function StoreExpense(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'expense_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        Expense::create([
            'user_id' => $request->user_id,
            'expense_name' => $request->expense_name,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('all.expense')
            ->with([
                'message' => 'Expense Added Successfully',
                'alert-type' => 'success',
            ]);
    }

    public function EditExpense(int $id)
    {
        $expense = Expense::findOrFail($id);
        $users = User::whereIn('role', ['admin', 'user'])->orderBy('role')->latest()->get();

        return view('admin.Expense.edit_expense', compact('expense', 'users'));
    }

    public function UpdateExpense(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:expenses,id',
            'user_id' => 'required|exists:users,id',
            'expense_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $expense = Expense::findOrFail($request->id);

        $expense->update([
            'user_id' => $request->user_id,
            'expense_name' => $request->expense_name,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        return redirect()
            ->route('all.expense')
            ->with([
                'message' => 'Expense Updated Successfully',
                'alert-type' => 'success',
            ]);
    }

    public function DeleteExpense(int $id)
    {
        $expense = Expense::findOrFail($id);
        $expense->delete();

        return redirect()
            ->route('all.expense')
            ->with([
                'message' => 'Expense Deleted Successfully',
                'alert-type' => 'success',
            ]);
    }
}
