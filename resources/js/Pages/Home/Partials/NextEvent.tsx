import {
    Button,
    Card,
    CardBody,
    CardHeader,
    Heading,
    Stack,
    Text,
} from '@chakra-ui/react';
import { ucfirst } from '@/helpers/string';
import { formatFullLocalizedDateTime } from '@/helpers/date';
import LinkBridge from '@/Components/LinkBridge';
import React from 'react';
import { NextEvent } from '@/Pages/Home/Home';
import Countdown from '@/Components/Countdown';

type Props = {
    nextEvent: NextEvent;
};

export default function ({ nextEvent }: Props) {
    return (
        <Card>
            <CardHeader>
                <Heading as={'h3'} size={'md'} textAlign={'center'}>
                    {nextEvent.race_weekend_name} {ucfirst(nextEvent.type)}
                </Heading>
                <Text size="xs" textAlign={'center'} opacity={0.5}>
                    {formatFullLocalizedDateTime(nextEvent.session_start)}
                </Text>
            </CardHeader>
            <Stack as={CardBody} spacing={4}>
                <Countdown endDate={nextEvent.session_start} />

                <LinkBridge href={route('prediction.index')}>
                    <Button w={'100%'}>PREDICT NOW!</Button>
                </LinkBridge>
            </Stack>
        </Card>
    );
}
