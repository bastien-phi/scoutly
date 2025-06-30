import CommunityLinkCard from '@/components/community-link-card';
import { Button } from '@/components/ui/button';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Pill } from '@/components/ui/pill';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { clearFormData, cn, debounce, fetchJson } from '@/lib/utils';
import { BreadcrumbItem, Paginated } from '@/types';
import { Head, router, useForm, WhenVisible } from '@inertiajs/react';
import { Check, ChevronsUpDown, Filter, LoaderCircle, Search, X } from 'lucide-react';
import { ChangeEvent, useCallback, useEffect, useRef, useState } from 'react';
import CommunityLinkData = App.Data.CommunityLinkData;
import SearchCommunityLinkFormData = App.Data.SearchCommunityLinkFormData;
import AuthorData = App.Data.AuthorData;
import TagData = App.Data.TagData;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Community links',
        href: route('community-links.index'),
    },
];

export default function Index({ links, request }: { links: Paginated<CommunityLinkData>; request: SearchCommunityLinkFormData }) {
    const [page, setPage] = useState<number>(links.current_page);
    const [showFilters, setShowFilters] = useState<boolean>(false);
    const firstRender = useRef(true);
    const [search, setSearch] = useState<string>(request.search ?? '');

    const { data, setData } = useForm<Required<SearchCommunityLinkFormData>>({
        search: request.search ?? '',
        author: request.author ?? '',
        tags: request.tags ?? [],
    });

    // eslint-disable-next-line react-hooks/exhaustive-deps
    const debouncedSetSearchData = useCallback(
        debounce((value: string) => setData('search', value), 300),
        [],
    );

    const handleSearchChange = (e: ChangeEvent<HTMLInputElement>) => {
        setSearch(e.target.value);
        debouncedSetSearchData(e.target.value);
    };

    const resetSearch = () => {
        setSearch('');
        setData('search', '');
    };

    useEffect(() => {
        if (firstRender.current) {
            firstRender.current = false;
            return;
        }

        router.get(route('community-links.index'), clearFormData(data), {
            only: ['links'],
            preserveState: true,
        });
    }, [data]);

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

                    {showFilters && (
                        <div className="space-y-4">
                            <div className="grid gap-2">
                                <Label>Author</Label>
                                <AuthorSearch value={data.author} onChange={(value: string) => setData('author', value)} />
                            </div>
                            <div className="grid gap-2">
                                <Label>Tags</Label>
                                {data.tags.length > 0 && (
                                    <div className="flex flex-wrap gap-2">
                                        {data.tags.map((tag: string) => (
                                            <Pill
                                                key={tag}
                                                onClose={() => setData((prev) => ({ ...prev, tags: prev.tags.filter((t) => t !== tag) }))}
                                            >
                                                {tag}
                                            </Pill>
                                        ))}
                                    </div>
                                )}
                                <TagSearch
                                    selectedTags={data.tags}
                                    onValueAdded={(value: string) =>
                                        setData((prev) => (prev.tags.includes(value) ? prev : { ...prev, tags: [...prev.tags, value] }))
                                    }
                                    onValueRemoved={(value: string) =>
                                        setData((prev) => ({ ...prev, tags: prev.tags.filter((tag) => tag !== value) }))
                                    }
                                />
                            </div>
                        </div>
                    )}

                    <Separator />

                    {links.data.map((link: CommunityLinkData) => (
                        <CommunityLinkCard key={link.uuid} link={link} />
                    ))}

                    {links.data.length === 0 && <div className="flex justify-center">Nothing to see there!</div>}

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

function AuthorSearch({ value, onChange }: { value: string; onChange: (value: string) => void }) {
    const [open, setOpen] = useState<boolean>(false);
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [authors, setAuthors] = useState<AuthorData[]>([]);
    const [searchValue, setSearchValue] = useState<string>(value);

    const fetchAuthors = useCallback((search: string) => {
        setIsLoading(true);
        setAuthors([]);

        fetchJson<AuthorData[]>(route('api.community-authors.index', { search: search }))
            .then((json) => {
                setAuthors(json.data);
            })
            .catch((err) => console.error('Failed to fetch authors.', err))
            .finally(() => setIsLoading(false));
    }, []);

    // eslint-disable-next-line react-hooks/exhaustive-deps
    const debouncedFetchAuthors = useCallback(
        debounce((search: string) => fetchAuthors(search), 300),
        [],
    );

    useEffect(() => {
        debouncedFetchAuthors(searchValue);
    }, [searchValue, debouncedFetchAuthors]);

    useEffect(() => {
        fetchAuthors('');
    }, [fetchAuthors]);

    return (
        <Popover open={open} onOpenChange={setOpen}>
            <div className="flex gap-2">
                <PopoverTrigger asChild>
                    <Button variant="outline" role="combobox" aria-expanded={open} className="w-full justify-between">
                        {value || 'Any author'}
                        <ChevronsUpDown className="opacity-50" />
                    </Button>
                </PopoverTrigger>
                <Button variant="ghost" onClick={() => onChange('')}>
                    <X />
                </Button>
            </div>
            <PopoverContent className="w-96 p-0" align="start">
                <Command className="w-96" shouldFilter={false}>
                    <CommandInput placeholder="Search author..." className="h-9" value={searchValue} onValueChange={setSearchValue} />
                    <CommandList>
                        <CommandEmpty>
                            {isLoading ? (
                                <div className="flex justify-center">
                                    <LoaderCircle className="h-4 w-4 animate-spin" />
                                </div>
                            ) : (
                                'No author found.'
                            )}
                        </CommandEmpty>
                        <CommandGroup>
                            {authors.map((author: AuthorData) => (
                                <CommandItem
                                    key={author.uuid}
                                    value={author.name}
                                    onSelect={() => {
                                        onChange(value === author.name ? '' : author.name);
                                        setSearchValue('');
                                        setOpen(false);
                                    }}
                                >
                                    {author.name}
                                    <Check className={cn('ml-auto', value === author.name ? 'opacity-100' : 'opacity-0')} />
                                </CommandItem>
                            ))}
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}

function TagSearch({
    selectedTags,
    onValueAdded,
    onValueRemoved,
}: {
    selectedTags: string[];
    onValueAdded: (tag: string) => void;
    onValueRemoved: (tag: string) => void;
}) {
    const [open, setOpen] = useState<boolean>(false);
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [tags, setTags] = useState<TagData[]>([]);
    const [searchValue, setSearchValue] = useState<string>('');

    const fetchTags = useCallback((search: string) => {
        setIsLoading(true);
        setTags([]);

        fetchJson<TagData[]>(route('api.community-tags.index', { search: search }))
            .then((json) => {
                setTags(json.data);
            })
            .catch((err) => console.error('Failed to fetch tags.', err))
            .finally(() => setIsLoading(false));
    }, []);

    // eslint-disable-next-line react-hooks/exhaustive-deps
    const debouncedFetchTags = useCallback(
        debounce((search: string) => fetchTags(search), 300),
        [],
    );

    useEffect(() => {
        debouncedFetchTags(searchValue);
    }, [searchValue, debouncedFetchTags]);

    useEffect(() => {
        fetchTags('');
    }, [fetchTags]);

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
                <Command className="w-96" shouldFilter={false}>
                    <CommandInput placeholder="Search tag..." className="h-9" value={searchValue} onValueChange={setSearchValue} />
                    <CommandList>
                        <CommandEmpty>
                            {isLoading ? (
                                <div className="flex justify-center">
                                    <LoaderCircle className="h-4 w-4 animate-spin" />
                                </div>
                            ) : (
                                'No tag found.'
                            )}
                        </CommandEmpty>
                        <CommandGroup>
                            {tags.map((tag: TagData) => (
                                <CommandItem
                                    key={tag.uuid}
                                    value={tag.label}
                                    onSelect={() => {
                                        if (selectedTags.includes(tag.label)) {
                                            onValueRemoved(tag.label);
                                        } else {
                                            onValueAdded(tag.label);
                                        }
                                        setSearchValue('');
                                        setOpen(false);
                                    }}
                                >
                                    {tag.label}
                                    <Check className={cn('ml-auto', selectedTags.includes(tag.label) ? 'opacity-100' : 'opacity-0')} />
                                </CommandItem>
                            ))}
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}
