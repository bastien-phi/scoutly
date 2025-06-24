import Heading from '@/components/heading';
import LinkForm from '@/components/link-form';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';
import LinkFormData = App.Data.LinkFormData;
import LinkData = App.Data.LinkData;

export default function Edit({ draft, authors, tags }: { draft: LinkData; authors: string[]; tags: string[] }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Drafts',
            href: route('drafts.index'),
        },
        {
            title: draft.title || 'Draft',
            href: route('drafts.edit', draft.id),
        },
        {
            title: 'Edition',
            href: route('drafts.edit', draft.id),
        },
    ];

    const { data, setData, put, processing, errors } = useForm<Required<LinkFormData>>({
        url: draft.url,
        title: draft.title || '',
        description: draft.description || '',
        is_public: draft.is_public || false,
        author: draft.author?.name || '',
        tags: draft.tags.map((tag) => tag.label) || [],
    });

    const submitDraft: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('drafts.update', draft.id));
    };

    const submitLink: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('drafts.publish', draft.id));
    };

    const isValid = data.url && data.title;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Edit draft" />
            <div className="flex flex-col items-center px-4 py-6">
                <div className="w-full space-y-4 xl:w-1/2">
                    <Heading title="Edit draft" />

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
