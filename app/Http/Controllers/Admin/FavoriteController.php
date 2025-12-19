<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\Service;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of favorites with optional filters.
     */
    public function index(Request $request)
    {
        $filterType = $request->query('type');
        $search = $request->query('search');

        $validTypes = [
            Favorite::TYPE_BLOG,
            Favorite::TYPE_SERVICE,
            Favorite::TYPE_PRODUCT,
        ];

        $favoritesQuery = Favorite::with('user')->latest();

        if ($filterType && in_array($filterType, $validTypes, true)) {
            $favoritesQuery->where('favorite_type', $filterType);
        }

        if ($search) {
            $favoritesQuery->whereHas('user', function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $favorites = $favoritesQuery->paginate(20)->withQueryString();

        // Build content lookups so we can display the actual title/name
        $contentLookups = [
            Favorite::TYPE_BLOG => Blog::whereIn('id', $favorites->where('favorite_type', Favorite::TYPE_BLOG)->pluck('item_id'))
                ->pluck('title', 'id'),
            Favorite::TYPE_SERVICE => Service::whereIn('id', $favorites->where('favorite_type', Favorite::TYPE_SERVICE)->pluck('item_id'))
                ->pluck('name', 'id'),
            Favorite::TYPE_PRODUCT => Product::whereIn('id', $favorites->where('favorite_type', Favorite::TYPE_PRODUCT)->pluck('item_id'))
                ->pluck('name', 'id'),
        ];

        // Quick stats for cards
        $stats = [
            'total' => Favorite::count(),
            Favorite::TYPE_BLOG => Favorite::where('favorite_type', Favorite::TYPE_BLOG)->count(),
            Favorite::TYPE_SERVICE => Favorite::where('favorite_type', Favorite::TYPE_SERVICE)->count(),
            Favorite::TYPE_PRODUCT => Favorite::where('favorite_type', Favorite::TYPE_PRODUCT)->count(),
        ];

        return view('admin.favorites.index', [
            'favorites' => $favorites,
            'filterType' => $filterType,
            'search' => $search,
            'validTypes' => $validTypes,
            'contentLookups' => $contentLookups,
            'stats' => $stats,
        ]);
    }
}






