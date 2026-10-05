<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClientRegisterController extends Controller
{
    public function ClientRegister(){
        return view('admin.client.client_register');
    }
}
