import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Datetime } from '@/components/ui/datetime';
import { Pill } from '@/components/ui/pill';
import AppLayout from '@/layouts/app-layout';
import { BreadcrumbItem, Paginated } from '@/types';
import { Head, WhenVisible } from '@inertiajs/react';
import { ArrowUpRight, PencilLine, User } from 'lucide-react';
import { useState } from 'react';
import CommunityLinkData = App.Data.CommunityLinkData;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Community links',
        href: route('community-links.index'),
    },
];

export default function Index({ links }: { links: Paginated<CommunityLinkData> }) {
    const [page, setPage] = useState<number>(links.current_page);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Links" />
            <div className="flex flex-col items-center space-y-4 px-4 py-6">
                <div className="w-full space-y-4 xl:w-1/2">
                    {links.data.map((link: CommunityLinkData) => (
                        <CommunityLinkCard key={link.id} link={link} />
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

function CommunityLinkCard({ link }: { link: CommunityLinkData }) {
    return (
        <Card>
            <CardHeader className="flex-row items-baseline justify-between">
                <CardTitle>{link.title}</CardTitle>
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
                            <Pill key={tag.id}>{tag.label}</Pill>
                        ))}
                    </div>
                )}
            </CardContent>
            <CardFooter className="flex justify-between">
                <div className="space-y-4">
                    {link.author && (
                        <div className="flex gap-x-4">
                            <PencilLine />
                            <span>{link.author.name}</span>
                        </div>
                    )}
                    <div className="flex gap-x-4">
                        <User />
                        <span>{link.user.username}</span>
                    </div>
                </div>
                {link.published_at && (
                    <div className="text-muted-foreground flex items-center gap-2 text-sm">
                        <span>
                            Published : <Datetime datetime={new Date(link.published_at)} />
                        </span>
                    </div>
                )}
            </CardFooter>
        </Card>
    );
}
