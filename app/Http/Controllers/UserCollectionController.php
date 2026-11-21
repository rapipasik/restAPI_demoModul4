<?php

namespace App\Http\Controllers;

use App\Http\Resources\MediaResource;
use App\Models\Media;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserCollectionController extends Controller
{
    public function index(User $user): JsonResponse
    {
        $user->load('media');

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
            ],
            'total_collection' => $user->media->count(),
            'collection' => MediaResource::collection($user->media),
        ]);
    }

    public function store(Request $request, User $user): JsonResponse
    {
        $validated = $request->validate([
            'media_id' => ['required', 'integer', 'exists:media,id'],
        ], [
            'media_id.required' => 'ID media wajib diisi.',
            'media_id.exists' => 'Media dengan ID tersebut tidak ditemukan.',
        ]);

        $mediaId = $validated['media_id'];

        if ($user->media()->where('media_id', $mediaId)->exists()) {
            return response()->json([
                'message' => 'Media tersebut sudah ada di koleksi pengguna.',
            ], 409);
        }

        $user->media()->attach($mediaId);

        return response()->json([
            'message' => "Media berhasil ditambahkan ke koleksi {$user->name}.",
        ], 201);
    }

    public function destroy(User $user, Media $media): JsonResponse
    {
        if (! $user->media()->where('media_id', $media->id)->exists()) {
            return response()->json([
                'message' => 'Media tersebut tidak ditemukan di dalam koleksi pengguna.',
            ], 404);
        }

        $user->media()->detach($media->id);

        return response()->json([
            'message' => "Media '{$media->title}' berhasil dihapus dari koleksi {$user->name}.",
        ], 200);
    }
}
