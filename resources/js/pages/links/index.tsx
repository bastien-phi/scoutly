import LinkCard from '@/components/link-card';
import Autocomplete from '@/components/ui/autocomplete';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import MultiAutocomplete from '@/components/ui/multi-autocomplete';
import { Pill } from '@/components/ui/pill';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { clearFormData, debounce } from '@/lib/utils';
import { Paginated, type BreadcrumbItem } from '@/types';
import { Head, router, useForm, WhenVisible } from '@inertiajs/react';
import { Filter, Search, User, X } from 'lucide-react';
import { ChangeEvent, useCallback, useEffect, useRef, useState } from 'react';
import GetUserLinksRequest = App.Data.Requests.GetUserLinksRequest;
import LinkResource = App.Data.Resources.LinkResource;
import AuthorResource = App.Data.Resources.AuthorResource;
import TagResource = App.Data.Resources.TagResource;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Links',
        href: route('links.index'),
    },
];

export default function Index({
    links,
    authors,
    tags,
    request,
}: {
    links: Paginated<LinkResource>;
    authors: AuthorResource[];
    tags: TagResource[];
    request: GetUserLinksRequest;
}) {
    const [page, setPage] = useState<number>(links.current_page);
    const [showFilters, setShowFilters] = useState<boolean>(false);
    const firstRender = useRef<boolean>(true);
    const [search, setSearch] = useState<string>(request.search ?? '');

    const { data, setData } = useForm<Required<GetUserLinksRequest>>({
        search: request.search ?? '',
        author_uuid: request.author_uuid ?? '',
        tag_uuids: request.tag_uuids ?? [],
    });

    useEffect(() => {
        if (firstRender.current) {
            firstRender.current = false;
            return;
        }

        router.get(route('links.index'), clearFormData(data), {
            only: ['links'],
            preserveState: true,
        });
    }, [data]);

    /** Text search */

    // eslint-disable-next-line react-hooks/exhaustive-deps
    const debouncedSetSearchData = useCallback(
        debounce((value: string) => setData('search', value), 300),
        [],
    );

    const handleSearchChange = useCallback(
        (e: ChangeEvent<HTMLInputElement>) => {
            setSearch(e.target.value);
            debouncedSetSearchData(e.target.value);
        },
        [debouncedSetSearchData],
    );

    const resetSearch = useCallback(() => {
        setSearch('');
        setData('search', '');
    }, [setData]);

    /** Author search */

    const selectedAuthor = authors.find((author) => author.uuid === data.author_uuid);

    const selectAuthor = useCallback((authorUuid: string) => setData('author_uuid', authorUuid), [setData]);

    /** Tags search */

    const selectedTags = data.tag_uuids
        .map((uuid) => tags.find((tag) => tag.uuid === uuid))
        .filter((tag: TagResource | undefined): tag is TagResource => tag !== undefined);

    const addTag = useCallback(
        (tagUuid: string) => setData((prev) => (prev.tag_uuids.includes(tagUuid) ? prev : { ...prev, tag_uuids: [...prev.tag_uuids, tagUuid] })),
        [setData],
    );

    const removeTag = useCallback(
        (tagUuid: string) => setData((prev) => ({ ...prev, tag_uuids: prev.tag_uuids.filter((t) => t !== tagUuid) })),
        [setData],
    );

    const removeTags = useCallback(() => setData('tag_uuids', []), [setData]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Links" />
            <div className="flex flex-col items-center space-y-4 px-4 py-6">
                <div className="w-full space-y-4 xl:w-1/2">
                    <div className="flex gap-2">
                        <div className="relative flex-1">
                            <Search className="absolute top-1/2 left-4 h-5 w-5 -translate-y-1/2 transform text-gray-400" />
                            <Input type="text" value={search} onChange={handleSearchChange} placeholder="Search links..." className="pr-12 pl-12" />
                            <X className="absolute top-1/2 right-4 size-2 h-4 w-4 -translate-y-1/2 transform cursor-pointer" onClick={resetSearch} />
                        </div>
                        <Button variant="ghost" onClick={() => setShowFilters((prev) => !prev)}>
                            <Filter />
                        </Button>
                    </div>

                    {!showFilters && (
                        <div className="flex flex-wrap gap-2">
                            {selectedAuthor && (
                                <Pill onClose={() => selectAuthor('')}>
                                    <User height={18} /> {selectedAuthor.name}
                                </Pill>
                            )}
                            {selectedTags.map((tag: TagResource) => (
                                <Pill key={tag.uuid} onClose={() => removeTag(tag.uuid)}>
                                    {tag.label}
                                </Pill>
                            ))}
                        </div>
                    )}

                    {showFilters && (
                        <div className="space-y-4">
                            <div className="grid gap-2">
                                <Label>Author</Label>
                                <div className="flex gap-2">
                                    <Autocomplete
                                        value={data.author_uuid}
                                        options={authors}
                                        onValueChanged={selectAuthor}
                                        showUsing={(author: AuthorResource) => author.name}
                                        getValueUsing={(author: AuthorResource) => author.uuid}
                                        placeholder="Any author"
                                    />
                                    <Button variant="ghost" onClick={() => selectAuthor('')}>
                                        <X />
                                    </Button>
                                </div>
                            </div>
                            <div className="grid gap-2">
                                <Label>Tags</Label>
                                {selectedTags.length > 0 && (
                                    <div className="flex flex-wrap gap-2">
                                        {selectedTags.map((tag: TagResource) => (
                                            <Pill key={tag.uuid} onClose={() => removeTag(tag.uuid)}>
                                                {tag.label}
                                            </Pill>
                                        ))}
                                    </div>
                                )}
                                <div className="flex gap-2">
                                    <MultiAutocomplete
                                        selectedValues={data.tag_uuids}
                                        options={tags}
                                        onValueAdded={addTag}
                                        showUsing={(tag: TagResource) => tag.label}
                                        getValueUsing={(tag: TagResource) => tag.uuid}
                                        placeholder="Select tags"
                                    />
                                    <Button variant="ghost" onClick={removeTags}>
                                        <X />
                                    </Button>
                                </div>
                            </div>
                        </div>
                    )}

                    <Separator />

                    {links.data.map((link: LinkResource) => (
                        <LinkCard key={link.uuid} link={link} />
                    ))}

                    {links.data.length === 0 && <div className="flex justify-center">Nothing to see there !</div>}

                    {page < links.last_page && (
                        <WhenVisible
                            data=""
                            always
                            fallback={<div>Loading... </div>}
                            params={{
                                data: { page: page + 1 },
                                onSuccess: () => setPage((page: number) => page + 1),
                                preserveUrl: true,
                                only: ['links'],
                            }}
                        >
                            <p className="text-center text-gray-500">Loading more...</p>
                        </WhenVisible>
                    )}
                </div>
            </div>
        </AppLayout>
    );
}
