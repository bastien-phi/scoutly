import { Page } from '@inertiajs/core';
import { type ClassValue, clsx } from 'clsx';
import { twMerge } from 'tailwind-merge';
import { Config, RouteParams, ValidRouteName } from 'ziggy-js';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function routeMatches<T extends ValidRouteName>(page: Page, name: T, params?: RouteParams<T> | undefined, config?: Config): boolean {
    return page.url.startsWith(route(name, params, false, config));
}

// eslint-disable-next-line @typescript-eslint/no-explicit-any
export function debounce<F extends (...params: any[]) => ReturnType<F>>(fn: F, delay: number): F {
    let timeoutID: NodeJS.Timeout;
    return function (...args: unknown[]) {
        clearTimeout(timeoutID);
        // @ts-expect-error - TypeScript doesn't know `this` type
        timeoutID = setTimeout(() => fn.apply(this, args), delay);
    } as F;
}
