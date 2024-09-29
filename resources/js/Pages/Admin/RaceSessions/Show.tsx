import Layout from '@/Layouts/Layout';
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
import { RaceSession } from '@/Pages/Admin/RaceSessions/Index';
import getActionText, {
    Action,
} from '@/Pages/Admin/RaceSessions/functions/getActionText';
import { formatFullLocalizedDateTime } from '@/helpers/date'; // ES 2015

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
    results: { data: Results | null };
    action: Action | null;
};

export default function Show({ race_session, results, action }: Props) {
    return (
        <Layout
            title={`${race_session.race_weekend_name} ${race_session.type}`}
        >
            <Stack spacing={8}>
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
                                    {formatFullLocalizedDateTime(
                                        race_session.session_start
                                    )}
                                </Td>
                            </Tr>
                            <Tr>
                                <Th>Session end</Th>
                                <Td>
                                    {formatFullLocalizedDateTime(
                                        race_session.session_start
                                    )}
                                </Td>
                            </Tr>
                            <Tr>
                                <Th>Guesses</Th>
                                <Td>{race_session.guesses}</Td>
                            </Tr>
                        </Tbody>
                    </Table>
                </TableContainer>

                {results.data ? (
                    <>
                        <Heading size={'sm'} as={'h3'}>
                            Results
                        </Heading>
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
                                        <Td>{results.data.p1.name}</Td>
                                    </Tr>
                                    <Tr>
                                        <Td maxW="20px">2</Td>
                                        <Td>{results.data.p2.name}</Td>
                                    </Tr>
                                    <Tr>
                                        <Td maxW="20px">3</Td>
                                        <Td>{results.data.p3.name}</Td>
                                    </Tr>
                                    <Tr>
                                        <Td maxW="20px">4</Td>
                                        <Td>{results.data.p4.name}</Td>
                                    </Tr>
                                    <Tr>
                                        <Td maxW="20px">5</Td>
                                        <Td>{results.data.p5.name}</Td>
                                    </Tr>
                                    <Tr>
                                        <Td maxW="20px">6</Td>
                                        <Td>{results.data.p6.name}</Td>
                                    </Tr>
                                    <Tr>
                                        <Td maxW="20px">7</Td>
                                        <Td>{results.data.p7.name}</Td>
                                    </Tr>
                                    <Tr>
                                        <Td maxW="20px">8</Td>
                                        <Td>{results.data.p8.name}</Td>
                                    </Tr>
                                    <Tr>
                                        <Td maxW="20px">9</Td>
                                        <Td>{results.data.p9.name}</Td>
                                    </Tr>
                                    <Tr>
                                        <Td maxW="20px">10</Td>
                                        <Td>{results.data.p10.name}</Td>
                                    </Tr>
                                </Tbody>
                            </Table>
                        </TableContainer>
                    </>
                ) : null}
            </Stack>
        </Layout>
    );
}
