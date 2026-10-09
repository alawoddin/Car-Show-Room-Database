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

    public function SearchByMonth(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|min:1|max:12',
            'year' => 'required|integer|min:2022|max:2100',
        ]);

        $startDate = \Carbon\Carbon::create(
            $request->year,
            $request->month,
            1
        )->startOfDay();

        // Include the selected month and the next two months.
        $endDate = $startDate->copy()->addMonths(3);

        // Expenses
        $expenses = Expense::where('date', '>=', $startDate->toDateString())
            ->where('date', '<', $endDate->toDateString())
            ->get();

        // Purchases
        $purchases = Purchase::where('buying_date', '>=', $startDate->toDateString())
            ->where('buying_date', '<', $endDate->toDateString())
            ->get();

        // Sales from the existing purchases table
        $sales = Purchase::where('status', 'Sold')
            ->where('buying_date', '>=', $startDate->toDateString())
            ->where('buying_date', '<', $endDate->toDateString())
            ->get();

        // User Capital
        $userCapitals = User_Capitals::where('date', '>=', $startDate->toDateString())
            ->where('date', '<', $endDate->toDateString())
            ->get();

        return view('admin.reports.search_by_month', compact(
            'startDate',
            'endDate',
            'expenses',
            'purchases',
            'sales',
            'userCapitals'
        ));
    }

    public function SearchByYear(Request $request)
{
    $request->validate([
        'year' => 'required|integer|min:2022|max:2100',
    ]);

    $year = $request->year;

    // Expenses for selected year
    $expenses = Expense::whereYear('date', $year)->get();

    // Purchases for selected year
    $purchases = Purchase::whereYear('buying_date', $year)->get();

    // Sales from the existing purchases table
    $sales = Purchase::where('status', 'Sold')
        ->whereYear('buying_date', $year)
        ->get();

    // User Capital for selected year
    $userCapitals = User_Capitals::whereYear('date', $year)->get();

    return view('admin.reports.search_by_year', compact(
        'year',
        'expenses',
        'purchases',
        'sales',
        'userCapitals'
    ));
}
}
