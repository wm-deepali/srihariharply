<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnnouncementBar;
use Illuminate\Http\Request;

class AnnouncementBarController extends Controller
{
    public function edit()
    {
        $bar = AnnouncementBar::first();

        return view('admin.announcements.edit', compact('bar'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'message' => 'nullable|string|max:255',
            'link_text' => 'nullable|string|max:100',
            'link_url' => 'nullable|string|max:255',
            'bg_color' => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
        ]);

        $bar = AnnouncementBar::first();

        $data = [
            'message' => $request->message,
            'link_text' => $request->link_text,
            'link_url' => $request->link_url,
            'bg_color' => $request->bg_color ?: '#1F5552',
            'text_color' => $request->text_color ?: '#FFFFFF',
            'is_active' => $request->has('is_active') ? 1 : 0,
            'is_dismissible' => $request->has('is_dismissible') ? 1 : 0,
        ];

        if ($bar) {
            $bar->update($data);
        } else {
            AnnouncementBar::create($data);
        }

        return redirect()
            ->back()
            ->with('success', 'Announcement bar updated successfully.');
    }
}