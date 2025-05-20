import { Tooltip, TooltipContent, TooltipProvider, TooltipTrigger } from '@/components/ui/tooltip';

export function Datetime({ datetime }: {datetime: Date}) {
    return (
        <TooltipProvider>
        <Tooltip>
            <TooltipTrigger>
                <time dateTime={datetime.toISOString()}>
                    {datetime.toLocaleDateString()}
                </time>
            </TooltipTrigger>
            <TooltipContent>
                <time dateTime={datetime.toISOString()}>
                    {datetime.toLocaleDateString()} {datetime.toLocaleTimeString()}
                </time>
            </TooltipContent>
        </Tooltip>
        </TooltipProvider>
    );
}
