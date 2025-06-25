<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Actions\GetCommunityTags;
use App\Data\TagData;
use App\Http\Requests\SearchRequest;
use Spatie\LaravelData\DataCollection;

class CommunityTagController
{
    /**
     * @return DataCollection<int, TagData>
     */
    public function index(SearchRequest $request, GetCommunityTags $getCommunityTags): DataCollection
    {
        return TagData::collect(
            $getCommunityTags->execute($request->validated('search')),
            DataCollection::class
        );
    }
}
