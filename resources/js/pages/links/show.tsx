import LinkData = App.Data.LinkData;
import DeleteLinkButton from '@/components/delete-link-button';
import TextLink from '@/components/text-link';
import { Datetime } from '@/components/ui/datetime';
import { Pill } from '@/components/ui/pill';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/react';
import { Globe, Pencil, User } from 'lucide-react';

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
                <div className="grid w-full gap-8 xl:w-1/2">
                    <div className="flex items-baseline justify-between">
                        <h2 className="text-xl font-semibold tracking-tight">{link.title || 'Draft'} </h2>
                        <div className="flex space-x-2">
                            <Link href={route('links.edit', link.id)}>
                                <Pencil />
                            </Link>
                            <DeleteLinkButton link={link} />
                        </div>
                    </div>
                    <a
                        href={link.url}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="text-foreground cursor-pointer underline decoration-neutral-300 underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current! dark:decoration-neutral-500"
                    >
                        {link.url}
                    </a>
                    {link.description && <pre className="font-sans whitespace-pre-wrap">{link.description}</pre>}

                    {link.tags.length > 0 && (
                        <div className="flex flex-wrap gap-2">
                            {link.tags.map((tag) => (
                                <Link href={route('links.index', { tags: [tag.id] })} key={tag.id}>
                                    <Pill>{tag.label}</Pill>
                                </Link>
                            ))}
                        </div>
                    )}

                    <div className="flex items-center justify-between">
                        <div className="space-y-4">
                            {link.author && (
                                <div className="flex gap-x-4">
                                    <User />
                                    <TextLink href={route('links.index', { author_id: link.author.id })} variant="ghost">
                                        {link.author.name}
                                    </TextLink>
                                </div>
                            )}
                            {link.is_public && (
                                <div className="flex gap-x-4">
                                    <Globe />
                                    Shared publicly
                                </div>
                            )}
                        </div>
                        <div className="text-muted-foreground text-right text-sm">
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
            </div>
        </AppLayout>
    );
}
