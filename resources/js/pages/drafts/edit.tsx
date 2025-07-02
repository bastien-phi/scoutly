import DeleteLinkButton from '@/components/delete-link-button';
import LinkForm from '@/components/link-form';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';
import LinkData = App.Data.LinkData;
import StoreLinkRequest = App.Data.Requests.StoreLinkRequest;

export default function Edit({ draft, authors, tags }: { draft: LinkData; authors: string[]; tags: string[] }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Drafts',
            href: route('drafts.index'),
        },
        {
            title: draft.title || 'Draft',
            href: route('drafts.edit', draft.uuid),
        },
        {
            title: 'Edition',
            href: route('drafts.edit', draft.uuid),
        },
    ];

    const { data, setData, put, processing, errors } = useForm<Required<StoreLinkRequest>>({
        url: draft.url,
        title: draft.title || '',
        description: draft.description || '',
        is_public: draft.is_public || false,
        author: draft.author?.name || '',
        tags: draft.tags.map((tag) => tag.label) || [],
    });

    const submitDraft: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('drafts.update', draft.uuid));
    };

    const submitLink: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('drafts.publish', draft.uuid));
    };

    const isValid = data.url && data.title;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit draft" />
            <div className="flex flex-col items-center px-4 py-6">
                <div className="w-full space-y-4 xl:w-1/2">
                    <div className="flex items-baseline justify-between">
                        <h2 className="text-xl font-semibold tracking-tight">Edit draft</h2>
                        <DeleteLinkButton link={draft} />
                    </div>

                    <form className="flex flex-col gap-6" onSubmit={submitLink}>
                        <div className="grid gap-6">
                            <LinkForm data={data} setData={setData} processing={processing} errors={errors} authors={authors} tags={tags} />

                            <div className="grid gap-2">
                                <Button className="w-full" tabIndex={6} disabled={processing} variant="secondary" name="draft" onClick={submitDraft}>
                                    {processing && (
                                        <>
                                            <LoaderCircle className="h-4 w-4 animate-spin" /> Saving...
                                        </>
                                    )}
                                    {!processing && <>Save draft</>}
                                </Button>

                                {!processing && (
                                    <Button className="w-full" tabIndex={7} name="link" onClick={submitLink} disabled={!isValid}>
                                        Save and publish link
                                    </Button>
                                )}
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </AppLayout>
    );
}
