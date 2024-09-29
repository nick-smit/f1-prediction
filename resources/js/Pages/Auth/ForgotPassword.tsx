import { type ReactElement, useEffect } from 'react';
import Layout from '@/Layouts/Layout';
import {
    Button,
    FormControl,
    FormErrorMessage,
    FormLabel,
    Input,
    Stack,
    Text,
    useToast,
} from '@chakra-ui/react';
import useForgotPasswordForm from '@/Pages/Auth/hooks/useForgotPasswordForm';

export default function ForgotPassword({
    status,
}: {
    status?: string;
}): ReactElement {
    const { data, errors, change, submit, processing } =
        useForgotPasswordForm();

    const toast = useToast();

    useEffect(() => {
        if (status) {
            toast({
                title: 'Reset link sent.',
                description: status,
                status: 'success',
                duration: 5000,
                isClosable: true,
            });
        }
    }, [status, toast]);

    return (
        <Layout title="Forgot Password">
            <form onSubmit={submit}>
                <Stack spacing={4}>
                    <Text>
                        Forgot your password? No problem. Just let us know your
                        email address and we will email you a password reset
                        link that will allow you to choose a new one.
                    </Text>

                    <FormControl isRequired isInvalid={Boolean(errors.email)}>
                        <FormLabel>Email</FormLabel>
                        <Input
                            type={'email'}
                            name={'email'}
                            value={data.email}
                            autoFocus
                            onChange={change}
                        />
                        <FormErrorMessage>{errors.email}</FormErrorMessage>
                    </FormControl>
                    <Button
                        type={'submit'}
                        isLoading={processing}
                        disabled={processing}
                    >
                        Email Password Reset Link
                    </Button>
                </Stack>
            </form>
        </Layout>
    );
}
