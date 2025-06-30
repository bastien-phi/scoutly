import CommunityLinkCard from '@/components/community-link-card';
import LinkCard from '@/components/link-card';
import { Pill } from '@/components/ui/pill';
import AppLayout from '@/layouts/app-layout';
import { fetchJson } from '@/lib/utils';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { useEffect, useState } from 'react';
import TagStatisticData = App.Data.TagStatisticData;
import CommunityLinkData = App.Data.CommunityLinkData;
import LinkData = App.Data.LinkData;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function Dashboard() {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <div className="grid auto-rows-min gap-4 xl:grid-cols-2">
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative rounded-xl border">
                        <RandomLink />
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative rounded-xl border">
                        <RandomCommunityLink />
                    </div>
                </div>
                <div className="grid auto-rows-min gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <LinkCount title="Your links" url={route('api.dashboard.link-count')} />
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <TrendingTags
                            title="Your favorite tags"
                            url={route('api.dashboard.favorite-tags')}
                            generateLinkUsing={(tag: TagStatisticData) => route('links.index', { tag_uuids: [tag.uuid] })}
                        />
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <LinkCount title="Community links" url={route('api.dashboard.community-link-count')} />
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <TrendingTags
                            title="Community trending tags"
                            url={route('api.dashboard.community-trending-tags')}
                            generateLinkUsing={(tag: TagStatisticData) => route('community-links.index', { tags: [tag.label] })}
                        />
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}

function LinkCount({ title, url }: { title: string; url: string }) {
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [count, setCount] = useState<number | null>(null);

    useEffect(() => {
        fetchJson<number>(url)
            .then((json) => {
                setCount(json.data);
            })
            .catch((err) => {
                console.error('Failed to fetch link count.', err);
            })
            .finally(() => {
                setIsLoading(false);
            });
    }, [url]);

    return (
        <div className="flex size-full flex-col p-4">
            <div className="text-muted-foreground">{title}</div>
            <div className="flex flex-1 items-center justify-center">
                {isLoading ? (
                    <LoaderCircle className="text-muted-foreground h-8 w-8 animate-spin" />
                ) : (
                    <span className="text-3xl font-semibold">{count !== null ? count : '-'}</span>
                )}
            </div>
        </div>
    );
}

function TrendingTags({ title, url, generateLinkUsing }: { title: string; url: string; generateLinkUsing: (tag: TagStatisticData) => string }) {
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [tags, setTags] = useState<TagStatisticData[] | null>(null);

    useEffect(() => {
        fetchJson<TagStatisticData[]>(url)
            .then((json) => {
                setTags(json.data);
            })
            .catch((err) => {
                console.error('Failed to fetch trending tags.', err);
            })
            .finally(() => {
                setIsLoading(false);
            });
    }, [url]);

    return (
        <div className="flex size-full flex-col p-4">
            <div className="text-muted-foreground">{title}</div>
            <div className="flex flex-1 items-center justify-center">
                {isLoading && <LoaderCircle className="text-muted-foreground h-8 w-8 animate-spin" />}
                {!isLoading && (!tags || tags.length === 0) && <span className="text-3xl font-semibold">-</span>}
                {!isLoading && tags && tags.length > 0 && (
                    <div className="flex size-full flex-col justify-evenly">
                        {tags.map((tag) => (
                            <div className="flex justify-between" key={tag.uuid}>
                                <Link href={generateLinkUsing(tag)}>
                                    <Pill>{tag.label}</Pill>
                                </Link>
                                <span className="text-muted-foreground">{tag.links_count}</span>
                            </div>
                        ))}
                    </div>
                )}
            </div>
        </div>
    );
}

function RandomLink() {
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [link, setLink] = useState<LinkData | null>(null);

    useEffect(() => {
        fetchJson<LinkData>(route('api.dashboard.random-link'))
            .then((json) => {
                setLink(json.data);
            })
            .catch((err) => {
                console.error('Failed to fetch random link.', err);
            })
            .finally(() => {
                setIsLoading(false);
            });
    }, []);

    return (
        <div className="flex size-full flex-col p-4">
            <div className="text-muted-foreground">Random link</div>
            <div className="flex min-h-32 flex-1 items-center justify-center">
                {isLoading && <LoaderCircle className="text-muted-foreground h-8 w-8 animate-spin" />}
                {!isLoading && !link && <span className="text-3xl font-semibold">-</span>}
                {!isLoading && link && <LinkCard link={link} className="border-0 shadow-none" />}
            </div>
        </div>
    );
}

function RandomCommunityLink() {
    const [isLoading, setIsLoading] = useState<boolean>(true);
    const [link, setLink] = useState<CommunityLinkData | null>(null);

    useEffect(() => {
        fetchJson<CommunityLinkData>(route('api.dashboard.random-community-link'))
            .then((json) => {
                setLink(json.data);
            })
            .catch((err) => {
                console.error('Failed to fetch random community link.', err);
            })
            .finally(() => {
                setIsLoading(false);
            });
    }, []);

    return (
        <div className="flex size-full flex-col p-4">
            <div className="text-muted-foreground">Random community link</div>
            <div className="flex min-h-32 flex-1 items-center justify-center">
                {isLoading && <LoaderCircle className="text-muted-foreground h-8 w-8 animate-spin" />}
                {!isLoading && !link && <span className="text-3xl font-semibold">-</span>}
                {!isLoading && link && <CommunityLinkCard link={link} className="border-0 shadow-none" />}
            </div>
        </div>
    );
}
