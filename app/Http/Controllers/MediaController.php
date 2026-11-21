<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMediaRequest;
use App\Http\Resources\MediaResource;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class MediaController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $media = Media::query()
            ->when($request->query('search'), function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($request->query('type'), function ($query, $type) {
                $query->where('type', $type);
            })
            ->paginate(10);

        return MediaResource::collection($media);
    }

    public function store(StoreMediaRequest $request): JsonResponse
    {
        $data = $request->validated();

        if ($data['type'] === 'Anime') {
            $data['duration_minutes'] = null;
        } elseif ($data['type'] === 'Film') {
            $data['episodes'] = null;
        }

        $media = Media::create($data);

        return (new MediaResource($media))
            ->response()
            ->setStatusCode(201);
    }

    public function show(Media $media): MediaResource
    {
        $media->load('users');

        return new MediaResource($media);
    }

    public function update(StoreMediaRequest $request, Media $media): MediaResource
    {
        $data = $request->validated();
        $targetType = $data['type'] ?? $media->type;

        if ($targetType === 'Anime') {
            $data['duration_minutes'] = null;
        } elseif ($targetType === 'Film') {
            $data['episodes'] = null;
        }

        $media->update($data);

        return new MediaResource($media);
    }

    public function destroy(Media $media): JsonResponse
    {
        $media->delete();

        return response()->json([
            'message' => 'Media berhasil dihapus dari FlipPlay.',
        ], 200);
    }
}
