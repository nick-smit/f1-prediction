import Layout from '@/Layouts/Layout';
import { DateTime, Driver, Nullable } from '@/types';
import {
    Button,
    Flex,
    Tab,
    TabList,
    TabPanel,
    TabPanels,
    Tabs,
    Text,
} from '@chakra-ui/react';
import React from 'react';
import Session from '@/Pages/Predict/Partials/Session';
import LinkBridge from '@/Components/LinkBridge';
import { isInFuture } from '@/helpers/date';

export type SessionType = {
    id: number;
    session_start: DateTime;
    prediction: Driver[];
};

type Props = {
    event: Nullable<{
        name: string;
        qualification: SessionType;
        race: SessionType;
        previous_event_slug: Nullable<string>;
        next_event_slug: Nullable<string>;
    }>;
    drivers: Driver[];
};

export default function ({ event, drivers }: Props) {
    if (event === null) {
        return (
            <Layout title="Predict">
                <Text>
                    There is no event which you can make a prediction for right
                    now.
                </Text>
            </Layout>
        );
    }

    return (
        <Layout title={`Predict ${event.name}`}>
            <Tabs
                isFitted
                defaultIndex={
                    isInFuture(event.qualification.session_start) ? 0 : 1
                }
            >
                <Flex align={'center'}>
                    {event.previous_event_slug ? (
                        <LinkBridge
                            href={route('prediction.show', {
                                raceWeekend: event.previous_event_slug,
                            })}
                        >
                            <Button variant="link">&laquo;</Button>
                        </LinkBridge>
                    ) : (
                        <Button variant="link" isDisabled>
                            &laquo;
                        </Button>
                    )}

                    <TabList flexGrow={1}>
                        <Tab>Qualification</Tab>
                        <Tab>Race</Tab>
                    </TabList>

                    {event.next_event_slug ? (
                        <LinkBridge
                            href={route('prediction.show', {
                                raceWeekend: event.next_event_slug,
                            })}
                        >
                            <Button variant="link">&raquo;</Button>
                        </LinkBridge>
                    ) : (
                        <Button variant="link" isDisabled>
                            &raquo;
                        </Button>
                    )}
                </Flex>
                <TabPanels>
                    <TabPanel>
                        <Session
                            session={event.qualification}
                            drivers={drivers}
                        />
                    </TabPanel>
                    <TabPanel>
                        <Session session={event.race} drivers={drivers} />
                    </TabPanel>
                </TabPanels>
            </Tabs>
        </Layout>
    );
}
