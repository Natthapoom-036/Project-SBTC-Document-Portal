<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Division;

class DivisionController extends Controller
{
    public function store(Request $request) {
    Division::create($request->all());
    return back()->with('success', 'เพิ่มฝ่ายงานเรียบร้อย');
}
    public function destroy(Division $division) {
    $division->delete();
    return back()->with('success', 'ลบฝ่ายงานเรียบร้อย');
}
}
