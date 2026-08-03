<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index(Request $request)
    {
        $query = Coupon::query();

        if ($request->filled('search')) {
            $query->where('code', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $coupons = $query
            ->latest()
            ->paginate(20)
            ->appends($request->all());

        return view(
            'admin.coupons.index',
            compact('coupons')
        );
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',

            'discount_type' => 'required|in:percentage,fixed',

            'discount_value' => 'required|numeric|min:0',

            'minimum_order_amount' => 'nullable|numeric|min:0',

            'maximum_discount' => 'nullable|numeric|min:0',

            'start_date' => 'required|date',

            'end_date' => 'required|date|after_or_equal:start_date',

            'usage_limit' => 'nullable|integer|min:1',

            'customer_type' => 'required|in:all,new',

            'visibility' => 'required|in:public,private',

            'status' => 'nullable|boolean',
            'minimum_order_quantity' => 'nullable|integer|min:1',
        ]);

        $validated['status'] = $request->status ?? 0;

        Coupon::create($validated);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon created successfully.');
    }

    public function show(Coupon $coupon)
    {
        return view(
            'admin.coupons.show',
            compact('coupon')
        );
    }

    public function edit(Coupon $coupon)
    {
        return view(
            'admin.coupons.edit',
            compact('coupon')
        );
    }

    public function update(Request $request, Coupon $coupon)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code,' . $coupon->id,

            'discount_type' => 'required|in:percentage,fixed',

            'discount_value' => 'required|numeric|min:0',

            'minimum_order_amount' => 'nullable|numeric|min:0',

            'maximum_discount' => 'nullable|numeric|min:0',

            'start_date' => 'required|date',

            'end_date' => 'required|date|after_or_equal:start_date',

            'usage_limit' => 'nullable|integer|min:1',

            'customer_type' => 'required|in:all,new',

            'visibility' => 'required|in:public,private',

            'status' => 'nullable|boolean',
            'minimum_order_quantity' => 'nullable|integer|min:1',
        ]);

        $validated['status'] = $request->status ?? 0;

        $coupon->update($validated);

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', 'Coupon deleted successfully.');
    }

    public function changeStatus(Request $request)
    {
        $coupon = Coupon::findOrFail($request->id);

        $coupon->status = !$coupon->status;

        $coupon->save();

        return response()->json([
            'status' => true,
            'message' => 'Status updated successfully.'
        ]);
    }

    public function share(Request $request, \App\Models\Coupon $coupon)
    {
        $request->validate([
            'mobile' => ['required', 'regex:/^[6-9]\d{9}$/'],
        ]);

        $customer = \App\Models\Customer::where('mobile', $request->mobile)
            ->first();

        \App\Services\Sms\SmsDispatcher::send('coupon', $request->mobile, [
            '{coupon_code}' => $coupon->code,
            '{discount_value}' => $coupon->discount_type === 'percentage'
                ? $coupon->discount_value . '%'
                : '₹' . number_format($coupon->discount_value, 2),
            '{expiry_date}' => \Carbon\Carbon::parse($coupon->end_date)->format('d M Y'),
            '{customer_name}' => $customer?->name ?? 'Valued Customer',
            '{store_url}' => url('/shop'),
            '{shop_url}' => url('/shop'),
            '{brand_name}' => config('app.name'),
        ]);

        if ($customer?->email) {
            \App\Services\Email\EmailDispatcher::send('coupon', $customer->email, [
                '{coupon_code}' => $coupon->code,
                '{discount_value}' => $coupon->discount_type === 'percentage'
                    ? $coupon->discount_value . '%'
                    : '₹' . number_format($coupon->discount_value, 2),
                '{expiry_date}' => \Carbon\Carbon::parse($coupon->end_date)->format('d M Y'),
                '{customer_name}' => $customer->name ?? 'Valued Customer',
                '{store_url}' => url('/shop'),
            ], $customer->name);
        }

        return response()->json([
            'status' => true,
            'message' => 'Coupon SMS sent successfully to ' . $request->mobile,
        ]);
    }

}