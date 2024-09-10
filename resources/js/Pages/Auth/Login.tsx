import { Head } from '@inertiajs/react';
import Layout from '@/Layouts/Layout';
import {
    Box,
    Button,
    Checkbox,
    Flex,
    FormControl,
    FormErrorMessage,
    FormLabel,
    Heading,
    Input,
    Stack,
    Text,
} from '@chakra-ui/react';
import LinkBridge from '@/Components/LinkBridge';
import AuthBox from '@/Components/AuthBox';
import useLoginForm from '@/Pages/Auth/hooks/useLoginForm';
import { type ReactElement } from 'react';

export default function Login({ status }: { status?: string }): ReactElement {
    const { data, errors, change, submit, processing } = useLoginForm();

    return (
        <Layout>
            <Head title="Sign in" />

            <Stack spacing={4}>
                <Box as={'header'}>
                    <Heading>Sign in</Heading>
                </Box>

                <AuthBox as={'main'}>
                    <Stack spacing={4}>
                        <Text>
                            Sign in to your account to access your games and
                            statistics. Don't have an account yet?{' '}
                            <LinkBridge
                                variant={'highlight'}
                                href={route('register')}
                            >
                                Sign up
                            </LinkBridge>{' '}
                            for free!
                        </Text>
                        {status !== null ? <Text>{status}</Text> : null}
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
                                <Checkbox
                                    name={'remember'}
                                    checked={data.remember}
                                    onChange={change}
                                >
                                    Remember me
                                </Checkbox>
                                <Flex
                                    direction={'row'}
                                    align={'center'}
                                    justify={'flex-end'}
                                    gap={'2rem'}
                                >
                                    {/*<LinkBridge href={route("password.request")}>*/}
                                    {/*  Forgot your password?*/}
                                    {/*</LinkBridge>*/}
                                    <Button
                                        variant={'primary'}
                                        type={'submit'}
                                        isLoading={processing}
                                        disabled={processing}
                                    >
                                        Sign in
                                    </Button>
                                </Flex>
                            </Stack>
                        </form>
                    </Stack>
                </AuthBox>
            </Stack>
        </Layout>
    );
}
