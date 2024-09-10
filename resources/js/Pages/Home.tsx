import Layout from '@/Layouts/Layout';
import React, { ReactElement } from 'react';
import { Head } from '@inertiajs/react';
import { Box, Heading, Stack, Text } from '@chakra-ui/react';

export default function Home(): ReactElement {
    return (
        <Layout>
            <Head title="Home" />

            <Stack spacing={4}>
                <Box as={'header'}>
                    <Heading>Welcome to MyDarts</Heading>
                </Box>
                <Box as={'main'}>
                    <Stack spacing={2}>
                        <Text>
                            GrandPrixGuessr is currently in development. If you
                            have any suggestions or feedback, please let me know
                            through the feedback form .
                        </Text>
                    </Stack>
                </Box>
            </Stack>
        </Layout>
    );
}
