<?php

declare(strict_types=1);

use App\Actions\IngestDraftMessage;
use App\Actions\StoreDraft;
use App\Data\DraftFormData;
use App\Models\Link;
use App\Models\User;
use DirectoryTree\ImapEngine\Message;
use Illuminate\Support\Collection;

it('ingests a draft message', function (): void {
    $user = User::factory()->createOne();

    $message = mock(Message::class);
    $message->shouldReceive('text')
        ->andReturn('I found a great app ! Check https://github.com/bastien-phi/scoutly !')
        ->once();
    $message->shouldReceive('subject')
        ->andReturn('Test draft')
        ->once();
    $message->shouldReceive('markSeen')
        ->once();
    $message->shouldReceive('delete')
        ->with(true)
        ->once();

    $this->mockAction(StoreDraft::class)
        ->with(
            $user,
            new DraftFormData(
                url: 'https://github.com/bastien-phi/scoutly',
                title: 'Test draft',
                description: null,
                is_public: false,
                author: null,
                tags: new Collection,
            )
        )
        ->returns(fn () => Link::factory()->createOne())
        ->in($createdLink);

    $link = app(IngestDraftMessage::class)->execute($user, $message);

    expect($link)->toBeModel($createdLink);
});

it('does not ingest incomplete messages', function (?string $body): void {
    $user = User::factory()->createOne();

    $message = mock(Message::class);
    $message->shouldReceive('text')
        ->andReturn($body)
        ->once();
    $message->shouldReceive('subject')
        ->andReturn('Test draft')
        ->once();
    $message->shouldReceive('markSeen')
        ->once();
    $message->shouldReceive('delete')
        ->with(true)
        ->once();

    $this->mockAction(StoreDraft::class)
        ->neverCalled();

    $link = app(IngestDraftMessage::class)->execute($user, $message);

    expect($link)->toBeNull();
})->with([
    'no url' => 'I found a great app but I cannot remember where...',
    'empty body' => null,
]);
