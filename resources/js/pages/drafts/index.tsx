import DeleteLinkButton from '@/components/delete-link-button';
import HeadingSmall from '@/components/heading-small';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import { Datetime } from '@/components/ui/datetime';
import { Pill } from '@/components/ui/pill';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { Separator } from '@/components/ui/separator';
import AppLayout from '@/layouts/app-layout';
import { Paginated, type BreadcrumbItem } from '@/types';
import { Head, Link, router, WhenVisible } from '@inertiajs/react';
import { ArrowUpRight, Globe, Info, Lightbulb, LoaderCircle, MailQuestion, User } from 'lucide-react';
import { useState } from 'react';
import LinkData = App.Data.LinkData;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Drafts',
        href: route('drafts.index'),
    },
];

export default function Index({ drafts, draftEmail }: { drafts: Paginated<LinkData>; draftEmail: string }) {
    const [page, setPage] = useState<number>(drafts.current_page);
    const [loading, setLoading] = useState<boolean>(false);

    const checkInbox = () => {
        setLoading(true);

        router.post(
            route('drafts.check-inbox'),
            {},
            {
                preserveState: true,
                onFinish: () => setLoading(false),
            },
        );
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Drafts" />
            <div className="flex flex-col items-center px-4 py-6">
                <div className="w-full space-y-4 xl:w-1/2">
                    <div className="flex items-center justify-center gap-4">
                        <Button variant="secondary" onClick={checkInbox} disabled={loading}>
                            {!loading && (
                                <>
                                    <MailQuestion />
                                    Check draft inbox
                                </>
                            )}
                            {loading && (
                                <>
                                    <LoaderCircle className="animate-spin" />
                                    Checking draft inbox ...
                                </>
                            )}
                        </Button>
                        <Popover>
                            <PopoverTrigger className="cursor-pointer">
                                <Info className="text-muted-foreground" />
                            </PopoverTrigger>
                            <PopoverContent className="w-150 space-y-4">
                                <HeadingSmall title="How to email drafts ?" />
                                <ul className="list-disc pl-5">
                                    <li>
                                        Email{' '}
                                        <a href={'mailto:' + draftEmail} className="underline">
                                            {draftEmail}
                                        </a>{' '}
                                        from the same email address associated with your Scoutly account.
                                    </li>
                                    <li>Provide the URL you want to save in the main body of your email.</li>
                                    <li>Optionally, use the email subject line to give your draft a custom title.</li>
                                </ul>
                                <div className="flex items-center gap-2">
                                    <Lightbulb />
                                    <p>Check your draft inbox regularly to review and organize your submitted content in Scoutly.</p>
                                </div>
                            </PopoverContent>
                        </Popover>
                    </div>

                    <Separator />

                    {drafts.data.map((link: LinkData) => (
                        <DraftCard key={link.id} link={link} />
                    ))}

                    {drafts.data.length === 0 && <div className="flex justify-center">Nothing to see there !</div>}
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
                <div className="flex items-center justify-between">
                    <TextLink href={route('drafts.edit', link.id)} variant="ghost">
                        <CardTitle>{link.url}</CardTitle>
                    </TextLink>
                    <div className="flex space-x-2">
                        <a href={link.url} target="_blank" className="text-muted-foreground hover:text-foreground">
                            <ArrowUpRight></ArrowUpRight>
                        </a>
                        <DeleteLinkButton link={link} />
                    </div>
                </div>
                {link.title && <CardDescription>{link.title}</CardDescription>}
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
                            <Link href={route('links.index', { tags: [tag.id] })} key={tag.id}>
                                <Pill>{tag.label}</Pill>
                            </Link>
                        ))}
                    </div>
                )}
            </CardContent>
            <CardFooter className="flex justify-between">
                {link.author ? (
                    <div className="flex gap-x-4">
                        <User />
                        <TextLink href={route('links.index', { author_id: link.author.id })} variant="ghost">
                            {link.author.name}
                        </TextLink>
                    </div>
                ) : (
                    <div />
                )}
                <div className="text-muted-foreground flex items-center gap-2 text-sm">
                    {link.is_public && <Globe size={16} />}
                    <span>
                        Created : <Datetime datetime={new Date(link.created_at)} />
                    </span>
                </div>
            </CardFooter>
        </Card>
    );
}
