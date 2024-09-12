import Layout from '@/Layouts/Layout';
import AdminBox from '@/Components/AdminBox';
import { Head, router } from '@inertiajs/react';
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

type Driver = {
    id: number;
    name: string;
    number: number;
    has_contract: boolean;
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
        <Layout>
            <Head title={'Manage drivers'} />
            <AdminBox>
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
                            defaultChecked={Boolean(hideInactive)}
                            checked={Boolean(hideInactive)}
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
                                <SearchIcon color="gray.300" />
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
                                    <Tr
                                        key={driver.id}
                                        _hover={{ bg: '#252C3A' }}
                                    >
                                        <Td>{driver.name}</Td>
                                        <Td isNumeric>{driver.number}</Td>
                                        <Td>
                                            {driver.has_contract
                                                ? driver.current_team_name
                                                : '-'}
                                        </Td>
                                        <Td>
                                            {driver.has_contract
                                                ? driver.current_contract_start
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
                                                        size={'xsm'}
                                                        aria-label={
                                                            'Edit driver'
                                                        }
                                                        title={'Edit driver'}
                                                        icon={<EditIcon />}
                                                        variant={'secondary'}
                                                    />
                                                </LinkBridge>
                                                <IconButton
                                                    size={'xsm'}
                                                    aria-label={
                                                        'Change contract'
                                                    }
                                                    title={'Change contract'}
                                                    icon={<CalendarIcon />}
                                                    variant={'secondary'}
                                                />
                                            </HStack>
                                        </Td>
                                    </Tr>
                                ))}
                            </Tbody>

                            <Tfoot>
                                <Tr>
                                    <Td colSpan={5} textAlign={'center'}>
                                        {drivers.links.map((link) => {
                                            if (link.url === null) {
                                                return null;
                                            }

                                            return (
                                                <LinkBridge
                                                    key={link.label}
                                                    href={link.url}
                                                    mx={1}
                                                    fontWeight={
                                                        link.active
                                                            ? 'bold'
                                                            : 'normal'
                                                    }
                                                >
                                                    {link.label}
                                                </LinkBridge>
                                            );
                                        })}
                                    </Td>
                                </Tr>
                            </Tfoot>
                        </Table>
                    </TableContainer>
                </Stack>
            </AdminBox>
        </Layout>
    );
}
