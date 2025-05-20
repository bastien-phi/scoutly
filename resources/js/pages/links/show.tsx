import LinkData = App.Data.LinkData;
import { Datetime } from '@/components/ui/datetime';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import { PencilLine } from 'lucide-react';

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
            <div className="flex flex-col items-center py-8">
                <div className="w-full space-y-4 xl:w-1/2">
                    <h1 className="text-2xl font-bold">{link.title || 'Draft'}</h1>
                    <a
                        href={link.url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-foreground cursor-pointer underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                    >
                        {link.url}
                    </a>
                    {link.description && <div className="mt-4">{link.description}</div>}
                    {link.author && (
                        <div className="flex gap-x-4">
                            <PencilLine></PencilLine>
                            {link.author.name}
                        </div>
                    )}
                    <div>
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
                </div>
            </div>
        </AppLayout>
    );
}
