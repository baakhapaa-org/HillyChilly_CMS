<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public function index(Request $request)
    {
        $query = Package::active()->with('tasks');

        if ($request->filled('search')) {
            $q = $request->search;
            $query->where(function ($q2) use ($q) {
                $q2->where('title', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%")
                   ->orWhere('location_label', 'like', "%{$q}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('max_price')) {
            $query->where('price_npr', '<=', (int) $request->max_price);
        }

        if ($request->filled('max_days')) {
            $query->where('duration_days', '<=', (int) $request->max_days);
        }

        $packages = $query->orderBy('id')->paginate(20);

        return response()->json($packages);
    }

    public function show(Package $package)
    {
        return response()->json($package->load('tasks'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'required|string',
            'category'       => 'required|in:trekking,adventure,cultural,wildlife,spiritual',
            'duration_days'  => 'required|integer|min:1',
            'price_npr'      => 'required|integer|min:0',
            'points_reward'  => 'nullable|integer|min:0',
            'image_url'      => 'required|url',
            'location_lat'   => 'required|numeric',
            'location_lng'   => 'required|numeric',
            'location_label' => 'required|string|max:255',
        ]);

        $package = Package::create($data);
        return response()->json($package, 201);
    }

    public function update(Request $request, Package $package)
    {
        $data = $request->validate([
            'title'          => 'sometimes|string|max:255',
            'description'    => 'sometimes|string',
            'category'       => 'sometimes|in:trekking,adventure,cultural,wildlife,spiritual',
            'duration_days'  => 'sometimes|integer|min:1',
            'price_npr'      => 'sometimes|integer|min:0',
            'points_reward'  => 'sometimes|integer|min:0',
            'image_url'      => 'sometimes|url',
            'location_lat'   => 'sometimes|numeric',
            'location_lng'   => 'sometimes|numeric',
            'location_label' => 'sometimes|string|max:255',
            'is_active'      => 'sometimes|boolean',
        ]);

        $package->update($data);
        return response()->json($package);
    }

    public function destroy(Package $package)
    {
        $package->update(['is_active' => false]);
        return response()->json(['message' => 'Package deactivated.']);
    }
}
