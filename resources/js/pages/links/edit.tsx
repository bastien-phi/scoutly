import DeleteLinkButton from '@/components/delete-link-button';
import LinkForm from '@/components/link-form';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';
import LinkData = App.Data.LinkData;
import TagData = App.Data.TagData;
import StoreLinkRequest = App.Data.Requests.StoreLinkRequest;

export default function Edit({ link, authors, tags }: { link: LinkData; authors: string[]; tags: string[] }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Links',
            href: route('links.index'),
        },
        {
            title: link.title || 'Draft',
            href: route('links.show', link.uuid),
        },
        {
            title: 'Edition',
            href: route('links.edit', link.uuid),
        },
    ];

    const { data, setData, put, processing, errors } = useForm<Required<StoreLinkRequest>>({
        url: link.url,
        title: link.title || '',
        description: link.description || '',
        is_public: link.is_public || false,
        author: link.author?.name || '',
        tags: link.tags.map((tag: TagData) => tag.label) || [],
    });

    const submitLink: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('links.update', link.uuid));
    };

    const isValid = data.url && data.title;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit link" />
            <div className="flex flex-col items-center px-4 py-6">
                <div className="w-full space-y-4 xl:w-1/2">
                    <div className="flex items-baseline justify-between">
                        <h2 className="text-xl font-semibold tracking-tight">Edit link</h2>
                        <DeleteLinkButton link={link} />
                    </div>

                    <form className="flex flex-col gap-6" onSubmit={submitLink}>
                        <div className="grid gap-6">
                            <LinkForm data={data} setData={setData} processing={processing} errors={errors} authors={authors} tags={tags} />

                            <Button className="w-full" tabIndex={6} disabled={processing || !isValid} onClick={submitLink}>
                                {processing && (
                                    <>
                                        <LoaderCircle className="h-4 w-4 animate-spin" /> Saving...
                                    </>
                                )}
                                {!processing && <>Save Link</>}
                            </Button>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
