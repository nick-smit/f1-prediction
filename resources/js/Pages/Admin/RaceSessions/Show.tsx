import Layout from '@/Layouts/Layout';
import { Head } from '@inertiajs/react';
import AdminBox from '@/Components/AdminBox';
import {
    Box,
    Button,
    Flex,
    Heading,
    Stack,
    Table,
    TableContainer,
    Tbody,
    Td,
    Th,
    Thead,
    Tr,
} from '@chakra-ui/react';
import { Driver } from '@/types';
import React from 'react';
import dayjs from 'dayjs';
import { RaceSession } from '@/Pages/Admin/RaceSessions/Index';
import localizedFormat from 'dayjs/plugin/localizedFormat';
import getActionText, {
    Action,
} from '@/Pages/Admin/RaceSessions/functions/getActionText'; // ES 2015

dayjs.extend(localizedFormat);

type Results = {
    p1: Driver;
    p2: Driver;
    p3: Driver;
    p4: Driver;
    p5: Driver;
    p6: Driver;
    p7: Driver;
    p8: Driver;
    p9: Driver;
    p10: Driver;
};

type Props = {
    race_session: RaceSession;
    results: Results | null;
    action: Action | null;
};

export default function Show({ race_session, results, action }: Props) {
    return (
        <Layout>
            <Head
                title={`${race_session.race_weekend_name} ${race_session.type}`}
            />
            <AdminBox>
                <Stack spacing={8}>
                    <Heading size="lg">
                        {race_session.race_weekend_name} {race_session.type}
                    </Heading>

                    {action !== null ? (
                        <Box>
                            <Heading size={'sm'}>Action required!</Heading>
                            <Flex align={'center'}>
                                <span>{getActionText(action)}</span>
                                <Button ml={8} size="sm">
                                    Execute
                                </Button>
                            </Flex>
                        </Box>
                    ) : null}

                    <TableContainer>
                        <Table size={'sm'}>
                            <Tbody>
                                <Tr>
                                    <Th>Race weekend</Th>
                                    <Td>{race_session.race_weekend_name}</Td>
                                </Tr>
                                <Tr>
                                    <Th>Session type</Th>
                                    <Td>{race_session.type}</Td>
                                </Tr>
                                <Tr>
                                    <Th>Session start</Th>
                                    <Td>
                                        {dayjs(
                                            race_session.session_start
                                        ).format('LLLL HH:mm')}
                                    </Td>
                                </Tr>
                                <Tr>
                                    <Th>Session end</Th>
                                    <Td>
                                        {dayjs(
                                            race_session.session_start
                                        ).format('LLLL HH:mm')}
                                    </Td>
                                </Tr>
                                <Tr>
                                    <Th>Guesses</Th>
                                    <Td>{race_session.guesses}</Td>
                                </Tr>
                            </Tbody>
                        </Table>
                    </TableContainer>

                    {results ? (
                        <>
                            <Heading size={'sm'}>Results</Heading>
                            <TableContainer>
                                <Table size={'sm'}>
                                    <Thead>
                                        <Tr>
                                            <Th maxW="20px">Position</Th>
                                            <Th>Driver</Th>
                                        </Tr>
                                    </Thead>
                                    <Tbody>
                                        <Tr>
                                            <Td maxW="20px">1</Td>
                                            <Td>{results.p1.name}</Td>
                                        </Tr>
                                        <Tr>
                                            <Td maxW="20px">2</Td>
                                            <Td>{results.p2.name}</Td>
                                        </Tr>
                                        <Tr>
                                            <Td maxW="20px">3</Td>
                                            <Td>{results.p3.name}</Td>
                                        </Tr>
                                        <Tr>
                                            <Td maxW="20px">4</Td>
                                            <Td>{results.p4.name}</Td>
                                        </Tr>
                                        <Tr>
                                            <Td maxW="20px">5</Td>
                                            <Td>{results.p5.name}</Td>
                                        </Tr>
                                        <Tr>
                                            <Td maxW="20px">6</Td>
                                            <Td>{results.p6.name}</Td>
                                        </Tr>
                                        <Tr>
                                            <Td maxW="20px">7</Td>
                                            <Td>{results.p7.name}</Td>
                                        </Tr>
                                        <Tr>
                                            <Td maxW="20px">8</Td>
                                            <Td>{results.p8.name}</Td>
                                        </Tr>
                                        <Tr>
                                            <Td maxW="20px">9</Td>
                                            <Td>{results.p9.name}</Td>
                                        </Tr>
                                        <Tr>
                                            <Td maxW="20px">10</Td>
                                            <Td>{results.p10.name}</Td>
                                        </Tr>
                                    </Tbody>
                                </Table>
                            </TableContainer>
                        </>
                    ) : null}
                </Stack>
            </AdminBox>
        </Layout>
    );
}
