import Layout from '@/Layouts/Layout';
import { DateTime, Driver, Nullable } from '@/types';
import {
    Tab,
    TabList,
    TabPanel,
    TabPanels,
    Tabs,
    Text,
} from '@chakra-ui/react';
import React from 'react';
import Session from '@/Pages/Predict/Partials/Session';

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
            <Tabs isFitted>
                <TabList>
                    <Tab>Qualification</Tab>
                    <Tab>Race</Tab>
                </TabList>
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
