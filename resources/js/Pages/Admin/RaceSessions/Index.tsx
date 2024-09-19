import Layout from '@/Layouts/Layout';
import { Head, router } from '@inertiajs/react';
import AdminBox from '@/Components/AdminBox';
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
import React, { useEffect } from 'react';
import useSearchParameter from '@/hooks/useSearchParameter';
import dayjs from 'dayjs';
import PaginationLinks from '@/Components/PaginationLinks';
import { useDebounce } from '@uidotdev/usehooks';
import LinkBridge from '@/Components/LinkBridge';
import getActionText, {
    Action,
} from '@/Pages/Admin/RaceSessions/functions/getActionText';

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
    const [year, setYear] = useSearchParameter(
        'year',
        String(new Date().getFullYear())
    );

    const debouncedYear = useDebounce(year, 500);
    useEffect(() => {
        router.reload({ only: ['race_sessions'] });
    }, [debouncedYear]);

    return (
        <Layout>
            <Head title="Race Sessions" />
            <AdminBox>
                <Heading size="lg" mb={8}>
                    Race Sessions
                </Heading>

                {action_required.length > 0 ? (
                    <Box mb={8}>
                        <Flex justify={'space-between'} align={'center'} mb={2}>
                            <Heading size={'sm'}>
                                Some sessions require actions!
                            </Heading>
                            <Button size={'sm'}>Execute all</Button>
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
                                                            race_session:
                                                                action.id,
                                                        }
                                                    )}
                                                >
                                                    {action.race_weekend_name}
                                                </LinkBridge>{' '}
                                                {action.type} requires action:{' '}
                                                {actionText}
                                            </span>
                                            <Button size={'sm'}>Execute</Button>
                                        </Flex>
                                    </ListItem>
                                );
                            })}
                        </List>
                    </Box>
                ) : null}

                <HStack
                    spacing={4}
                    justifyContent={'end'}
                    alignItems={'center'}
                >
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
                                <Tr
                                    key={raceSession.id}
                                    _hover={{ bg: '#252C3A' }}
                                >
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
                                        {dayjs(
                                            raceSession.session_start
                                        ).format('L HH:mm')}
                                    </Td>
                                    <Td isNumeric>{raceSession.guesses}</Td>
                                    <Td>
                                        {raceSession.has_results
                                            ? 'true'
                                            : 'false'}
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
            </AdminBox>
        </Layout>
    );
}
