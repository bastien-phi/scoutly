import LinkFormData = App.Data.LinkFormData;
import DraftFormData = App.Data.DraftFormData;
import InputError from '@/components/input-error';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { MultiSuggest } from '@/components/ui/multi-suggest';
import { Suggest } from '@/components/ui/suggest';
import { Textarea } from '@/components/ui/textarea';
import { InertiaFormProps } from '@inertiajs/react';
import { ChangeEvent } from 'react';

export default function LinkForm({
    data,
    setData,
    processing,
    errors,
    authors,
    tags,
}: Pick<InertiaFormProps<Required<LinkFormData & DraftFormData>>, 'data' | 'setData' | 'processing' | 'errors'> & {
    authors: string[];
    tags: string[];
}) {
    return (
        <>
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
                <Label htmlFor="tags">Tags</Label>
                <MultiSuggest
                    id="tags"
                    tabIndex={5}
                    suggestions={tags}
                    selectedSuggestions={data.tags}
                    onValueAdded={(value: string) => setData((prev) => (prev.tags.includes(value) ? prev : { ...prev, tags: [...prev.tags, value] }))}
                    onValueRemoved={(value: string) => setData((prev) => ({ ...prev, tags: prev.tags.filter((tag) => tag !== value) }))}
                    disabled={processing}
                    placeholder="Tag"
                />
                <InputError message={errors.tags} />
            </div>
        </>
    );
}
