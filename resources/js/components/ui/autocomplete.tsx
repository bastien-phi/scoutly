import * as React from "react"

import { KeyboardEventHandler, KeyboardEvent, useState, useEffect, useRef } from 'react';
import { Input } from '@/components/ui/input';
import { ChevronsUpDown } from 'lucide-react';

const SUGGESTION_ITEM_HEIGHT = 40;

export default function Autocomplete<T>({className, value, options, onValueChanged, showUsing, getValueUsing, ...props }: React.ComponentProps<"input"> & {
    options: T[]
    onValueChanged: (value: string) => void
    showUsing: (value: T) => string
    getValueUsing: (value: T) => string
}) {
    const [inputValue, setInputValue] = useState<string>('');
    const [showDropdown, setShowDropdown] = useState<boolean>(false);
    const [hasFocus, setHasFocus] = useState<boolean>(false);
    const [focusedIndex, setFocusedIndex] = useState<number>(-1);
    const [scrollIndex, setScrollIndex] = useState<number>(-1);
    const [filteredOptions, setFilteredOptions] = useState<T[]>(options);
    const dropdownRef = useRef<HTMLDivElement>(null);
    const blurTimeoutRef = useRef<ReturnType<typeof setTimeout> | null>(null);

    useEffect(() => {
        if(hasFocus) {
           return;
        }
        if (value) {
            const option = options.find(opt => getValueUsing(opt) === value);
            setInputValue(option ? showUsing(option) : '');
        } else {
            setInputValue('');
        }
    }, [hasFocus, value, options, showUsing, getValueUsing]);

    useEffect(() => {
        if(!hasFocus) {
            setShowDropdown(false);
            setFocusedIndex(-1);

            return;
        }

        const filtered = inputValue
            ? options.filter(option =>
                showUsing(option).toLowerCase().includes(inputValue.toLowerCase())
            )
            : options;

        if(filtered.length > 1) {
            setShowDropdown(true);
        } else if (filtered.length === 1 && showUsing(filtered[0]) !== inputValue) {
            setShowDropdown(true);
        } else {
            setShowDropdown(false);
        }

        setFilteredOptions(filtered)
        setFocusedIndex(-1)
    }, [hasFocus, inputValue, options, showUsing]);

    useEffect(() => {
        setScrollIndex((prev) => {
            if(prev < focusedIndex - 5) {
                return focusedIndex - 5;
            }
            if(prev > focusedIndex) {
                return focusedIndex;
            }
            return prev;
        })
    }, [focusedIndex]);

    useEffect(() => {
        if(dropdownRef.current) {
            dropdownRef.current.scroll({
                top: scrollIndex * SUGGESTION_ITEM_HEIGHT,
            })
        }
    }, [scrollIndex]);

    const handleKeyDown: KeyboardEventHandler<HTMLInputElement> = (e: KeyboardEvent<HTMLInputElement>) => {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            setFocusedIndex(prev => Math.min(filteredOptions.length - 1, prev +1));
            setShowDropdown(true);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            setFocusedIndex(prev => Math.max(0, prev -1));
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (focusedIndex >= 0 && filteredOptions[focusedIndex]) {
                handleOptionSelected(filteredOptions[focusedIndex]);
            }
        } else if (e.key === 'Escape') {
            setShowDropdown(false);
            setFocusedIndex(-1);
        }
    };

    const handleOptionSelected = (option: T) => {
        setInputValue(showUsing(option));
        onValueChanged(getValueUsing(option));
        setShowDropdown(false);
        setFocusedIndex(-1);
    };

    return (
        <div className="relative w-full">
            <Input
                {...props}
                className={className}
                value={inputValue}
                onChange={(e) => setInputValue(e.target.value)}
                onKeyDown={handleKeyDown}
                onFocus={() => {
                    setInputValue('')
                    setHasFocus(true)
                }}
                onBlur={() => {
                    if (blurTimeoutRef.current) {
                        clearTimeout(blurTimeoutRef.current);
                    }

                    blurTimeoutRef.current = setTimeout(() => setHasFocus(false), 200)
                }}
            />
            <div className="absolute inset-y-0 right-0 flex items-center pr-2 text-muted-foreground pointer-events-none">
                <ChevronsUpDown size={16} />
            </div>

            {showDropdown && (
                <div
                    ref={dropdownRef}
                    className="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                >
                    {filteredOptions.map((option, index) => (
                        <button
                            key={getValueUsing(option)}
                            onClick={() => handleOptionSelected(option)}
                            className={`w-full text-left px-3 py-2 hover:bg-gray-50 ${
                                index === focusedIndex ? 'bg-primary/5 text-primary' : 'text-gray-700'
                            }`}
                        >
                            {showUsing(option)}
                        </button>
                    ))}
                </div>
            )}
        </div>
    );
}

export { Autocomplete }
