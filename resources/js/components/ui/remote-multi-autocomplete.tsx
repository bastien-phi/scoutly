import RemoteAutocomplete from '@/components/ui/remote-autocomplete';
import { useCallback } from 'react';

export default function RemoteMultiAutocomplete<T>({
                                                       className,
                                                       selectedValues,
                                                       fetchOptionsUsing,
                                                       onValueAdded,
                                                       showUsing,
                                                       getValueUsing,
                                                       ...props
                                                   }: React.ComponentProps<'input'> & {
    selectedValues: string[]
    fetchOptionsUsing: (value: string) => Promise<void | T[]>
    onValueAdded: (value: string) => void
    showUsing: (value: T) => string
    getValueUsing: (value: T) => string
}) {
    const fetchAndFilterOptions = useCallback(
        (search: string) => fetchOptionsUsing(search).then(
            options => options ? options.filter(opt => !selectedValues.includes(getValueUsing(opt))) : []
        ),
        [fetchOptionsUsing, selectedValues, getValueUsing]
    );

    return (
        <RemoteAutocomplete
            className={className}
            value=""
            fetchOptionsUsing={fetchAndFilterOptions}
            onValueChanged={onValueAdded}
            showUsing={showUsing}
            getValueUsing={getValueUsing}
            resetOnSelected={true}
            {...props}
        />
    );
}
