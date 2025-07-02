<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityTags;
use App\Data\Requests\SearchRequest;
use App\Data\TagData;
use App\Http\Resources\DataCollectionResource;

class CommunityTagController
{
    public function index(SearchRequest $data, GetCommunityTags $getCommunityTags): DataCollectionResource
    {
        return DataCollectionResource::make(
            $getCommunityTags->execute($data->search),
            TagData::class
        );
    }
}
