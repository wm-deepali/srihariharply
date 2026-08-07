<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Introduction;
use Illuminate\Http\Request;

class IntroductionController extends Controller
{
    public function index()
    {
        $introduction = Introduction::firstOrCreate(['id' => 1], ['status' => 'active']);

        return view('admin.introduction.index', compact('introduction'));
    }

    public function update(Request $request, Introduction $introduction)
    {
        $request->validate([
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $introduction->update($request->only('title', 'content'));

        return redirect()->route('admin.introduction.index')->with('success', 'Introduction updated successfully.');
    }
}