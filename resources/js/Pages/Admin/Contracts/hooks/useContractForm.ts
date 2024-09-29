import { DriverContract } from '@/types';
import { useForm } from '@inertiajs/react';
import { type FormEventHandler, useCallback } from 'react';
import useChange from '@/hooks/useChange';
import useSearchParameter from '@/hooks/useSearchParameter';

type ContractFormValues = {
    driver: string;
    team: string;
    start_date: string;
    end_date: string;
};

export default function useContractForm(contract?: DriverContract) {
    const [driverId] = useSearchParameter('driver_id');

    const { data, setData, post, put, processing, errors, reset } =
        useForm<ContractFormValues>({
            team: String(contract?.team_id ?? ''),
            driver: String(contract?.driver_id ?? driverId),
            start_date: contract?.start_date ?? '',
            end_date: contract?.end_date ?? '',
        });

    const contractId = contract?.id;
    const submit = useCallback<FormEventHandler>(
        (e) => {
            e.preventDefault();

            if (contractId) {
                put(route('admin.contracts.update', { contract: contractId }));
            } else {
                post(route('admin.contracts.store'));
            }
        },
        [post, put, contractId]
    );

    const change = useChange(setData);

    return {
        data,
        errors,
        change,
        submit,
        processing,
        reset,
    };
}
