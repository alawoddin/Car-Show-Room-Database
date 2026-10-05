<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;


class ClientRegisterController extends Controller
{
    public function ClientRegister(){
        $client = User::where('role', 'user')->latest()->get();
        return view('admin.client.client_register' , compact('client'));
    }

    public function AddClient(){
        return view('admin.client.add_client');
    }

    public function StoreClient(Request $request){

    if ($request->file('photo')) {
            $image = $request->file('photo');
            $manager = new ImageManager(new Driver());
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();
            $img = $manager->read($image);
            $img->resize(100, 100)->save(public_path('upload/client_images/' . $name_gen));
            $save_url = 'upload/client_images/' . $name_gen;

            // Create User
            User::insert([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => bcrypt($request->password),
            'photo' => $save_url,
            'role' => 'user',
        ]);

        $notification = array(
            'message' => 'Client Added Successfully',
            'alert-type' => 'success'
        );

        return redirect()->route('client.register')->with($notification);


        }



    
    }
}
