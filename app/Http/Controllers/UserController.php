<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();
        return view('layouts.app', compact('users'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $user = User::find($id);

        if (auth()->user()->id != $user->id) {
            return redirect()->back();
        }

        return view('show', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $this->validate($request, [
            'email' => 'required|email|unique:users,email,' . $user->id,
            'name' => 'required|string|max:255',
            'edad' => 'nullable|integer|min:0', // Adjust validation for edad as needed
            'telefono' => 'nullable|string',
            'acerca_de' => 'nullable|string',
            'profileImage' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Add validation for image type and size
        ]);

            // Update user info
            $user->email = $request->email;
            $user->name = $request->name;
            $user->edad = $request->edad;
            $user->telefono = $request->telefono;
            $user->acerca_de = $request->acerca_de;

            if ($request->hasFile('profileImage')) {
                $fileName = time() . '.' . $request->profileImage->getClientOriginalExtension();
                $request->profileImage->move(public_path('images'), $fileName);
                $user->imagen = $fileName;
            }

            $user->save();

        return redirect()->back()->with('success', 'Perfil actualizado exitosamente!');
    }
}
