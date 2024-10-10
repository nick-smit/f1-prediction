import { Driver } from '@/types';
import { isInFuture } from '@/helpers/date';
import { Stack, Text } from '@chakra-ui/react';
import Countdown from '@/Components/Countdown';
import Prediction from '@/Pages/Predict/Partials/Prediction';
import { SessionType } from '@/Pages/Predict/Predict';
import React from 'react';

type Props = { session: SessionType; drivers: Driver[] };
export default function Session({ session, drivers }: Props) {
    if (isInFuture(session.session_start)) {
        return (
            <Stack spacing={4}>
                <Countdown endDate={session.session_start} />

                <Text>
                    Pick 10 drivers to make your prediction. Once picked you can
                    drag and drop drivers to the desired position.
                </Text>

                <Prediction
                    sessionId={session.id}
                    prediction={session.prediction}
                    drivers={drivers}
                />
            </Stack>
        );
    }

    return <div>Todo past sessions</div>;
}
