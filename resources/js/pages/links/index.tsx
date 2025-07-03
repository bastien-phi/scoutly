import LinkCard from '@/components/link-card';
import Autocomplete from '@/components/ui/autocomplete';
import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Pill } from '@/components/ui/pill';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { clearFormData, cn, debounce } from '@/lib/utils';
import { Paginated, type BreadcrumbItem } from '@/types';
import { Head, router, useForm, WhenVisible } from '@inertiajs/react';
import { Check, ChevronsUpDown, Filter, Search, User, X } from 'lucide-react';
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
                                <TagMultiselect tags={tags} selectedTags={data.tag_uuids} onValueAdded={addTag} onValueRemoved={removeTag} />
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

function TagMultiselect({
    tags,
    selectedTags,
    onValueAdded,
    onValueRemoved,
}: {
    tags: TagResource[];
    selectedTags: string[];
    onValueAdded: (tag: string) => void;
    onValueRemoved: (tag: string) => void;
}) {
    const [open, setOpen] = useState<boolean>(false);

    return (
        <Popover open={open} onOpenChange={setOpen}>
            <div className="flex gap-2">
                <PopoverTrigger asChild>
                    <Button variant="outline" role="combobox" aria-expanded={open} className="w-full justify-between">
                        Select tags
                        <ChevronsUpDown className="opacity-50" />
                    </Button>
                </PopoverTrigger>
                <Button variant="ghost" onClick={() => selectedTags.forEach((tag) => onValueRemoved(tag))}>
                    <X />
                </Button>
            </div>
            <PopoverContent className="w-96 p-0" align="start">
                <Command className="w-96">
                    <CommandInput placeholder="Search tag..." className="h-9" />
                    <CommandList>
                        <CommandEmpty>No tag found.</CommandEmpty>
                        <CommandGroup>
                            {tags.map((tag: TagResource) => (
                                <CommandItem
                                    key={tag.uuid}
                                    value={tag.label}
                                    onSelect={() => {
                                        if (selectedTags.includes(tag.uuid)) {
                                            onValueRemoved(tag.uuid);
                                        } else {
                                            onValueAdded(tag.uuid);
                                        }
                                        setOpen(false);
                                    }}
                                >
                                    {tag.label}
                                    <Check className={cn('ml-auto', selectedTags.includes(tag.uuid) ? 'opacity-100' : 'opacity-0')} />
                                </CommandItem>
                            ))}
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}
