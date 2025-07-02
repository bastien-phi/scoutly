<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityAuthors;
use App\Data\AuthorData;
use App\Data\Requests\SearchRequest;
use App\Http\Resources\DataCollectionResource;

class CommunityAuthorController
{
    public function index(SearchRequest $data, GetCommunityAuthors $getCommunityAuthors): DataCollectionResource
    {
        return DataCollectionResource::make(
            $getCommunityAuthors->execute($data->search),
            AuthorData::class
        );
    }
}
