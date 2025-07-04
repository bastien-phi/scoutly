import Autocomplete from '@/components/ui/autocomplete';

export default function MultiAutocomplete<T>({className, selectedValues, options, onValueAdded, showUsing, getValueUsing, ...props }: React.ComponentProps<"input"> & {
    selectedValues: string[]
    options: T[]
    onValueAdded: (value: string) => void
    showUsing: (value: T) => string
    getValueUsing: (value: T) => string
}) {
    return (
        <Autocomplete
            className={className}
            value=""
            options={options.filter(opt => !selectedValues.includes(getValueUsing(opt)))}
            onValueChanged={onValueAdded}
            showUsing={showUsing}
            getValueUsing={getValueUsing}
            resetOnSelected={true}
            {...props}
        />
    )
}

export { MultiAutocomplete }
