import * as React from "react"

import { KeyboardEventHandler, KeyboardEvent, useState, useEffect, useRef } from 'react';
import { Input } from '@/components/ui/input';

function Suggest({className, suggestions, onSuggestionSelected, ...props }: React.ComponentProps<"input"> & {
    suggestions: string[]
    onSuggestionSelected: (value: string) => void
    value: string|undefined
}) {
    const [showDropdown, setShowDropdown] = useState<boolean>(false);
    const [focusedIndex, setFocusedIndex] = useState<number>(-1);
    const [scrollIndex, setScrollIndex] = useState<number>(-1);
    const [filteredSuggestions, setFilteredSuggestions] = useState<string[]>(suggestions);
    const dropdownRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        if (props.value) {
            const filtered = suggestions.filter(suggestion =>
                suggestion.toLowerCase().includes(props.value?.toLowerCase() ?? '')
            );
            setFilteredSuggestions(filtered);
            if(filtered.length > 1) {
                setShowDropdown(true);
            } else if (filtered.length === 1 && filtered[0] !== props.value) {
                setShowDropdown(true);
            } else {
                setShowDropdown(false);
            }
        } else {
            setFilteredSuggestions(suggestions);
            setShowDropdown(false);
        }
        setFocusedIndex(-1);
    }, [props.value, setFilteredSuggestions, suggestions]);

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
                top: scrollIndex * 40,
            })
        }
    }, [scrollIndex]);

    const handleKeyDown: KeyboardEventHandler<HTMLInputElement> = (e: KeyboardEvent<HTMLInputElement>) => {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            setFocusedIndex(prev => Math.min(filteredSuggestions.length - 1, prev +1));
            setShowDropdown(true);
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            setFocusedIndex(prev => Math.max(0, prev -1));
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (focusedIndex >= 0 && filteredSuggestions[focusedIndex]) {
                handleSuggestionSelect(filteredSuggestions[focusedIndex]);
            }
        } else if (e.key === 'Escape') {
            setShowDropdown(false);
            setFocusedIndex(-1);
        }
    };

    const handleSuggestionSelect = (suggestion: string) => {
        onSuggestionSelected(suggestion);
        setShowDropdown(false);
        setFocusedIndex(-1);
    };

    return (
        <div className="relative">
            <Input
                {...props}
                className={className}
                onKeyDown={handleKeyDown}
                onFocus={() => props.value && setShowDropdown(filteredSuggestions.length > 0)}
                onBlur={() => setTimeout(() => setShowDropdown(false), 200)}
            />
            {showDropdown && (
                <div
                    ref={dropdownRef}
                    className="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"
                >
                    {filteredSuggestions.map((suggestion, index) => (
                        <button
                            key={suggestion}
                            onClick={() => handleSuggestionSelect(suggestion)}
                            className={`w-full text-left px-3 py-2 hover:bg-gray-50 ${
                                index === focusedIndex ? 'bg-primary/5 text-primary' : 'text-gray-700'
                            }`}
                        >
                            {suggestion}
                        </button>
                    ))}
                </div>
            )}
        </div>
    );
}

export { Suggest }
