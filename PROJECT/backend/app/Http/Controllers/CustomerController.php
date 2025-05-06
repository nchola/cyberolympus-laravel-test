<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Http\Requests\CustomerRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = DB::table('users as u')
            ->select([
                'u.id',
                'u.first_name',
                'u.last_name',
                'u.phone',
                'u.created_at as tanggal_registrasi',
                'c.address'
            ])
            ->leftJoin('customer as c', 'u.id', '=', 'c.id')
            ->where('u.account_role', 'customer');

        // Search
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('u.first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('u.last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('u.phone', 'like', '%' . $request->search . '%')
                  ->orWhere('c.address', 'like', '%' . $request->search . '%');
            });
        }

        // Date range filter
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('u.created_at', [$request->start_date . ' 00:00:00', $request->end_date . ' 23:59:59']);
        }

        $customers = $query->orderBy('u.first_name')
                          ->orderBy('u.last_name')
                          ->paginate(10);
        
        if ($request->ajax()) {
            return view('customer.table', compact('customers'))->render();
        }

        return view('customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(CustomerRequest $request)
    {
        // Create user first
        $userId = DB::table('users')->insertGetId([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'account_role' => 'customer',
            'account_type' => 4,
            'account_status' => '1',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        // Then create customer
        DB::table('customer')->insert([
            'id' => $userId,
            'referral_id' => $userId,
            'address' => $request->address
        ]);

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $customer = DB::table('customer as c')
            ->select([
                'c.id',
                'c.address',
                'u.first_name',
                'u.last_name',
                'u.phone',
                'u.created_at as tanggal_registrasi'
            ])
            ->leftJoin('users as u', 'c.referral_id', '=', 'u.id')
            ->where('c.id', $id)
            ->first();

        if (!$customer) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        // Fallback jika field kosong/null
        $customer->first_name = $customer->first_name ?? '';
        $customer->last_name = $customer->last_name ?? '';
        $customer->phone = $customer->phone ?? '';
        $customer->address = $customer->address ?? '';
        $customer->tanggal_registrasi = $customer->tanggal_registrasi ?? '';

        return response()->json($customer);
    }

    public function update(CustomerRequest $request, $id)
    {
        $customer = DB::table('customer')
            ->where('id', $id)
            ->first();

        if ($customer) {
            // Update user data
            DB::table('users')
                ->where('id', $customer->referral_id)
                ->update([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'phone' => $request->phone,
                    'created_at' => $request->tanggal_registrasi,
                    'updated_at' => now()
                ]);

            // Update customer data
            DB::table('customer')
                ->where('id', $id)
                ->update([
                    'address' => $request->address
                ]);
        }

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        $customer = DB::table('customer')->where('id', $id)->first();

        if (!$customer) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        // Delete user first (cascade)
        DB::table('users')
            ->where('id', $customer->referral_id)
            ->delete();

        // Delete customer
        DB::table('customer')
            ->where('id', $id)
            ->delete();

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $customer = DB::table('customer as c')
            ->select([
                'c.id',
                'c.address',
                'u.first_name',
                'u.last_name',
                'u.phone',
                'u.created_at as tanggal_registrasi'
            ])
            ->leftJoin('users as u', 'c.referral_id', '=', 'u.id')
            ->where('c.id', $id)
            ->first();

        if (!$customer) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        // Fallback jika field kosong/null
        $customer->first_name = $customer->first_name ?? '';
        $customer->last_name = $customer->last_name ?? '';
        $customer->phone = $customer->phone ?? '';
        $customer->address = $customer->address ?? '';
        $customer->tanggal_registrasi = $customer->tanggal_registrasi ?? '';

        return response()->json($customer);
    }

    public function create()
    {
        return view('customer.create');
    }
}
