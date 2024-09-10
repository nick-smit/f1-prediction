import { Head } from '@inertiajs/react';
import Layout from '@/Layouts/Layout';
import {
    Box,
    Button,
    Flex,
    FormControl,
    FormErrorMessage,
    FormLabel,
    Heading,
    Input,
    Stack,
    Text,
} from '@chakra-ui/react';
import AuthBox from '@/Components/AuthBox';
// import LinkBridge from "@/Components/LinkBridge";
import useRegisterForm from '@/Pages/Auth/hooks/useRegisterForm';
import { type ReactElement } from 'react';

export default function Register(): ReactElement {
    const { data, errors, change, submit, processing } = useRegisterForm();

    return (
        <Layout>
            <Head title="Sign up" />

            <Stack spacing={4}>
                <Box as={'header'}>
                    <Heading>Sign up</Heading>
                </Box>

                <AuthBox as={'main'}>
                    <Stack spacing={4}>
                        <Text>
                            Create an account to get playing for free. If you
                            already have an account, you can{' '}
                            {/*<LinkBridge variant={"highlight"} href={route("login")}>*/}
                            {/*  sign in*/}
                            {/*</LinkBridge>{" "}*/}
                            instead.
                        </Text>
                        <form onSubmit={submit}>
                            <Stack spacing={4}>
                                <FormControl
                                    isRequired
                                    isInvalid={Boolean(errors.name)}
                                >
                                    <FormLabel>Nickname</FormLabel>
                                    <Input
                                        name={'name'}
                                        value={data.name}
                                        onChange={change}
                                    />
                                    <FormErrorMessage>
                                        {errors.name}
                                    </FormErrorMessage>
                                </FormControl>
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
                                    <FormLabel>Confirm password</FormLabel>
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
                                <Flex
                                    direction={'row'}
                                    align={'center'}
                                    justify={'flex-end'}
                                    gap={'2rem'}
                                >
                                    {/*<LinkBridge href={route("login")}>*/}
                                    {/*  Already registered?*/}
                                    {/*</LinkBridge>*/}
                                    <Button
                                        variant={'primary'}
                                        type={'submit'}
                                        isLoading={processing}
                                        disabled={processing}
                                    >
                                        Sign up
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
