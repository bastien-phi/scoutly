import CommunityLinkCard from '@/components/community-link-card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Pill } from '@/components/ui/pill';
import RemoteAutocomplete from '@/components/ui/remote-autocomplete';
import RemoteMultiAutocomplete from '@/components/ui/remote-multi-autocomplete';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { clearFormData, debounce, fetchJson } from '@/lib/utils';
import { BreadcrumbItem, Paginated } from '@/types';
import { Head, router, useForm, WhenVisible } from '@inertiajs/react';
import { Filter, Search, SquareUser, User, X } from 'lucide-react';
import { ChangeEvent, useCallback, useEffect, useRef, useState } from 'react';
import GetCommunityLinksRequest = App.Data.Requests.GetCommunityLinksRequest;
import AuthorResource = App.Data.Resources.AuthorResource;
import TagResource = App.Data.Resources.TagResource;
import CommunityLinkResource = App.Data.Resources.CommunityLinkResource;
import UserResource = App.Data.Resources.UserResource;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Community links',
        href: route('community-links.index'),
    },
];

export default function Index({
    links,
    request,
    user,
}: {
    links: Paginated<CommunityLinkResource>;
    request: GetCommunityLinksRequest;
    user?: UserResource;
}) {
    const [page, setPage] = useState<number>(links.current_page);
    const [showFilters, setShowFilters] = useState<boolean>(false);
    const firstRender = useRef(true);
    const [search, setSearch] = useState<string>(request.search ?? '');

    const { data, setData } = useForm<Required<GetCommunityLinksRequest>>({
        search: request.search ?? '',
        author: request.author ?? '',
        tags: request.tags ?? [],
        user: request.user ?? '',
    });

    useEffect(() => {
        if (firstRender.current) {
            firstRender.current = false;
            return;
        }

        router.get(route('community-links.index'), clearFormData(data), {
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

    const fetchAuthors = useCallback(
        (search: string): Promise<void | AuthorResource[]> =>
            fetchJson<AuthorResource[]>(route('api.community-authors.index', { search: search }))
                .then((json) => json.data)
                .catch((err) => console.error('Failed to fetch authors.', err)),
        [],
    );

    const selectAuthor = useCallback((author: AuthorResource | null) => setData('author', author?.name ?? ''), [setData]);

    /** Tag search */

    const [selectedUser, setSelectedUser] = useState<string>(user?.username ?? '');

    const fetchTags = useCallback(
        (search: string): Promise<void | TagResource[]> =>
            fetchJson<TagResource[]>(route('api.community-tags.index', { search: search }))
                .then((json) => json.data)
                .catch((err) => console.error('Failed to fetch tags.', err)),
        [],
    );

    const addTag = useCallback(
        (tag: TagResource) => setData((prev) => (prev.tags.includes(tag.label) ? prev : { ...prev, tags: [...prev.tags, tag.label] })),
        [setData],
    );

    const removeTag = useCallback((tag: string) => setData((prev) => ({ ...prev, tags: prev.tags.filter((t) => t !== tag) })), [setData]);

    const removeTags = useCallback(() => setData('tags', []), [setData]);

    /** User search */

    const fetchUsers = useCallback(
        (search: string): Promise<void | UserResource[]> =>
            fetchJson<UserResource[]>(route('api.community-users.index', { search: search }))
                .then((json) => json.data)
                .catch((err) => console.error('Failed to fetch authors.', err)),
        [],
    );

    const selectUser = useCallback(
        (user: UserResource | null) => {
            setSelectedUser(user?.username ?? '');
            setData('user', user?.uuid ?? '');
        },
        [setSelectedUser, setData],
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
                            {data.author && (
                                <Pill onClose={() => selectAuthor(null)}>
                                    <div className="flex items-center space-x-1">
                                        <User height={16} />
                                        <span>{data.author}</span>
                                    </div>
                                </Pill>
                            )}
                            {data.tags.map((tag: string) => (
                                <Pill key={tag} onClose={() => removeTag(tag)}>
                                    {tag}
                                </Pill>
                            ))}
                            {selectedUser && (
                                <Pill onClose={() => selectUser(null)}>
                                    <div className="flex items-center space-x-1">
                                        <SquareUser size={16} />
                                        <span>{selectedUser}</span>
                                    </div>
                                </Pill>
                            )}
                        </div>
                    )}

                    {showFilters && (
                        <div className="space-y-4">
                            <div className="grid gap-2">
                                <Label>Author</Label>
                                <div className="flex items-center gap-2">
                                    <RemoteAutocomplete
                                        value={data.author}
                                        onValueChanged={selectAuthor}
                                        showUsing={(author: AuthorResource) => author.name}
                                        getValueUsing={(author: AuthorResource) => author.name}
                                        fetchOptionsUsing={fetchAuthors}
                                        placeholder="Search authors..."
                                    />
                                    <Button variant="ghost" onClick={() => selectAuthor(null)}>
                                        <X />
                                    </Button>
                                </div>
                            </div>
                            <div className="grid gap-2">
                                <Label>Tags</Label>
                                {data.tags.length > 0 && (
                                    <div className="flex flex-wrap gap-2">
                                        {data.tags.map((tag: string) => (
                                            <Pill key={tag} onClose={() => removeTag(tag)}>
                                                {tag}
                                            </Pill>
                                        ))}
                                    </div>
                                )}
                                <div className="flex items-center gap-2">
                                    <RemoteMultiAutocomplete
                                        selectedValues={data.tags}
                                        onValueAdded={addTag}
                                        showUsing={(tag: TagResource) => tag.label}
                                        getValueUsing={(tag: TagResource) => tag.label}
                                        fetchOptionsUsing={fetchTags}
                                        placeholder="Select tags"
                                    />
                                    <Button variant="ghost" onClick={removeTags}>
                                        <X />
                                    </Button>
                                </div>
                            </div>
                            <div className="grid gap-2">
                                <Label>User</Label>
                                <div className="flex items-center gap-2">
                                    <RemoteAutocomplete
                                        value={selectedUser}
                                        onValueChanged={selectUser}
                                        showUsing={(user: UserResource) => user.username}
                                        getValueUsing={(user: UserResource) => user.uuid}
                                        fetchOptionsUsing={fetchUsers}
                                        placeholder="Search users..."
                                    />
                                    <Button variant="ghost" onClick={() => selectUser(null)}>
                                        <X />
                                    </Button>
                                </div>
                            </div>
                        </div>
                    )}

                    <Separator />

                    {links.data.map((link: CommunityLinkResource) => (
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
