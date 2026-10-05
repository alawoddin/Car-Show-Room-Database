<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User_Capitals;
use App\Models\User;
use Illuminate\Http\Request;

class UserCapitalController extends Controller
{
    public function UserCapital()
{
    $userCapitals = User_Capitals::with('user')->latest()->get();

    return view('admin.Investment.user_capital', compact('userCapitals'));
}

    public function AddUserCapital()
{
    $users = User::where('role', 'user')->latest()->get();

    return view('admin.Investment.add_user_capital', compact('users'));
}

    public function StoreUserCapital(Request $request)
    {
        

        User_Capitals::create([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

          $notification = array(
                'message' => 'Client Added Successfully',
                'alert-type' => 'success'
            );

            return redirect()->route('user.capital')->with($notification);


    }

    public function EditUserCapital(int $id)
    {
        $userCapital = User_Capitals::findOrFail($id);
        $users = User::where('role', 'user')->latest()->get();

        return view('admin.Investment.edit_user_capital', compact('userCapital', 'users'));
    }

    public function UpdateUserCapital(Request $request)
    {
        $userCapital = User_Capitals::findOrFail($request->id);

        $userCapital->update([
            'user_id' => $request->user_id,
            'amount' => $request->amount,
            'date' => $request->date,
            'description' => $request->description,
        ]);

        $notification = array(
            'message' => 'User Capital Updated Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('user.capital')->with($notification);
    }

    public function DeleteUserCapital(int $id)
    {
        $userCapital = User_Capitals::findOrFail($id);
        $userCapital->delete();

        $notification = array(
            'message' => 'User Capital Deleted Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('user.capital')->with($notification);
    }
}
