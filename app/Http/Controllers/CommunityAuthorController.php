<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityAuthors;
use App\Data\AuthorData;
use App\Http\Requests\SearchRequest;
use Spatie\LaravelData\DataCollection;

class CommunityAuthorController
{
    /**
     * @return DataCollection<int, \App\Data\AuthorData>
     */
    public function index(SearchRequest $request, GetCommunityAuthors $getCommunityAuthors): DataCollection
    {
        return AuthorData::collect(
            $getCommunityAuthors->execute($request->validated('search')),
            DataCollection::class
        );
    }
}
