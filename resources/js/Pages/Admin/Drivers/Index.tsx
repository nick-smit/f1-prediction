import Layout from '@/Layouts/Layout';
import { router } from '@inertiajs/react';
import {
    Button,
    Checkbox,
    Flex,
    Heading,
    HStack,
    IconButton,
    Input,
    InputGroup,
    InputRightElement,
    Stack,
    Table,
    TableContainer,
    Tbody,
    Td,
    Tfoot,
    Th,
    Thead,
    Tr,
} from '@chakra-ui/react';
import { CalendarIcon, EditIcon, SearchIcon } from '@chakra-ui/icons';
import { Paginator } from '@/types';
import LinkBridge from '@/Components/LinkBridge';
import React, { useEffect } from 'react';
import useSearchParameter from '@/hooks/useSearchParameter';
import { useDebounce } from '@uidotdev/usehooks';
import PaginationLinks from '@/Components/PaginationLinks';
import { formatLocalizedDate } from '@/helpers/date';

type Driver = {
    id: number;
    name: string;
    number: number;
    has_contract: boolean;
    current_contract_id: number;
    current_team_id: string;
    current_team_name: string;
    current_contract_start: string;
    current_contract_end: string | null;
};

type Props = {
    drivers: Paginator<Driver>;
};

export default function ({ drivers }: Props) {
    const [search, setSearch] = useSearchParameter('s');
    const [hideInactive, setHideInactive] = useSearchParameter(
        'hide_inactive',
        '1'
    );

    const debouncedSearch = useDebounce(search, 250);
    useEffect(() => {
        router.reload();
    }, [debouncedSearch, hideInactive]);

    return (
        <Layout title="Manage drivers">
            <Stack spacing={2}>
                <Flex justify={'space-between'}>
                    <Heading size={'lg'} mb={8}>
                        Manage drivers
                    </Heading>
                    <LinkBridge href={route('admin.drivers.create')}>
                        <Button>New Driver</Button>
                    </LinkBridge>
                </Flex>
                <HStack
                    spacing={4}
                    justifyContent={'end'}
                    alignItems={'center'}
                >
                    <Checkbox
                        defaultChecked={hideInactive === '1'}
                        checked={hideInactive === '1'}
                        onChange={(e) => {
                            setHideInactive(e.target.checked ? '1' : '0');
                        }}
                    >
                        Hide inactive drivers
                    </Checkbox>
                    <InputGroup w={300}>
                        <Input
                            placeholder="Search..."
                            value={search}
                            onChange={(e) => setSearch(e.target.value)}
                        />
                        <InputRightElement pointerEvents="none">
                            <SearchIcon />
                        </InputRightElement>
                    </InputGroup>
                </HStack>
                <TableContainer>
                    <Table size="sm">
                        <Thead>
                            <Tr>
                                <Th>Driver</Th>
                                <Th isNumeric>Number</Th>
                                <Th>Current team</Th>
                                <Th colSpan={2}>Current contract</Th>
                            </Tr>
                        </Thead>
                        <Tbody>
                            {drivers.data.map((driver) => (
                                <Tr key={driver.id}>
                                    <Td>{driver.name}</Td>
                                    <Td isNumeric>{driver.number}</Td>
                                    <Td>
                                        {driver.has_contract
                                            ? driver.current_team_name
                                            : '-'}
                                    </Td>
                                    <Td>
                                        {driver.has_contract
                                            ? formatLocalizedDate(
                                                  driver.current_contract_start
                                              )
                                            : '-'}
                                    </Td>
                                    <Td>
                                        <HStack
                                            spacing={2}
                                            justifyContent={'end'}
                                        >
                                            <LinkBridge
                                                href={route(
                                                    'admin.drivers.edit',
                                                    { driver: driver.id }
                                                )}
                                            >
                                                <IconButton
                                                    variant="action"
                                                    aria-label={'Edit driver'}
                                                    title={'Edit driver'}
                                                    icon={<EditIcon />}
                                                />
                                            </LinkBridge>
                                            {driver.has_contract ? (
                                                <LinkBridge
                                                    href={route(
                                                        'admin.contracts.edit',
                                                        {
                                                            contract:
                                                                driver.current_contract_id,
                                                        }
                                                    )}
                                                >
                                                    <IconButton
                                                        variant="action"
                                                        aria-label={
                                                            'Change contract'
                                                        }
                                                        title={
                                                            'Change contract'
                                                        }
                                                        icon={<CalendarIcon />}
                                                    />
                                                </LinkBridge>
                                            ) : (
                                                <LinkBridge
                                                    href={route(
                                                        'admin.contracts.create',
                                                        {
                                                            driver_id:
                                                                driver.id,
                                                        }
                                                    )}
                                                >
                                                    <IconButton
                                                        variant="action"
                                                        aria-label={
                                                            'Create contract'
                                                        }
                                                        title={
                                                            'Create contract'
                                                        }
                                                        icon={<CalendarIcon />}
                                                    />
                                                </LinkBridge>
                                            )}
                                        </HStack>
                                    </Td>
                                </Tr>
                            ))}
                        </Tbody>

                        <Tfoot>
                            <Tr>
                                <Td colSpan={5} textAlign={'center'}>
                                    <PaginationLinks links={drivers.links} />
                                </Td>
                            </Tr>
                        </Tfoot>
                    </Table>
                </TableContainer>
            </Stack>
        </Layout>
    );
}
