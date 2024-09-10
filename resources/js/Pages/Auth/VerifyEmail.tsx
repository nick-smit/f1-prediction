import { Head, router } from '@inertiajs/react';
import { type ReactElement, useState } from 'react';
import Layout from '@/Layouts/Layout';
import { Button, Flex, Heading, Stack, Text } from '@chakra-ui/react';
import AuthBox from '@/Components/AuthBox';

export default function VerifyEmail({
    status,
}: {
    status?: string;
}): ReactElement {
    const [processing, setProcessing] = useState<boolean>(false);

    return (
        <Layout>
            <Head title="Email Verification" />

            <Stack spacing={4}>
                <Heading>Verify email</Heading>

                <AuthBox as={'main'}>
                    <Stack spacing={4}>
                        <Text>
                            Thanks for signing up! Before getting started, could
                            you verify your email address by clicking on the
                            link we just emailed to you? If you didn't receive
                            the email, we will gladly send you another.
                        </Text>

                        {status === 'verification-link-sent' ? (
                            <Text>
                                A new verification link has been sent to the
                                email address you provided during registration.
                            </Text>
                        ) : null}

                        <Flex align={'center'} justify={'space-between'}>
                            <Button
                                variant={'primary'}
                                onClick={() => {
                                    router.post(
                                        route('verification.send'),
                                        undefined,
                                        {
                                            onStart: () => {
                                                setProcessing(true);
                                            },
                                            onFinish: () => {
                                                setProcessing(false);
                                            },
                                        }
                                    );
                                }}
                                disabled={processing}
                                isLoading={processing}
                            >
                                Resend Verification Email
                            </Button>
                            {/*<Link*/}
                            {/*  onClick={() => {*/}
                            {/*    router.post(route("logout"));*/}
                            {/*  }}*/}
                            {/*>*/}
                            {/*  Sign off*/}
                            {/*</Link>*/}
                        </Flex>
                    </Stack>
                </AuthBox>
            </Stack>
        </Layout>
    );
}
