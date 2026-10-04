<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function ClientProfile(){

        $id = Auth::user()->id;
        $profileData = User::find($id);
        return view('client.client_profile',compact('profileData')); 
    } // End Method 

}
