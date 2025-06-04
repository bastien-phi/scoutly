import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Suggest } from '@/components/ui/suggest';
import { Textarea } from '@/components/ui/textarea';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { ChangeEvent, FormEventHandler } from 'react';
import LinkFormData = App.Data.LinkFormData;

export default function Create({ authors }: { authors: string[] }) {
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

    const { data, setData, post, processing, errors } = useForm<Required<LinkFormData>>({
        url: '',
        title: '',
        description: '',
        author: '',
    });

    const submitDraft: FormEventHandler = (e) => {
        e.preventDefault();
        console.log('Submitting draft', data);
        // post(route('links.store'), {
        //     onFinish: (e) => console.log(e),
        // });
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
                            <div className="grid gap-2">
                                <Label htmlFor="url">Url</Label>
                                <Input
                                    id="url"
                                    type="text"
                                    required
                                    autoFocus
                                    tabIndex={1}
                                    value={data.url}
                                    onChange={(e) => setData('url', e.target.value)}
                                    disabled={processing}
                                    placeholder="Url"
                                />
                                <InputError message={errors.url} className="mt-2" />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="title">Title</Label>
                                <Input
                                    id="title"
                                    type="text"
                                    tabIndex={2}
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    disabled={processing}
                                    placeholder="Title"
                                />
                                <InputError message={errors.title} className="mt-2" />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="description">Description</Label>
                                <Textarea
                                    id="description"
                                    tabIndex={3}
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    disabled={processing}
                                    placeholder="Description"
                                />
                                <InputError message={errors.description} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="author">Author</Label>
                                <Suggest
                                    id="author"
                                    tabIndex={4}
                                    value={data.author}
                                    onChange={(e: ChangeEvent<HTMLInputElement>) => setData('author', e.target.value)}
                                    onSuggestionSelected={(value: string) => setData('author', value)}
                                    disabled={processing}
                                    placeholder="Author"
                                    suggestions={authors}
                                />
                                <InputError message={errors.author} />
                            </div>

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
