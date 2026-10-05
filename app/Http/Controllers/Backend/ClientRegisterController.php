<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class ClientRegisterController extends Controller
{
    public function ClientRegister(){
        $client = User::get();
        return view('admin.client.client_register' , compact('client'));
    }

    public function AddClient(){
        return view('admin.client.add_client');
    }
}
