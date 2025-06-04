import LinkData = App.Data.LinkData;
import Heading from '@/components/heading';
import { Button } from '@/components/ui/button';
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
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Pencil, PencilLine, Trash } from 'lucide-react';

export default function Show({ link }: { link: LinkData }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Links',
            href: route('links.index'),
        },
        {
            title: link.title || 'Draft',
            href: route('links.show', link.id),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={link.title || 'Draft'} />
            <div className="flex flex-col items-center px-4 py-8">
                <div className="grid w-full gap-4 xl:w-1/2">
                    <Heading title={link.title || 'Draft'} />
                    <a
                        href={link.url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-foreground cursor-pointer underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                    >
                        {link.url}
                    </a>
                    {link.description && <pre className="font-sans whitespace-pre-wrap">{link.description}</pre>}
                    {link.author && (
                        <div className="flex gap-x-4">
                            <PencilLine></PencilLine>
                            {link.author.name}
                        </div>
                    )}
                    <div className="flex items-center justify-between">
                        <div className="text-muted-foreground text-sm">
                            {link.published_at ? (
                                <div>
                                    Published : <Datetime datetime={new Date(link.published_at)} />
                                </div>
                            ) : (
                                <div>Draft</div>
                            )}
                            <div>
                                Created : <Datetime datetime={new Date(link.created_at)} />
                            </div>
                            <div>
                                Updated : <Datetime datetime={new Date(link.updated_at)} />
                            </div>
                        </div>
                        <div className="flex gap-x-2">
                            <Link href={route('links.edit', link.id)}>
                                <Pencil></Pencil>
                            </Link>
                            <Dialog>
                                <DialogTrigger>
                                    <Trash className="cursor-pointer"></Trash>
                                </DialogTrigger>
                                <DialogPortal>
                                    <DialogOverlay></DialogOverlay>
                                    <DialogContent>
                                        <DialogTitle>Delete link</DialogTitle>
                                        <DialogDescription>
                                            Are you sure you want to delete this link? This action cannot be undone.
                                        </DialogDescription>
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
                    </div>
                </div>
            </div>
        </AppLayout>
    );
}
