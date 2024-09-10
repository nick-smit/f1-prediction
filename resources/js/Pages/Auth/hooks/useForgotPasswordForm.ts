import { useForm } from '@inertiajs/react';
import { type ChangeEvent, type FormEventHandler, useCallback } from 'react';
import useChange from '@/hooks/useChange';

interface ForgotPasswordFormValues {
    email: string;
}

interface UseForgotPasswordForm {
    data: ForgotPasswordFormValues;
    errors: Partial<Record<keyof ForgotPasswordFormValues, string>>;
    change: (e: ChangeEvent<HTMLInputElement>) => void;
    submit: FormEventHandler;
    processing: boolean;
}

export default function useForgotPasswordForm(): UseForgotPasswordForm {
    const { data, setData, post, processing, errors } =
        useForm<ForgotPasswordFormValues>({
            email: '',
        });

    const submit = useCallback<FormEventHandler>(
        (e) => {
            e.preventDefault();

            post(route('forgot-password'));
        },
        [post]
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
