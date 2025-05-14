<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $datas = User::get()->all();

        return view('user.index', compact('datas'));
    }

    public function create()
    {
        // dd("ihan");
        return view('user.create');
    }

    public function store(Request $request)
    {

        // dd( $request);
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);


        return redirect()->route('user.index')->with('success', 'User created: ' . $request->name);
    }

    public function edit($id)
    {
        $data = User::findOrFail($id);

        return view('user.edit', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->password) {
            $request->validate(['password' => 'min:6']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('user.index')->with('success', 'User Updated: ' . $request->name);
    }

    public function destroy($id)
    {
        $data = User::destroy($id);
        // dd($data);
        return redirect()->route('user.index')->with('success', 'User deleted successfully.');
    }
}
