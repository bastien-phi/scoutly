import * as React from "react"

import { Command, CommandGroup, CommandInput, CommandItem, CommandList } from '@/components/ui/command';
import { KeyboardEventHandler, KeyboardEvent } from 'react';

function Suggest({className, onSuggestionSelected, suggestions, ...props }: React.ComponentProps<"input"> & {
    suggestions: string[]
    onSuggestionSelected: (value: string) => void
    value: string|undefined
}) {
    const [forceOpen, setForceOpen] = React.useState(false);
    const [forceClose, setForceClose] = React.useState(true);

    const handleKeyDown: KeyboardEventHandler<HTMLInputElement> = (event: KeyboardEvent<HTMLInputElement>)=> {
        if(event.key === "Escape" || event.key === "Enter") {
            setForceClose(true);
            setForceOpen(false);
            return;
        }

        setForceClose(false);
        if(event.key === "ArrowDown") {
            setForceOpen(true);
        }
    }

    return (
        <Command value={props.value} onChange={props.onChange}>
            <CommandInput {...props} onKeyDown={handleKeyDown} className={className}></CommandInput>
            { (props.value || forceOpen) && !forceClose && (
                <CommandList>
                    <CommandGroup>
                        {suggestions.map((suggestion) => (
                            <CommandItem key={suggestion} onSelect={() => {
                                onSuggestionSelected(suggestion);
                                setForceClose(true)
                            }}>
                                {suggestion}
                            </CommandItem>
                        ))}
                    </CommandGroup>
                </CommandList>
            )}
        </Command>
    );

    /*

    return (
        <div>
        <input
            ref={inputRef}
            type={type}
            data-slot="input"
            className={cn(
                "border-input file:text-foreground placeholder:text-muted-foreground selection:bg-primary selection:text-primary-foreground flex h-9 w-full min-w-0 rounded-md border bg-transparent px-3 py-1 text-base shadow-xs transition-[color,box-shadow] outline-none file:inline-flex file:h-7 file:border-0 file:bg-transparent file:text-sm file:font-medium disabled:pointer-events-none disabled:cursor-not-allowed disabled:opacity-50 md:text-sm",
                "focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px]",
                "aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive",
                className
            )}
            {...props}
            // onFocus={() => setOpen(true)}
            // onBlur={() => {
            //     setOpen(false)
            // }}
            onKeyDown={(e) => {
                if (e.key === "ArrowDown") {
                    setOpen(true);
                }
                if(e.key === "Escape") {
                    setOpen(false);
                }
            }}
        />
                <Select open={open} onValueChange={(value) => {
                    fillFromSuggestion(value);
                    setOpen(false);

                }}>
                    <div className="w-0 h-0 overflow-hidden">
                    <SelectTrigger className="w-0 h-0">
                        <SelectValue>Select</SelectValue>
                    </SelectTrigger>
                    </div>
                    <SelectContent className="mt-[-22px]">
                        { suggestions.map((suggestion) => (
                            <SelectItem value={suggestion} key={suggestion}>{ suggestion }</SelectItem>
                        ))}
                    </SelectContent>
                </Select>


        </div>
    )

     */
}

export { Suggest }


//     <div onMouseDown={(e) => {
//         fillFromSuggestion('Prout')
//         setOpen(false);
//         e.preventDefault()
//     }}>
//     Suggestions !
// </div>
// )}
