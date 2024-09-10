import { useForm } from '@inertiajs/react';
import {
    type ChangeEvent,
    type FormEventHandler,
    useCallback,
    useEffect,
} from 'react';
import useChange from '@/hooks/useChange';

interface ResetPasswordFormValues {
    token: string;
    email: string;
    password: string;
    password_confirmation: string;
}

interface UseResetPasswordForm {
    data: ResetPasswordFormValues;
    errors: Partial<Record<keyof ResetPasswordFormValues, string>>;
    change: (e: ChangeEvent<HTMLInputElement>) => void;
    submit: FormEventHandler;
    processing: boolean;
}

export default function useResetPasswordForm(
    token: string,
    email: string
): UseResetPasswordForm {
    const { data, setData, post, processing, errors, reset } =
        useForm<ResetPasswordFormValues>({
            token,
            email,
            password: '',
            password_confirmation: '',
        });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const submit = useCallback<FormEventHandler>(
        (e) => {
            e.preventDefault();

            post(route('password.store'));
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
