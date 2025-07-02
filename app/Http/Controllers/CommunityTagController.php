<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityTags;
use App\Data\Requests\SearchRequest;
use App\Data\Resources\JsonResource;
use App\Data\Resources\TagResource;

class CommunityTagController
{
    public function index(SearchRequest $data, GetCommunityTags $getCommunityTags): JsonResource
    {
        return JsonResource::collection(
            $getCommunityTags->execute($data->search),
            TagResource::class
        );
    }
}
