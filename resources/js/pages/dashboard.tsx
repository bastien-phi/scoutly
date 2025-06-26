import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { fetchJson } from '@/helpers';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { useEffect, useState } from 'react';

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
                <div className="grid auto-rows-min gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <LinkCount title="Your links" url={route('api.dashboard.link-count')} />
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <LinkCount title="Community links" url={route('api.dashboard.community-link-count')} />
                    </div>
                    <div className="border-sidebar-border/70 dark:border-sidebar-border relative aspect-video overflow-hidden rounded-xl border">
                        <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
                    </div>
                </div>
                <div className="border-sidebar-border/70 dark:border-sidebar-border relative min-h-[100vh] flex-1 overflow-hidden rounded-xl border md:min-h-min">
                    <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-900/20 dark:stroke-neutral-100/20" />
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
                setCount(null);
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
