import Layout from '@/Layouts/Layout';
import AdminBox from '@/Components/AdminBox';
import { Head, router } from '@inertiajs/react';
import {
    Button,
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
import { EditIcon, SearchIcon } from '@chakra-ui/icons';
import { Paginator } from '@/types';
import LinkBridge from '@/Components/LinkBridge';
import React, { useEffect } from 'react';
import useSearchParameter from '@/hooks/useSearchParameter';
import { useDebounce } from '@uidotdev/usehooks';
import PaginationLinks from '@/Components/PaginationLinks';

type Team = {
    id: number;
    name: string;
};

type Props = {
    teams: Paginator<Team>;
};

export default function ({ teams }: Props) {
    const [search, setSearch] = useSearchParameter('s');

    const debouncedSearch = useDebounce(search, 250);
    useEffect(() => {
        router.reload();
    }, [debouncedSearch]);

    return (
        <Layout>
            <Head title={'Manage teams'} />
            <AdminBox>
                <Stack spacing={2}>
                    <Flex justify={'space-between'}>
                        <Heading size={'lg'} mb={8}>
                            Manage teams
                        </Heading>
                        <LinkBridge href={route('admin.teams.create')}>
                            <Button>New Team</Button>
                        </LinkBridge>
                    </Flex>
                    <HStack
                        spacing={4}
                        justifyContent={'end'}
                        alignItems={'center'}
                    >
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
                                    <Th colSpan={2}>Team</Th>
                                </Tr>
                            </Thead>
                            <Tbody>
                                {teams.data.map((team) => (
                                    <Tr
                                        key={team.id}
                                        _hover={{ bg: '#252C3A' }}
                                    >
                                        <Td>{team.name}</Td>
                                        <Td>
                                            <HStack
                                                spacing={2}
                                                justifyContent={'end'}
                                            >
                                                <LinkBridge
                                                    href={route(
                                                        'admin.teams.edit',
                                                        { team: team.id }
                                                    )}
                                                >
                                                    <IconButton
                                                        size={'xsm'}
                                                        aria-label={'Edit team'}
                                                        title={'Edit team'}
                                                        icon={<EditIcon />}
                                                        variant={'secondary'}
                                                    />
                                                </LinkBridge>
                                            </HStack>
                                        </Td>
                                    </Tr>
                                ))}
                            </Tbody>

                            <Tfoot>
                                <Tr>
                                    <Td colSpan={5} textAlign={'center'}>
                                        <PaginationLinks links={teams.links} />
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
