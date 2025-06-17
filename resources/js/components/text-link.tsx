import { cn } from '@/lib/utils';
import { Link } from '@inertiajs/react';
import { ComponentProps } from 'react';

type LinkProps = ComponentProps<typeof Link> & {
    variant?: 'default' | 'ghost';
};

export default function TextLink({ className = '', variant = 'default', children, ...props }: LinkProps) {
    return (
        <Link
            className={cn(
                'text-foreground underline underline-offset-4 transition-colors duration-300 ease-out hover:decoration-current!',
                variant === 'default' ? 'decoration-neutral-300 dark:decoration-neutral-500' : '',
                variant === 'ghost' ? 'decoration-transparent' : '',
                className,
            )}
            {...props}
        >
            {children}
        </Link>
    );
}
