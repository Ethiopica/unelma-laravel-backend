<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use App\Models\Blog;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\Response;

class FavoriteController extends Controller
{
    /**
        * Map favorite types to their corresponding Eloquent models.
        *
        * @var array<string, class-string<\Illuminate\Database\Eloquent\Model>>
        */
    private array $typeModelMap = [
        Favorite::TYPE_BLOG => Blog::class,
        Favorite::TYPE_SERVICE => Service::class,
        Favorite::TYPE_PRODUCT => Product::class,
    ];

    /**
     * Display a listing of the authenticated user's favorites.
     */
    public function index(Request $request)
    {
        $favorites = Favorite::with('user')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'data' => $favorites,
        ]);
    }

    /**
     * Store a new favorite.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'favorite_type' => ['required', Rule::in(array_keys($this->typeModelMap))],
            'item_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $modelClass = $this->typeModelMap[$data['favorite_type']];
        $content = $modelClass::findOrFail($data['item_id']);

        if ($user->favorites()
            ->where('favorite_type', $data['favorite_type'])
            ->where('item_id', $data['item_id'])
            ->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Already in favorites.',
            ], Response::HTTP_CONFLICT);
        }

        $favorite = null;
        $updatedCount = $content->favorite_count;

        DB::transaction(function () use (&$favorite, &$updatedCount, $user, $content, $data) {
            $favorite = Favorite::create([
                'user_id' => $user->id,
                'favorite_type' => $data['favorite_type'],
                'item_id' => $data['item_id'],
            ]);

            $content->increment('favorite_count');
            $content->refresh();
            $updatedCount = $content->favorite_count;
        });

        return response()->json([
            'success' => true,
            'message' => 'Added to favorites.',
            'data' => $favorite,
            'favorite_count' => $updatedCount,
        ], Response::HTTP_CREATED);
    }

    /**
     * Remove a favorite.
     */
    public function destroy(Request $request)
    {
        $data = $request->validate([
            'favorite_type' => ['required', Rule::in(array_keys($this->typeModelMap))],
            'item_id' => ['required', 'integer'],
        ]);

        $user = $request->user();
        $modelClass = $this->typeModelMap[$data['favorite_type']];
        $content = $modelClass::findOrFail($data['item_id']);

        $favorite = $user->favorites()
            ->where('favorite_type', $data['favorite_type'])
            ->where('item_id', $data['item_id'])
            ->first();

        if (! $favorite) {
            return response()->json([
                'success' => false,
                'message' => 'Favorite not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        $updatedCount = $content->favorite_count;

        DB::transaction(function () use (&$updatedCount, $favorite, $content) {
            $favorite->delete();

            $content->decrement('favorite_count');
            $content->refresh();
            $updatedCount = max($content->favorite_count, 0);
        });

        return response()->json([
            'success' => true,
            'message' => 'Removed from favorites.',
            'favorite_count' => $updatedCount,
        ]);
    }
}





