<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OurService;
use Illuminate\Http\Request;

class OurServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = OurService::query();

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $sortBy    = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $allowedSorts = ['id', 'title', 'status'];
        if (!in_array($sortBy, $allowedSorts)) {
            $sortBy = 'id';
        }

        $services = $query->orderBy($sortBy, $sortOrder)->paginate(20)->withQueryString();

        return view('admin.our-services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.our-services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        OurService::create([
            'title'   => $request->title,
            'content' => $request->content,
            'status'  => 'active',
        ]);

        return redirect()->route('admin.our-services.index')->with('success', 'Product added successfully.');
    }

    public function edit(OurService $ourService)
    {
        return view('admin.our-services.edit', ['service' => $ourService]);
    }

    public function update(Request $request, OurService $ourService)
    {
        $request->validate([
            'title'   => 'nullable|string|max:255',
            'content' => 'nullable|string',
        ]);

        $ourService->update($request->only('title', 'content'));

        return redirect()->route('admin.our-services.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(OurService $ourService)
    {
        $ourService->delete();

        return response()->json(['message' => 'Product deleted successfully.']);
    }

    public function toggleStatus(OurService $ourService)
    {
        $ourService->status = $ourService->status === 'active' ? 'block' : 'active';
        $ourService->save();

        return response()->json([
            'message' => 'Status updated successfully.',
            'status'  => $ourService->status,
        ]);
    }
}