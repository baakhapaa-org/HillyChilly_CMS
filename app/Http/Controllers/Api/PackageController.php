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

    /** Flutter-compatible quest format (camelCase, flat tasks array) */
    public function quests(Request $request)
    {
        $query = Package::active()->with('tasks');

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        $packages = $query->orderBy('is_featured', 'desc')->orderBy('id')->get();

        return response()->json($packages->map(fn ($p) => $this->toQuestFormat($p))->values());
    }

    private function toQuestFormat(Package $package): array
    {
        return [
            'id'           => (string) $package->id,
            'title'        => $package->title,
            'description'  => strip_tags($package->description ?? ''),
            'category'     => $package->category,
            'durationDays' => $package->duration_days,
            'priceNpr'     => $package->price_npr,
            'priceUsd'     => (float) $package->price_usd,
            'isFree'       => (bool) $package->is_free,
            'pointsReward' => $package->points_reward,
            'imageUrl'     => $package->image_url ?? '',
            'location'     => [
                'lat'   => (float) ($package->location_lat ?? 27.7172),
                'lng'   => (float) ($package->location_lng ?? 85.3240),
                'label' => $package->location_label ?? 'Nepal',
            ],
            'tasks' => $package->tasks->map(function ($t) {
                $config = (array) ($t->config ?? []);

                // Quiz questions may be stored as a JSON string inside the config
                // array (entered via the CMS textarea). Decode it to a proper array
                // so the Flutter client receives the correct structure.
                if ($t->type === 'quiz' && isset($config['questions']) && is_string($config['questions'])) {
                    $decoded = json_decode($config['questions'], true);
                    $config['questions'] = is_array($decoded) ? $decoded : [];
                }

                $base = [
                    'id'     => (string) $t->id,
                    'type'   => $t->type,
                    'title'  => $t->title,
                    'points' => $t->points,
                ];
                return array_merge($base, $config);
            })->values()->toArray(),
        ];
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
            'category'       => 'required|in:trekking,adventure,cultural,wildlife,spiritual,food,cycling,urban',
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
            'category'       => 'sometimes|in:trekking,adventure,cultural,wildlife,spiritual,food,cycling,urban',
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
