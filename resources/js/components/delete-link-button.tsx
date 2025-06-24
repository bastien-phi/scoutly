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
import { router } from '@inertiajs/react';
import { Trash } from 'lucide-react';
import LinkData = App.Data.LinkData;

export default function DeleteLinkButton({ link }: { link: LinkData }) {
    return (
        <Dialog>
            <DialogTrigger>
                <Trash className="text-muted-foreground hover:text-foreground cursor-pointer"></Trash>
            </DialogTrigger>
            <DialogPortal>
                <DialogOverlay></DialogOverlay>
                <DialogContent>
                    <DialogTitle>Delete link</DialogTitle>
                    <DialogDescription>Are you sure you want to delete this link? This action cannot be undone.</DialogDescription>
                    <div className="flex justify-between">
                        <DialogClose>Cancel</DialogClose>
                        <Button variant="destructive" onClick={() => router.delete(route('links.destroy', link.id))}>
                            Delete
                        </Button>
                    </div>
                </DialogContent>
            </DialogPortal>
        </Dialog>
    );
}
