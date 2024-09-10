// @ts-expect-error Typescript cannot find the module, but it is clearly there.
import { type InertiaFormProps } from '@inertiajs/react/types/useForm';
import { type ChangeEvent, type ChangeEventHandler, useCallback } from 'react';

export default function useChange<TForm extends object>(
    setData: InertiaFormProps<TForm>['setData']
): ChangeEventHandler {
    return useCallback<ChangeEventHandler>(
        (e: ChangeEvent<HTMLInputElement>): void => {
            switch (e.target.type) {
                case 'checkbox':
                    setData(e.target.name, e.target.checked);
                    break;
                default:
                    setData(e.target.name, e.target.value);
            }
        },
        [setData]
    );
}
