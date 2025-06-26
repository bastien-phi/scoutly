<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityAuthors;
use App\Data\AuthorData;
use App\Http\Requests\SearchRequest;
use App\Http\Resources\DataCollectionResource;

class CommunityAuthorController
{
    public function index(SearchRequest $request, GetCommunityAuthors $getCommunityAuthors): DataCollectionResource
    {
        return DataCollectionResource::make(
            $getCommunityAuthors->execute($request->validated('search')),
            AuthorData::class
        );
    }
}
