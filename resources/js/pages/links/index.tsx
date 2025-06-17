import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Datetime } from '@/components/ui/datetime';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Pill } from '@/components/ui/pill';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { clearFormData, cn, debounce } from '@/lib/utils';
import { Paginated, type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm, WhenVisible } from '@inertiajs/react';
import { ArrowUpRight, Check, ChevronsUpDown, Filter, Search, User, X } from 'lucide-react';
import { ChangeEvent, useCallback, useEffect, useRef, useState } from 'react';
import LinkData = App.Data.LinkData;
import SearchLinkFormData = App.Data.SearchLinkFormData;
import AuthorData = App.Data.AuthorData;
import TagData = App.Data.TagData;

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
    links: Paginated<LinkData>;
    authors: AuthorData[];
    tags: TagData[];
    request: SearchLinkFormData;
}) {
    const [page, setPage] = useState<number>(links.current_page);
    const [showFilters, setShowFilters] = useState<boolean>(false);
    const firstRender = useRef(true);
    const [search, setSearch] = useState<string>(request.search ?? '');

    const { data, setData } = useForm<Required<SearchLinkFormData>>({
        search: request.search ?? '',
        author_id: request.author_id ?? 0,
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

        router.get(route('links.index'), clearFormData(data), {
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
                                <AuthorSelect authors={authors} value={data.author_id} onChange={(value: number) => setData('author_id', value)} />
                            </div>
                            <div className="grid gap-2">
                                <Label>Tags</Label>
                                {data.tags.length > 0 && (
                                    <div className="flex flex-wrap gap-2">
                                        {data.tags
                                            .map((tagId) => tags.find((tag) => tag.id === tagId))
                                            .filter((tag: TagData | undefined): tag is TagData => tag !== undefined)
                                            .map((tag: TagData) => (
                                                <Pill
                                                    key={tag.id}
                                                    onClose={() => setData((prev) => ({ ...prev, tags: prev.tags.filter((t) => t !== tag.id) }))}
                                                >
                                                    {tag.label}
                                                </Pill>
                                            ))}
                                    </div>
                                )}
                                <TagMultiselect
                                    tags={tags}
                                    selectedTags={data.tags}
                                    onValueAdded={(value: number) =>
                                        setData((prev) => (prev.tags.includes(value) ? prev : { ...prev, tags: [...prev.tags, value] }))
                                    }
                                    onValueRemoved={(value: number) =>
                                        setData((prev) => ({ ...prev, tags: prev.tags.filter((tag) => tag !== value) }))
                                    }
                                />
                            </div>
                        </div>
                    )}

                    <Separator />

                    {links.data.map((link: LinkData) => (
                        <LinkCard key={link.id} link={link} />
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

function LinkCard({ link }: { link: LinkData }) {
    return (
        <Card>
            <CardHeader className="flex-row items-baseline justify-between">
                <TextLink href={route('links.show', link.id)} variant="ghost">
                    <CardTitle>{link.title}</CardTitle>
                </TextLink>
                <a href={link.url} target="_blank">
                    <ArrowUpRight></ArrowUpRight>
                </a>
            </CardHeader>
            <CardContent className="space-y-4">
                {link.description && (
                    <pre className="font-sans whitespace-pre-wrap">
                        {link.description.length > 256 ? link.description.substring(0, 255) + '...' : link.description}
                    </pre>
                )}
                {link.tags.length > 0 && (
                    <div className="flex flex-wrap gap-2">
                        {link.tags.map((tag) => (
                            <Link href={route('links.index', { tags: [tag.id] })} key={tag.id}>
                                <Pill>{tag.label}</Pill>
                            </Link>
                        ))}
                    </div>
                )}
            </CardContent>
            <CardFooter className="flex justify-between">
                {link.author ? (
                    <div className="flex gap-x-4">
                        <User />
                        <TextLink href={route('links.index', { author_id: link.author.id })} variant="ghost">
                            {link.author.name}
                        </TextLink>
                    </div>
                ) : (
                    <div></div>
                )}
                {link.published_at && (
                    <div className="text-muted-foreground text-sm">
                        Published : <Datetime datetime={new Date(link.published_at)} />
                    </div>
                )}
            </CardFooter>
        </Card>
    );
}

function AuthorSelect({ authors, value, onChange }: { authors: AuthorData[]; value: number; onChange: (value: number) => void }) {
    const [open, setOpen] = useState<boolean>(false);

    return (
        <Popover open={open} onOpenChange={setOpen}>
            <div className="flex gap-2">
                <PopoverTrigger asChild>
                    <Button variant="outline" role="combobox" aria-expanded={open} className="w-full justify-between">
                        {value !== 0 ? authors.find((author) => author.id === value)?.name : 'Any author'}
                        <ChevronsUpDown className="opacity-50" />
                    </Button>
                </PopoverTrigger>
                <Button variant="ghost" onClick={() => onChange(0)}>
                    <X />
                </Button>
            </div>
            <PopoverContent className="w-full p-0">
                <Command className="w-full">
                    <CommandInput placeholder="Search author..." className="h-9" />
                    <CommandList>
                        <CommandEmpty>No author found.</CommandEmpty>
                        <CommandGroup>
                            {authors.map((author) => (
                                <CommandItem
                                    key={author.id}
                                    value={author.name}
                                    onSelect={() => {
                                        onChange(author.id === value ? 0 : author.id);
                                        setOpen(false);
                                    }}
                                >
                                    {author.name}
                                    <Check className={cn('ml-auto', value === author.id ? 'opacity-100' : 'opacity-0')} />
                                </CommandItem>
                            ))}
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}

function TagMultiselect({
    tags,
    selectedTags,
    onValueAdded,
    onValueRemoved,
}: {
    tags: TagData[];
    selectedTags: number[];
    onValueAdded: (tag: number) => void;
    onValueRemoved: (tag: number) => void;
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
            <PopoverContent className="w-full p-0">
                <Command className="w-full">
                    <CommandInput placeholder="Search tag..." className="h-9" />
                    <CommandList>
                        <CommandEmpty>No tag found.</CommandEmpty>
                        <CommandGroup>
                            {tags.map((tag: TagData) => (
                                <CommandItem
                                    key={tag.id}
                                    value={tag.label}
                                    onSelect={() => {
                                        if (selectedTags.includes(tag.id)) {
                                            onValueRemoved(tag.id);
                                        } else {
                                            onValueAdded(tag.id);
                                        }
                                        setOpen(false);
                                    }}
                                >
                                    {tag.label}
                                    <Check className={cn('ml-auto', selectedTags.includes(tag.id) ? 'opacity-100' : 'opacity-0')} />
                                </CommandItem>
                            ))}
                        </CommandGroup>
                    </CommandList>
                </Command>
            </PopoverContent>
        </Popover>
    );
}
