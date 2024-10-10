import Layout from '@/Layouts/Layout';
import React, { ReactElement } from 'react';
import { HStack, Stack, Text } from '@chakra-ui/react';
import { DateTime, SessionType } from '@/types';
import NextEvent from '@/Pages/Home/Partials/NextEvent';

export type NextEvent = {
    id: number;
    race_weekend_name: string;
    type: SessionType;
    session_start: DateTime;
};

type Props = {
    next_session: NextEvent;
};

export default function Home({ next_session }: Props): ReactElement {
    return (
        <Layout title={'Home'}>
            <Stack spacing={4}>
                <Text>
                    Welcome to GrandPrixGuessr, the best place to see who is the
                    best predictor for Formula 1 qualification and race
                    sessions!
                </Text>

                <HStack>
                    <NextEvent nextEvent={next_session} />
                </HStack>
            </Stack>
        </Layout>
    );
}
