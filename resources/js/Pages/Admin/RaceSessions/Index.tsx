import Layout from '@/Layouts/Layout';
import { router } from '@inertiajs/react';
import {
    Box,
    Button,
    Flex,
    FormControl,
    FormLabel,
    Heading,
    HStack,
    Input,
    List,
    ListItem,
    Table,
    TableContainer,
    Tbody,
    Td,
    Tfoot,
    Th,
    Thead,
    Tr,
} from '@chakra-ui/react';
import { DateTime, Paginator, SessionType } from '@/types';
import React, { useEffect, useState } from 'react';
import useSearchParameter from '@/hooks/useSearchParameter';
import PaginationLinks from '@/Components/PaginationLinks';
import { useDebounce } from '@uidotdev/usehooks';
import LinkBridge from '@/Components/LinkBridge';
import getActionText, {
    Action,
} from '@/Pages/Admin/RaceSessions/functions/getActionText';
import axios from 'axios';
import { formatLocalizedDateTime } from '@/helpers/date';

export type RaceSession = {
    id: number;
    race_weekend_name: string;
    type: SessionType;
    session_start: DateTime;
    session_end: DateTime;
    guesses: number;
    has_results: boolean;
};

type ActionRequired = {
    id: number;
    race_weekend_name: string;
    type: SessionType;
    action: Action;
};

type Props = {
    race_sessions: Paginator<RaceSession>;
    action_required: ActionRequired[];
};

export default function Index({ race_sessions, action_required }: Props) {
    const [progress, setProgress] = useState(-1);
    const [year, setYear] = useSearchParameter(
        'year',
        String(new Date().getFullYear())
    );

    const debouncedYear = useDebounce(year, 500);
    useEffect(() => {
        router.reload({ only: ['race_sessions'] });
    }, [debouncedYear]);

    return (
        <Layout title="Race Sessions">
            {action_required.length > 0 ? (
                <Box mb={8}>
                    <Flex justify={'space-between'} align={'center'} mb={2}>
                        <Heading size={'sm'}>
                            Some sessions require actions!
                        </Heading>
                        <Button
                            isDisabled={progress >= 0}
                            size={'sm'}
                            onClick={() => {
                                setProgress(0);
                                Promise.all(
                                    action_required.map((action) => {
                                        return axios
                                            .post(
                                                route(
                                                    `admin.race-sessions.${action.action}`,
                                                    {
                                                        race_session: action.id,
                                                    },
                                                    false
                                                )
                                            )
                                            .then(() =>
                                                setProgress(
                                                    (prevState) => prevState + 1
                                                )
                                            );
                                    })
                                ).then(() => {
                                    setProgress(-1);
                                    router.reload();
                                });
                            }}
                        >
                            Execute all
                        </Button>
                    </Flex>
                    <List>
                        {action_required.map((action) => {
                            const actionText = getActionText(action.action);

                            return (
                                <ListItem key={action.id} mb={2}>
                                    <Flex
                                        justify={'space-between'}
                                        align={'center'}
                                    >
                                        <span>
                                            <LinkBridge
                                                href={route(
                                                    'admin.race-sessions.show',
                                                    {
                                                        race_session: action.id,
                                                    }
                                                )}
                                            >
                                                {action.race_weekend_name}
                                            </LinkBridge>{' '}
                                            {action.type} requires action:{' '}
                                            {actionText}
                                        </span>
                                        <Button
                                            isDisabled={progress >= 0}
                                            size={'sm'}
                                            onClick={() => {
                                                axios
                                                    .post(
                                                        route(
                                                            `admin.race-sessions.${action.action}`,
                                                            {
                                                                race_session:
                                                                    action.id,
                                                            }
                                                        )
                                                    )
                                                    .then(() =>
                                                        router.reload()
                                                    );
                                            }}
                                        >
                                            Execute
                                        </Button>
                                    </Flex>
                                </ListItem>
                            );
                        })}
                    </List>
                </Box>
            ) : null}

            <HStack spacing={4} justifyContent={'end'} alignItems={'center'}>
                <FormControl maxW={100}>
                    <FormLabel>Year</FormLabel>
                    <Input
                        type="number"
                        value={year}
                        onChange={(e) => setYear(e.target.value)}
                    />
                </FormControl>
            </HStack>

            <TableContainer>
                <Table size="sm">
                    <Thead>
                        <Tr>
                            <Th>Race weekend</Th>
                            <Th>Session</Th>
                            <Th>Session start</Th>
                            <Th isNumeric>Guesses</Th>
                            <Th>Results in</Th>
                        </Tr>
                    </Thead>
                    <Tbody>
                        {race_sessions.data.map((raceSession) => (
                            <Tr key={raceSession.id}>
                                <Td>
                                    <LinkBridge
                                        href={route(
                                            'admin.race-sessions.show',
                                            { race_session: raceSession.id }
                                        )}
                                    >
                                        {raceSession.race_weekend_name}
                                    </LinkBridge>
                                </Td>
                                <Td>{raceSession.type}</Td>
                                <Td>
                                    {formatLocalizedDateTime(
                                        raceSession.session_start
                                    )}
                                </Td>
                                <Td isNumeric>{raceSession.guesses}</Td>
                                <Td>
                                    {raceSession.has_results ? 'true' : 'false'}
                                </Td>
                            </Tr>
                        ))}
                    </Tbody>

                    <Tfoot>
                        <Tr>
                            <Td colSpan={5} textAlign="center">
                                <PaginationLinks
                                    links={race_sessions.links}
                                    only={['race_sessions']}
                                />
                            </Td>
                        </Tr>
                    </Tfoot>
                </Table>
            </TableContainer>
        </Layout>
    );
}
