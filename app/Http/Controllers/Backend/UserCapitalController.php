<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User_Capitals;
use Illuminate\Http\Request;

class UserCapitalController extends Controller
{
    public function UserCapital()
{
    $userCapitals = User_Capitals::with('user')->latest()->get();

    return view('admin.Investment.user_capital', compact('userCapitals'));
}
}
