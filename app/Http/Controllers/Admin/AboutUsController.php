<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AboutUsController extends Controller
{
    public function index()
    {
        $aboutUs = AboutUs::firstOrCreate(['id' => 1], ['status' => 'active']);

        return view('admin.about-us.index', compact('aboutUs'));
    }

    public function update(Request $request, AboutUs $aboutUs)
    {
        $request->validate([
            'title'   => 'nullable|string|max:255',
            'image'   => 'nullable|image|max:2048',
            'content' => 'nullable|string',
        ]);

        $data = $request->only('title', 'content');

        if ($request->hasFile('image')) {
            if ($aboutUs->image) {
                Storage::disk('public')->delete($aboutUs->image);
            }
            $data['image'] = $request->file('image')->store('about-us', 'public');
        }

        $aboutUs->update($data);

        return redirect()->route('admin.about-us.index')->with('success', 'About Us updated successfully.');
    }
}