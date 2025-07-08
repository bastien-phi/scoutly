<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityUsers;
use App\Data\Requests\SearchRequest;
use App\Data\Resources\JsonResource;
use App\Data\Resources\UserResource;

class CommunityUserController
{
    public function index(SearchRequest $data, GetCommunityUsers $getCommunityUsers): JsonResource
    {
        return JsonResource::collection(
            $getCommunityUsers->execute($data->search),
            UserResource::class
        );
    }
}
