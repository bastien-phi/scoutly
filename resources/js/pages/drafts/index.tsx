import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Datetime } from '@/components/ui/datetime';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogOverlay,
    DialogPortal,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import AppLayout from '@/layouts/app-layout';
import { Paginated, type BreadcrumbItem } from '@/types';
import { Head, Link, WhenVisible } from '@inertiajs/react';
import { ArrowUpRight, Pen, PencilLine, Trash } from 'lucide-react';
import { useState } from 'react';
import LinkData = App.Data.LinkData;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Drafts',
        href: route('drafts.index'),
    },
];

export default function Index({ drafts }: { drafts: Paginated<LinkData> }) {
    const [page, setPage] = useState<number>(drafts.current_page);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Drafts" />
            <div className="flex flex-col items-center px-4 py-6">
                <div className="w-full space-y-4 xl:w-1/2">
                    {drafts.data.map((link: LinkData) => (
                        <DraftCard key={link.id} link={link} />
                    ))}
                </div>
                {page < drafts.last_page && (
                    <WhenVisible
                        data=""
                        always
                        fallback={<div>Loading... </div>}
                        params={{
                            data: { page: page + 1 },
                            onSuccess: () => setPage((page: number) => page + 1),
                            preserveUrl: true,
                            only: ['drafts'],
                        }}
                    >
                        <p className="text-center text-gray-500">Loading more...</p>
                    </WhenVisible>
                )}
            </div>
        </AppLayout>
    );
}

function DraftCard({ link }: { link: LinkData }) {
    return (
        <Card>
            <CardHeader>
                <CardTitle>{link.url}</CardTitle>
                {link.description && <CardDescription>{link.title}</CardDescription>}
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
                <div className="text-muted-foreground text-sm">
                    Created : <Datetime datetime={new Date(link.created_at)} />
                </div>
                <div className="flex space-x-4">
                    <Link href={route('drafts.edit', link.id)}>
                        <Pen></Pen>
                    </Link>
                    <a href={link.url} target="_blank">
                        <ArrowUpRight></ArrowUpRight>
                    </a>
                    <Dialog>
                        <DialogTrigger>
                            <Trash className="cursor-pointer"></Trash>
                        </DialogTrigger>
                        <DialogPortal>
                            <DialogOverlay></DialogOverlay>
                            <DialogContent>
                                <DialogTitle>Delete link</DialogTitle>
                                <DialogDescription>Are you sure you want to delete this link? This action cannot be undone.</DialogDescription>
                                <div className="flex justify-between">
                                    <DialogClose>
                                        <Button variant="link" className="cursor-pointer">
                                            Cancel
                                        </Button>
                                    </DialogClose>
                                    <Link href={route('links.destroy', link.id)} method="delete">
                                        <Button variant="destructive" className="cursor-pointer">
                                            Delete
                                        </Button>
                                    </Link>
                                </div>
                            </DialogContent>
                        </DialogPortal>
                    </Dialog>
                </div>
            </CardFooter>
        </Card>
    );
}
