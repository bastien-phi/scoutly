import { Button } from '@/components/ui/button';
import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Command, CommandEmpty, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { Datetime } from '@/components/ui/datetime';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { cn, debounce } from '@/lib/utils';
import { Paginated, type BreadcrumbItem } from '@/types';
import { Head, Link, router, useForm, WhenVisible } from '@inertiajs/react';
import { ArrowUpRight, Check, ChevronsUpDown, Filter, Search, User, X } from 'lucide-react';
import { useCallback, useEffect, useRef, useState } from 'react';
import LinkData = App.Data.LinkData;
import SearchLinkFormData = App.Data.SearchLinkFormData;
import AuthorData = App.Data.AuthorData;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Links',
        href: route('links.index'),
    },
];

export default function Index({ links, authors, request }: { links: Paginated<LinkData>; authors: AuthorData[]; request: SearchLinkFormData }) {
    const [page, setPage] = useState<number>(links.current_page);
    const [showFilters, setShowFilters] = useState<boolean>(false);
    const firstRender = useRef(true);

    const { data, setData } = useForm<SearchLinkFormData>(request);

    // eslint-disable-next-line react-hooks/exhaustive-deps
    const debouncedFetchLinks = useCallback(
        debounce(
            (newData: SearchLinkFormData) =>
                router.get(route('links.index'), newData, {
                    only: ['links'],
                    preserveState: true,
                }),
            300,
        ),
        [],
    );

    useEffect(() => {
        if (firstRender.current) {
            firstRender.current = false;
            return;
        }

        debouncedFetchLinks(data);
    }, [debouncedFetchLinks, data]);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Links" />
            <div className="flex flex-col items-center space-y-4 px-4 py-6">
                <div className="w-full space-y-4 xl:w-1/2">
                    <div className="flex gap-2">
                        <div className="relative flex-1">
                            <Search className="absolute top-1/2 left-4 h-5 w-5 -translate-y-1/2 transform text-gray-400" />
                            <Input
                                type="text"
                                value={data.search}
                                onChange={(e) => setData('search', e.target.value === '' ? undefined : e.target.value)}
                                placeholder="Search links..."
                                className="pl-12"
                            />
                        </div>
                        <Button variant="ghost" onClick={() => setShowFilters((prev) => !prev)}>
                            <Filter />
                        </Button>
                    </div>

                    {showFilters && (
                        <div>
                            <div className="grid gap-2">
                                <Label>Author</Label>
                                <AuthorSelect
                                    authors={authors}
                                    value={data.author_id}
                                    onChange={(value: number | undefined) => setData('author_id', value)}
                                />
                            </div>
                        </div>
                    )}

                    <Separator />

                    {links.data.map((link: LinkData) => (
                        <LinkCard key={link.id} link={link} />
                    ))}

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
                <Link href={route('links.show', link.id)}>
                    <CardTitle>{link.title}</CardTitle>
                </Link>
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
            </CardContent>
            <CardFooter className="flex justify-between">
                {link.author && (
                    <div className="flex gap-x-4">
                        <User /> {link.author.name}
                    </div>
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

function AuthorSelect({
    authors,
    value,
    onChange,
}: {
    authors: AuthorData[];
    value: number | undefined;
    onChange: (value: number | undefined) => void;
}) {
    const [open, setOpen] = useState<boolean>(false);

    return (
        <Popover open={open} onOpenChange={setOpen}>
            <div className="flex gap-2">
                <PopoverTrigger asChild>
                    <Button variant="outline" role="combobox" aria-expanded={open} className="w-full justify-between">
                        {value ? authors.find((author) => author.id === value)?.name : 'Any author'}
                        <ChevronsUpDown className="opacity-50" />
                    </Button>
                </PopoverTrigger>
                <Button variant="ghost" onClick={() => onChange(undefined)}>
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
                                        onChange(author.id === value ? undefined : author.id);
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
