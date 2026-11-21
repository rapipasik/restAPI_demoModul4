<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'type' => $this->type,
            'genre' => $this->genre,
            'details' => $this->type === 'Anime'
                ? [
                    'episodes' => $this->episodes,
                    'summary' => "{$this->episodes} episode",
                ]
                : [
                    'duration_minutes' => $this->duration_minutes,
                    'summary' => "{$this->duration_minutes} menit",
                ],
            'collected_by' => $this->whenLoaded('users', function () {
                return $this->users->map(fn($user) => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'added_at' => $user->pivot?->created_at?->toDateTimeString(),
                ]);
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
            'updated_at' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
