<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function store(Request $request) {
    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => bcrypt($request->password),
        'role' => 'admin' // หรือรับจาก form ก็ได้
    ]);
    return back()->with('success', 'เพิ่ม User เรียบร้อย');
}
public function destroy(User $user) {
    $user->delete();
    return back()->with('success', 'ลบ User เรียบร้อย');
}
}
