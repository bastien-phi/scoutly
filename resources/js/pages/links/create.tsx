import Heading from '@/components/heading';
import LinkForm from '@/components/link-form';
import { Button } from '@/components/ui/button';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';
import StoreLinkRequest = App.Data.Requests.StoreLinkRequest;
import StoreDraftRequest = App.Data.Requests.StoreDraftRequest;

export default function Create({ authors, tags }: { authors: string[]; tags: string[] }) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Links',
            href: route('links.index'),
        },
        {
            title: 'New link',
            href: route('links.create'),
        },
    ];

    const { data, setData, post, processing, errors } = useForm<Required<StoreLinkRequest & StoreDraftRequest>>({
        url: '',
        title: '',
        description: '',
        is_public: false,
        author: '',
        tags: [],
    });

    const submitDraft: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('drafts.store'));
    };

    const submitLink: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('links.store'));
    };

    const isValid = data.url && data.title;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="New link" />
            <div className="flex flex-col items-center px-4 py-6">
                <div className="w-full space-y-4 xl:w-1/2">
                    <Heading title="New link" />

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
                                        Save link
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
