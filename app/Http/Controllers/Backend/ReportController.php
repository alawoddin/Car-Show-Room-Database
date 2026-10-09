<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Purchase;
use App\Models\User_Capitals;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function AllReports()
    {

        return view('admin.reports.all_report');
    }


    public function SearchByDate(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
        ]);

        $date = $request->date;

        // Daily Expenses
        $expenses = Expense::whereDate('date', $date)->get();

        // Daily Purchases
        $purchases = Purchase::whereDate('buying_date', $date)->get();

        // Daily Sales: Sold vehicles from the purchases table
        $sales = Purchase::where('status', 'Sold')
            ->whereDate('buying_date', $date)
            ->get();

        // Daily User Capital
        $userCapitals = User_Capitals::whereDate('date', $date)->get();

        return view('admin.reports.search_by_date', compact(
            'date',
            'expenses',
            'purchases',
            'sales',
            'userCapitals'
        ));
    }
}
