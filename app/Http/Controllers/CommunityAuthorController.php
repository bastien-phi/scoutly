<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityAuthors;
use App\Data\Requests\SearchRequest;
use App\Data\Resources\AuthorResource;
use App\Data\Resources\JsonResource;

class CommunityAuthorController
{
    public function index(SearchRequest $data, GetCommunityAuthors $getCommunityAuthors): JsonResource
    {
        return JsonResource::collection(
            $getCommunityAuthors->execute($data->search),
            AuthorResource::class
        );
    }
}
