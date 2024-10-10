import { DateTime } from '@/types';
import React, { useEffect, useState } from 'react';
import { Duration } from 'dayjs/plugin/duration';
import { getDuration } from '@/helpers/date';
import { HStack, Stat, StatLabel, StatNumber } from '@chakra-ui/react';

type Props = { endDate: DateTime };
export default function Countdown({ endDate }: Props) {
    const [duration, setDuration] = useState<Duration>(() =>
        getDuration(endDate).add(1000, 'ms')
    );

    useEffect(() => {
        const interval = setInterval(() => {
            setDuration((prevState) => prevState.subtract(1000, 'ms'));
        }, 1000);

        return () => clearInterval(interval);
    }, [endDate]);

    return (
        <HStack __css={{ textAlign: 'center' }}>
            <Stat variant="countdown">
                <StatLabel>Days</StatLabel>
                <StatNumber>{duration.days()}</StatNumber>
            </Stat>
            <Stat variant="countdown">
                <StatLabel>Hours</StatLabel>
                <StatNumber>{duration.hours()}</StatNumber>
            </Stat>
            <Stat variant="countdown">
                <StatLabel>Minutes</StatLabel>
                <StatNumber>{duration.minutes()}</StatNumber>
            </Stat>
            <Stat variant="countdown">
                <StatLabel>Seconds</StatLabel>
                <StatNumber>{duration.seconds()}</StatNumber>
            </Stat>
        </HStack>
    );
}
