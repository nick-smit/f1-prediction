import { useForm } from '@inertiajs/react';
import { type FormEventHandler, useCallback } from 'react';
import useChange from '@/hooks/useChange';
import { Driver } from '@/types';

interface DriverFormValues {
    number: string;
    name: string;
}

export default function useDriverForm(driver?: Driver) {
    const { data, setData, post, put, processing, errors } =
        useForm<DriverFormValues>({
            number: String(driver?.number ?? ''),
            name: String(driver?.name ?? ''),
        });

    const driverId = driver?.id;
    const submit = useCallback<FormEventHandler>(
        (e) => {
            e.preventDefault();

            if (driverId) {
                put(route('admin.drivers.update', { driver: driverId }));
            } else {
                post(route('admin.drivers.store'));
            }
        },
        [put, driverId]
    );

    const change = useChange(setData);

    return {
        data,
        errors,
        change,
        submit,
        processing,
    };
}
