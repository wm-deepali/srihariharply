<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AnnouncementBar;
use Illuminate\Http\Request;

class AnnouncementBarController extends Controller
{
    public function index()
    {
        $bars = AnnouncementBar::orderBy('sort_order')->orderByDesc('id')->get();

        return view('admin.announcements.index', compact('bars'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message'    => 'required|string|max:255',
            'link_text'  => 'nullable|string|max:100',
            'link_url'   => 'nullable|string|max:255',
            'bg_color'   => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
        ]);

        AnnouncementBar::create([
            'sort_order'      => AnnouncementBar::max('sort_order') + 1,
            'message'         => $request->message,
            'link_text'       => $request->link_text,
            'link_url'        => $request->link_url,
            'bg_color'        => $request->bg_color ?: '#1F5552',
            'text_color'      => $request->text_color ?: '#FFFFFF',
            'is_active'       => $request->has('is_active') ? 1 : 0,
            'is_dismissible'  => $request->has('is_dismissible') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement bar added successfully.');
    }

    public function update(Request $request, AnnouncementBar $announcement)
    {
        $request->validate([
            'message'    => 'required|string|max:255',
            'link_text'  => 'nullable|string|max:100',
            'link_url'   => 'nullable|string|max:255',
            'bg_color'   => 'nullable|string|max:7',
            'text_color' => 'nullable|string|max:7',
        ]);

        $announcement->update([
            'message'         => $request->message,
            'link_text'       => $request->link_text,
            'link_url'        => $request->link_url,
            'bg_color'        => $request->bg_color ?: '#1F5552',
            'text_color'      => $request->text_color ?: '#FFFFFF',
            'is_active'       => $request->has('is_active') ? 1 : 0,
            'is_dismissible'  => $request->has('is_dismissible') ? 1 : 0,
        ]);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement bar updated successfully.');
    }

    public function destroy(AnnouncementBar $announcement)
    {
        $announcement->delete();

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Announcement bar deleted.');
    }

    public function toggleStatus(AnnouncementBar $announcement)
    {
        $announcement->update(['is_active' => ! $announcement->is_active]);

        return redirect()
            ->route('admin.announcements.index')
            ->with('success', 'Status updated.');
    }
}