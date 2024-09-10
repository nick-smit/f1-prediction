import { useToast } from '@chakra-ui/react';
import { useForm } from '@inertiajs/react';
import {
    type ChangeEvent,
    type FormEventHandler,
    useCallback,
    useEffect,
} from 'react';
import useChange from '@/hooks/useChange';

interface RegisterFormValues {
    name: string;
    email: string;
    password: string;
    password_confirmation: string;
}
interface UseRegisterForm<T> {
    data: T;
    errors: Partial<Record<keyof T, string>>;
    change: (e: ChangeEvent<HTMLInputElement>) => void;
    submit: FormEventHandler;
    processing: boolean;
}

export default function useRegisterForm(): UseRegisterForm<RegisterFormValues> {
    const toast = useToast();
    const { data, setData, post, processing, errors, reset } =
        useForm<RegisterFormValues>({
            name: '',
            email: '',
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
            console.log(data);
            e.preventDefault();

            post(route('register'), {
                onSuccess: () => {
                    toast({
                        title: 'Signed up.',
                        description:
                            'You are now signed up and automatically signed in.',
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
    };
}
