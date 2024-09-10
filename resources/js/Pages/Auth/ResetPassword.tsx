import { type ReactElement } from 'react';
import { Head } from '@inertiajs/react';
import Layout from '@/Layouts/Layout';
import {
    Button,
    FormControl,
    FormErrorMessage,
    FormLabel,
    Heading,
    Input,
    Stack,
    Text,
} from '@chakra-ui/react';
import AuthBox from '@/Components/AuthBox';
import useResetPasswordForm from '@/Pages/Auth/hooks/useResetPasswordForm';

export default function ResetPassword({
    token,
    email,
}: {
    token: string;
    email: string;
}): ReactElement {
    const { data, errors, change, submit, processing } = useResetPasswordForm(
        token,
        email
    );

    return (
        <Layout>
            <Head title="Reset Password" />

            <Stack spacing={4}>
                <Heading>Reset Password</Heading>

                <AuthBox as={'main'}>
                    <Stack spacing={4}>
                        <Text>
                            Enter your email address and new password to reset
                            your password.
                        </Text>
                        <form onSubmit={submit}>
                            <Stack spacing={4}>
                                <FormControl
                                    isRequired
                                    isInvalid={Boolean(errors.email)}
                                >
                                    <FormLabel>Email</FormLabel>
                                    <Input
                                        type={'email'}
                                        name={'email'}
                                        value={data.email}
                                        onChange={change}
                                    />
                                    <FormErrorMessage>
                                        {errors.email}
                                    </FormErrorMessage>
                                </FormControl>
                                <FormControl
                                    isRequired
                                    isInvalid={Boolean(errors.password)}
                                >
                                    <FormLabel>Password</FormLabel>
                                    <Input
                                        type={'password'}
                                        name={'password'}
                                        value={data.password}
                                        onChange={change}
                                    />
                                    <FormErrorMessage>
                                        {errors.password}
                                    </FormErrorMessage>
                                </FormControl>
                                <FormControl
                                    isRequired
                                    isInvalid={Boolean(
                                        errors.password_confirmation
                                    )}
                                >
                                    <FormLabel>Confirm Password</FormLabel>
                                    <Input
                                        type={'password'}
                                        name={'password_confirmation'}
                                        value={data.password_confirmation}
                                        onChange={change}
                                    />
                                    <FormErrorMessage>
                                        {errors.password_confirmation}
                                    </FormErrorMessage>
                                </FormControl>
                                <Button
                                    type={'submit'}
                                    variant={'primary'}
                                    isLoading={processing}
                                    disabled={processing}
                                >
                                    Reset Password
                                </Button>
                            </Stack>
                        </form>
                    </Stack>
                </AuthBox>
            </Stack>
        </Layout>
    );
}
