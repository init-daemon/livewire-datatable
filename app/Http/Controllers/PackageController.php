<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index()
    {
        $data = User::select([
            'id as user_id',
            'name',
            'email',
            'phone',
            'status',
            'address',
            'city',
            'country',
            'postal_code',
            'notes',
            'created_at',
            'updated_at',
            'email_verified_at'
        ])
            ->get()
            ->map(function ($user) {
                return [
                    'user_id' => $user->user_id,
                    'name' => $user->name,
                    'phone' => $user->phone,
                    'description' => $user->email,
                    'address' => $user->address,
                    'city' => $user->city,
                    'country' => $user->country,
                    'postal_code' => $user->postal_code,
                    'status' => $user->status,
                    'balance' => rand(-300, 500),
                    'deposit' => rand(600, 1000),
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'email_verified_at' => $user->email_verified_at
                ];
            })
            ->toArray();

        return view('package.index', compact('data'));
    }
}
