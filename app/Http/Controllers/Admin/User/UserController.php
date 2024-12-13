<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
    public function index()
    {
        $clients = Client::all();
        return view('admin.pages.client', compact('clients'));
    }
    // Hiển thị form thêm client

    // Lưu client mới
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required|min:6',
        ]);

        $client = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'api_token' => Client::generateUniqueToken(),
        ]);

        // Trả về JSON với thông tin client mới
        return response()->json([
            'message' => 'Client created successfully.',
            'data' => $client,
        ], 200);
    }

    // Hiển thị form sửa client
    public function edit($id)
    {
        $client = Client::findOrFail($id);
        return view('admin.clients.edit', compact('client'));
    }

    // Cập nhật client
    public function update(Request $request, $id)
    {
        $client = Client::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email,' . $id,
        ]);

        $client->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return response()->json([
            'message' => 'Client updated successfully.',
            'data' => $client,
        ], 200);
    }

    // Xóa client
    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return response()->json(['message' => 'Client deleted successfully.'], 200);
    }
    
}
