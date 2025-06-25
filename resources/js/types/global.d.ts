import type { route as routeFn } from 'ziggy-js';

declare global {
    const route: typeof routeFn;
}

declare module 'ziggy-js' {
    interface TypeConfig {
        strictRouteNames: true;
    }
}
