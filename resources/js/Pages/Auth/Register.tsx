import Layout from '@/Layouts/Layout';
import {
    Button,
    Flex,
    FormControl,
    FormErrorMessage,
    FormLabel,
    Input,
    Stack,
    Text,
} from '@chakra-ui/react';
import useRegisterForm from '@/Pages/Auth/hooks/useRegisterForm';
import LinkBridge from '@/Components/LinkBridge';

export default function Register() {
    const { data, errors, change, submit, processing } = useRegisterForm();

    return (
        <Layout title="Sign up">
            <form onSubmit={submit}>
                <Stack spacing={4}>
                    <Text>
                        Create an account to get playing for free. If you
                        already have an account, you can{' '}
                        <LinkBridge variant={'highlight'} href={route('login')}>
                            sign in
                        </LinkBridge>{' '}
                        instead.
                    </Text>
                    <FormControl isRequired isInvalid={Boolean(errors.name)}>
                        <FormLabel>Nickname</FormLabel>
                        <Input
                            name={'name'}
                            value={data.name}
                            onChange={change}
                        />
                        <FormErrorMessage>{errors.name}</FormErrorMessage>
                    </FormControl>
                    <FormControl isRequired isInvalid={Boolean(errors.email)}>
                        <FormLabel>Email</FormLabel>
                        <Input
                            type={'email'}
                            name={'email'}
                            value={data.email}
                            onChange={change}
                        />
                        <FormErrorMessage>{errors.email}</FormErrorMessage>
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
                        <FormErrorMessage>{errors.password}</FormErrorMessage>
                    </FormControl>
                    <FormControl
                        isRequired
                        isInvalid={Boolean(errors.password_confirmation)}
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
                        <LinkBridge href={route('login')}>
                            Already registered?
                        </LinkBridge>
                        <Button
                            type={'submit'}
                            isLoading={processing}
                            disabled={processing}
                        >
                            Sign up
                        </Button>
                    </Flex>
                </Stack>
            </form>
        </Layout>
    );
}
