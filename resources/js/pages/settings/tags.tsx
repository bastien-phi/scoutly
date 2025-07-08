import { Head, router, useForm } from '@inertiajs/react';

import HeadingSmall from '@/components/heading-small';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { type BreadcrumbItem } from '@/types';

import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
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
import { Input } from '@/components/ui/input';
import AppLayout from '@/layouts/app-layout';
import SettingsLayout from '@/layouts/settings/layout';
import { Pencil, Save, Trash, X } from 'lucide-react';
import { FormEventHandler, useState } from 'react';
import TagSettingResource = App.Data.Resources.TagSettingResource;
import UpdateTagRequest = App.Data.Requests.UpdateTagRequest;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Tags',
        href: '/settings/tags',
    },
];

export default function Tags({ tags }: { tags: TagSettingResource[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="My tags" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Tags" description="Manage your tags" />
                    {tags.length > 0 ? <TagTable tags={tags} /> : <div>Nothing to see there ...</div>}
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}

function TagTable({ tags }: { tags: TagSettingResource[] }) {
    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Tag</TableHead>
                    <TableHead>Links</TableHead>
                    <TableHead></TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {tags.map((tag) => (
                    <TagRow key={tag.uuid} tag={tag} />
                ))}
            </TableBody>
        </Table>
    );
}

function TagRow({ tag }: { tag: TagSettingResource }) {
    const [isEditing, setIsEditing] = useState<boolean>(false);
    const { data, setData, put, processing, errors, clearErrors } = useForm<Required<UpdateTagRequest>>({
        label: tag.label,
    });

    const submitUpdate: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('settings.tags.update', tag.uuid), {
            onSuccess: () => setIsEditing(false),
        });
    };

    return (
        <TableRow key={tag.uuid}>
            <TableCell className="font-medium">
                {!isEditing ? (
                    tag.label
                ) : (
                    <div>
                        <Input
                            type="text"
                            required
                            value={data.label}
                            onChange={(e) => setData('label', e.target.value)}
                            disabled={processing}
                            placeholder="Name"
                        />
                        <InputError message={errors.label} className="mt-2" />
                    </div>
                )}
            </TableCell>
            <TableCell>{tag.links_count}</TableCell>
            <TableCell>
                {!isEditing ? (
                    <div className="text-muted-foreground flex justify-end gap-2">
                        <Pencil
                            size={16}
                            className="hover:text-foreground cursor-pointer"
                            onClick={() => {
                                setData('label', tag.label);
                                setIsEditing(true);
                            }}
                        />
                        <DeleteTagButton tag={tag} />
                    </div>
                ) : (
                    <div className="text-muted-foreground flex justify-end gap-2">
                        <Save size={16} className="hover:text-foreground cursor-pointer" onClick={submitUpdate} />
                        <X
                            size={16}
                            className="hover:text-foreground cursor-pointer"
                            onClick={() => {
                                clearErrors();
                                setIsEditing(false);
                            }}
                        />
                    </div>
                )}
            </TableCell>
        </TableRow>
    );
}

function DeleteTagButton({ tag }: { tag: TagSettingResource }) {
    return (
        <Dialog>
            <DialogTrigger>
                <Trash size={16} className="hover:text-foreground cursor-pointer"></Trash>
            </DialogTrigger>
            <DialogPortal>
                <DialogOverlay></DialogOverlay>
                <DialogContent>
                    <DialogTitle>Delete tag</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete this tag? This action cannot be undone. Associated links will be kept but disassociated from
                        this tag.
                    </DialogDescription>
                    <div className="flex justify-between">
                        <DialogClose>Cancel</DialogClose>
                        <Button
                            variant="destructive"
                            onClick={() =>
                                router.delete(route('settings.tags.destroy', tag.uuid), {
                                    preserveState: false,
                                })
                            }
                        >
                            Delete
                        </Button>
                    </div>
                </DialogContent>
            </DialogPortal>
        </Dialog>
    );
}
