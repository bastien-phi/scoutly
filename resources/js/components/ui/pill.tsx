import * as React from 'react';
import { cn } from '@/lib/utils';
import { X } from 'lucide-react';

function Pill({
    className,
    children,
    onClose,
    ...props
}: React.ComponentProps<"span"> & {
    onClose?: () => void
}) {
    return (
        <span
            data-slot="label"
            className={cn(
                "flex items-center text-sm leading-none font-medium bg-secondary rounded-full px-2.5 py-1.5 gap-2 group:pill",
                onClose ? 'hover:bg-secondary/80' : '',
                className
            )}
            {...props}
        >
            {children}
           { onClose && (
                <span onClick={onClose} className="cursor-pointer text-muted-foreground hover:text-foreground">
                    <X size={16} />
                </span>
           )}
        </span>
    )
}

export { Pill }
