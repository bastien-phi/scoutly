<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityTags;
use App\Data\TagData;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\DataCollectionResource;

class CommunityTagController
{
    public function index(SearchRequest $request, GetCommunityTags $getCommunityTags): DataCollectionResource
    {
        return DataCollectionResource::make(
            $getCommunityTags->execute($request->validated('search')),
            TagData::class
        );
    }
}
