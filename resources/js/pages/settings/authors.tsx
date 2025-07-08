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
import AuthorSettingResource = App.Data.Resources.AuthorSettingResource;
import UpdateAuthorRequest = App.Data.Requests.UpdateAuthorRequest;

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Authors',
        href: '/settings/authors',
    },
];

export default function Authors({ authors }: { authors: AuthorSettingResource[] }) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="My authors" />

            <SettingsLayout>
                <div className="space-y-6">
                    <HeadingSmall title="Authors" description="Manage your authors" />
                    {authors.length > 0 ? <AuthorTable authors={authors} /> : <div>Nothing to see there ...</div>}
                </div>
            </SettingsLayout>
        </AppLayout>
    );
}

function AuthorTable({ authors }: { authors: AuthorSettingResource[] }) {
    return (
        <Table>
            <TableHeader>
                <TableRow>
                    <TableHead>Author</TableHead>
                    <TableHead>Links</TableHead>
                    <TableHead></TableHead>
                </TableRow>
            </TableHeader>
            <TableBody>
                {authors.map((author) => (
                    <AuthorRow key={author.uuid} author={author} />
                ))}
            </TableBody>
        </Table>
    );
}

function AuthorRow({ author }: { author: AuthorSettingResource }) {
    const [isEditing, setIsEditing] = useState<boolean>(false);
    const { data, setData, put, processing, errors, clearErrors } = useForm<Required<UpdateAuthorRequest>>({
        name: author.name,
    });

    const submitUpdate: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('settings.authors.update', author.uuid), {
            onSuccess: () => setIsEditing(false),
        });
    };

    return (
        <TableRow key={author.uuid}>
            <TableCell className="font-medium">
                {!isEditing ? (
                    author.name
                ) : (
                    <div>
                        <Input
                            type="text"
                            required
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            disabled={processing}
                            placeholder="Name"
                        />
                        <InputError message={errors.name} className="mt-2" />
                    </div>
                )}
            </TableCell>
            <TableCell>{author.links_count}</TableCell>
            <TableCell>
                {!isEditing ? (
                    <div className="text-muted-foreground flex justify-end gap-2">
                        <Pencil
                            size={16}
                            className="hover:text-foreground cursor-pointer"
                            onClick={() => {
                                setData('name', author.name);
                                setIsEditing(true);
                            }}
                        />
                        <DeleteAuthorButton author={author} />
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

function DeleteAuthorButton({ author }: { author: AuthorSettingResource }) {
    return (
        <Dialog>
            <DialogTrigger>
                <Trash size={16} className="hover:text-foreground cursor-pointer"></Trash>
            </DialogTrigger>
            <DialogPortal>
                <DialogOverlay></DialogOverlay>
                <DialogContent>
                    <DialogTitle>Delete author</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete this author? This action cannot be undone. Associated links will be kept but disassociated
                        from this author.
                    </DialogDescription>
                    <div className="flex justify-between">
                        <DialogClose>Cancel</DialogClose>
                        <Button
                            variant="destructive"
                            onClick={() =>
                                router.delete(route('settings.authors.destroy', author.uuid), {
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
