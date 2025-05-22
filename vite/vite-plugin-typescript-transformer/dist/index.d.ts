import { Plugin } from 'vite';

interface TypescriptTransformerOptions {
    patterns?: string[];
    path?: string;
    output?: string;
    format?: boolean;
    command?: string;
}
declare const typescriptTransformer: ({ patterns, path, output, format, command, }?: TypescriptTransformerOptions) => Plugin;

export { typescriptTransformer };
