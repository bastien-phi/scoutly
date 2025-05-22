import { exec } from "child_process";
import { minimatch } from "minimatch";
import osPath from "path";
import { PluginContext } from "rollup";
import { promisify } from "util";
import { HmrContext, Plugin } from "vite";

const execAsync = promisify(exec);

let context: PluginContext;

interface TypescriptTransformerOptions {
    patterns?: string[];
    path?: string;
    output?: string;
    format?: boolean;
    command?: string;
}

export const typescriptTransformer = ({
    patterns = ["app/**/*.php"],
    path,
    output,
    format,
    command = "php artisan typescript:transform",
}: TypescriptTransformerOptions = {}): Plugin => {
    patterns = patterns.map((pattern) => pattern.replace("\\", "/"));

    const args: string[] = [];

    if (path) {
        args.push(`--path=${path}`);
    }

    if (output) {
        args.push(`--output=${output}`);
    }

    if (format) {
        args.push("--format");
    }

    const runCommand = async () => {
        try {
            await execAsync(`${command} ${args.join(" ")}`);
        } catch (error) {
            context.error("Error transforming to typescript: " + error);
        }

        context.info(`Typescript transformed`);
    };

    return {
        name: "vite-plugin-typescript-transformer",
        enforce: "pre",
        buildStart() {
            context = this;
            return runCommand();
        },
        handleHotUpdate({ file, server }) {
            if (shouldRun(patterns, { file, server })) {
                return runCommand();
            }

            return [];
        },
    };
};

const shouldRun = (
    patterns: string[],
    opts: Pick<HmrContext, "file" | "server">,
): boolean => {
    const file = opts.file.replaceAll("\\", "/");

    return patterns.some((pattern) => {
        pattern = osPath
            .resolve(opts.server.config.root, pattern)
            .replaceAll("\\", "/");

        return minimatch(file, pattern);
    });
};
