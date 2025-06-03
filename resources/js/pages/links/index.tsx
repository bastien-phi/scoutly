import { Card, CardContent, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Datetime } from '@/components/ui/datetime';
import AppLayout from '@/layouts/app-layout';
import { Paginated, type BreadcrumbItem } from '@/types';
import { Head, Link, WhenVisible } from '@inertiajs/react';
import { ArrowUpRight, Eye, PencilLine } from 'lucide-react';
import { useState } from 'react';
import LinkData = App.Data.LinkData;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Links',
        href: route('links.index'),
    },
];

export default function Index({ links }: { links: Paginated<LinkData> }) {
    const [page, setPage] = useState<number>(links.current_page);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Links" />
            <div className="flex flex-col items-center px-4 py-6">
                <div className="w-full space-y-4 xl:w-1/2">
                    {links.data.map((link: LinkData) => (
                        <LinkCard key={link.id} link={link} />
                    ))}
                </div>
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
        </AppLayout>
    );
}

function LinkCard({ link }: { link: LinkData }) {
    return (
        <Card>
            <CardHeader>
                <CardTitle>{link.title}</CardTitle>
            </CardHeader>
            <CardContent className="space-y-4">
                {link.description && <p>{link.description}</p>}
                {link.author && (
                    <div className="flex gap-x-4">
                        <PencilLine></PencilLine>
                        {link.author.name}
                    </div>
                )}
            </CardContent>
            <CardFooter className="flex justify-between">
                {link.published_at && <Datetime datetime={new Date(link.published_at)} />}
                <div className="flex space-x-4">
                    <Link href={route('links.show', link.id)}>
                        <Eye></Eye>
                    </Link>
                    <a href={link.url} target="_blank">
                        <ArrowUpRight></ArrowUpRight>
                    </a>
                </div>
            </CardFooter>
        </Card>
    );
}
