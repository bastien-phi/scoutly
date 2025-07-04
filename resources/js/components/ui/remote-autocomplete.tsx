import * as React from "react"

import { KeyboardEventHandler, KeyboardEvent, useState, useEffect, useRef, useCallback } from 'react';
import { Input } from '@/components/ui/input';
import { ChevronsUpDown, LoaderCircle } from 'lucide-react';
import { debounce } from "@/lib/utils";

const SUGGESTION_ITEM_HEIGHT = 40;

export default function RemoteAutocomplete<T>({className, value, fetchOptionsUsing, onValueChanged, showUsing, getValueUsing, resetOnSelected, ...props }: React.ComponentProps<"input"> & {
    fetchOptionsUsing: (value: string) => Promise<void|T[]>
    onValueChanged: (value: string) => void
    showUsing: (value: T) => string
    getValueUsing: (value: T) => string
    resetOnSelected?: boolean
}) {
    const [inputValue, setInputValue] = useState<string>('');
    const [showDropdown, setShowDropdown] = useState<boolean>(false);
    const [hasFocus, setHasFocus] = useState<boolean>(false);
    const [isLoading, setIsLoading] = useState<boolean>(false);
    const [options, setOptions] = useState<T[]>([]);
    const [focusedIndex, setFocusedIndex] = useState<number>(-1);
    const [scrollIndex, setScrollIndex] = useState<number>(-1);
    const dropdownRef = useRef<HTMLDivElement>(null);
    const blurTimeoutRef = useRef<ReturnType<typeof setTimeout> | null>(null);

    useEffect(() => {
        if(hasFocus || !options) {
           return;
        }
        if (value) {
            const option = options.find(opt => getValueUsing(opt) === value);
            if(option) {
                setInputValue(showUsing(option));
            } else if (typeof value === 'string') {
                setInputValue(value);
            } else {
                setInputValue('');
            }
        } else {
            setInputValue('');
        }
    }, [hasFocus, value, options, showUsing, getValueUsing]);

    const fetchOptions = useCallback(
        (search: string) => {
            setIsLoading(true);

            fetchOptionsUsing(search)
                .then(result => {
                    setOptions(result || [])
                })
                .finally(() => setIsLoading(false));
        },
        [fetchOptionsUsing]
    )

    // eslint-disable-next-line react-hooks/exhaustive-deps
    const debouncedFetchOptions = useCallback(
        debounce((search: string) => fetchOptions(search), 300),
        [fetchOptions],
    );

    useEffect(() => {
        if(inputValue === '') {
            debouncedFetchOptions.cancel();
            fetchOptions('');
        } else {
            debouncedFetchOptions(inputValue);
        }
    }, [inputValue, fetchOptions, debouncedFetchOptions]);

    useEffect(() => {
        if(!hasFocus) {
            setShowDropdown(false);
            setFocusedIndex(-1);

            return;
        }

        if(options.length > 1) {
            setShowDropdown(true);
        } else if (options.length === 1 && showUsing(options[0]) !== inputValue) {
            setShowDropdown(true);
        } else {
            setShowDropdown(false);
        }

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
            setFocusedIndex(prev => Math.min(options.length - 1, prev +1));
            setShowDropdown(true);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            setFocusedIndex(prev => Math.max(0, prev -1));
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (focusedIndex >= 0 && options[focusedIndex]) {
                handleOptionSelected(options[focusedIndex]);
            }
        } else if (e.key === 'Escape') {
            setShowDropdown(false);
            setFocusedIndex(-1);
        }
    };

    const handleOptionSelected = (option: T) => {
        setInputValue(resetOnSelected ? '' : showUsing(option));
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
                {isLoading ? <LoaderCircle className="h-4 w-4 animate-spin" /> : <ChevronsUpDown size={16} />}
            </div>

            {showDropdown && (
                <div
                    ref={dropdownRef}
                    className="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                >
                    {options.map((option, index) => (
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

export { RemoteAutocomplete }
