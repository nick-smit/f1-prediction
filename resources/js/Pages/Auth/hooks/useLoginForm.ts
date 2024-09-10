import { useToast } from '@chakra-ui/react';
import { useForm } from '@inertiajs/react';
import {
    type ChangeEvent,
    type FormEventHandler,
    useCallback,
    useEffect,
} from 'react';
import useChange from '@/hooks/useChange';

interface LoginFormValues {
    email: string;
    password: string;
    remember: boolean;
}

interface UseLoginForm<T> {
    data: LoginFormValues;
    errors: Partial<Record<keyof LoginFormValues, string>>;
    change: (e: ChangeEvent<HTMLInputElement>) => void;
    submit: FormEventHandler;
    processing: boolean;
    setData: <K extends keyof T>(key: K, value: T[K]) => void;
}

export default function useLoginForm(): UseLoginForm<LoginFormValues> {
    const toast = useToast();
    const { data, setData, post, processing, errors, reset } =
        useForm<LoginFormValues>({
            email: '',
            password: '',
            remember: false,
        });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const submit = useCallback<FormEventHandler>(
        (e) => {
            e.preventDefault();

            post(route('login'), {
                onSuccess: () => {
                    toast({
                        title: 'Signed in.',
                        description: 'You are now signed in.',
                        status: 'success',
                        duration: 5000,
                        isClosable: true,
                    });
                },
            });
        },
        [post, toast]
    );

    const change = useChange(setData);

    return {
        data,
        errors,
        change,
        submit,
        processing,
        setData,
    };
}
