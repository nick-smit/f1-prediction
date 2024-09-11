import { useCallback, useState } from 'react';

type ReturnType = [string, (newValue: string) => void];

export default function useSearchParameter(
    name: string,
    defaultValue: string = ''
): ReturnType {
    const [stateValue, setStateValue] = useState<string>(() => {
        const searchParams = new URLSearchParams(window.location.search);
        return searchParams.get(name) ?? defaultValue;
    });

    const setValue = useCallback(
        (newValue: string) => {
            const newParams = new URLSearchParams(window.location.search);

            if (newValue.length > 0) {
                newParams.set(name, newValue);
            } else {
                newParams.delete(name);
            }

            let newurl = `${window.location.protocol}//${window.location.host}${window.location.pathname}`;
            if (newParams.size > 0) {
                newurl += `?${newParams.toString()}`;
            }

            window.history.replaceState({ path: newurl }, '', newurl);

            setStateValue(newValue);
        },
        [name]
    );

    return [stateValue, setValue];
}
