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
import { Link } from '@inertiajs/react';
import { Trash } from 'lucide-react';
import LinkData = App.Data.LinkData;

export default function DeleteLinkButton({ link }: { link: LinkData }) {
    return (
        <Dialog>
            <DialogTrigger>
                <Trash className="cursor-pointer"></Trash>
            </DialogTrigger>
            <DialogPortal>
                <DialogOverlay></DialogOverlay>
                <DialogContent>
                    <DialogTitle>Delete link</DialogTitle>
                    <DialogDescription>Are you sure you want to delete this link? This action cannot be undone.</DialogDescription>
                    <div className="flex justify-between">
                        <DialogClose>
                            <Button variant="link" className="cursor-pointer">
                                Cancel
                            </Button>
                        </DialogClose>
                        <Link href={route('links.destroy', link.id)} method="delete">
                            <Button variant="destructive" className="cursor-pointer">
                                Delete
                            </Button>
                        </Link>
                    </div>
                </DialogContent>
            </DialogPortal>
        </Dialog>
    );
}
