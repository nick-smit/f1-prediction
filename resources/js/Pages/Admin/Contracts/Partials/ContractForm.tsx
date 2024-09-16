import { Driver, DriverContract, Team } from '@/types';
import {
    Button,
    FormControl,
    FormErrorMessage,
    FormLabel,
    HStack,
    IconButton,
    Input,
    InputGroup,
    InputRightElement,
    Select,
    Stack,
} from '@chakra-ui/react';
import useContractForm from '@/Pages/Admin/Contracts/hooks/useContractForm';
import LinkBridge from '@/Components/LinkBridge';
import React from 'react';
import { SmallCloseIcon } from '@chakra-ui/icons';

type Props = {
    contract?: DriverContract;
    drivers: Driver[];
    teams: Team[];
};

export default function ContractForm({ contract, drivers, teams }: Props) {
    const { data, errors, change, submit, processing, reset } =
        useContractForm(contract);

    return (
        <form onSubmit={submit}>
            <Stack spacing={4} maxW={300}>
                <FormControl isRequired isInvalid={Boolean(errors.driver)}>
                    <FormLabel>Driver</FormLabel>
                    <Select
                        placeholder={'Select a driver'}
                        name={'driver'}
                        value={data.driver}
                        onChange={change}
                    >
                        {drivers.map((driver) => (
                            <option key={driver.id} value={driver.id}>
                                {driver.name}
                            </option>
                        ))}
                    </Select>
                    <FormErrorMessage>{errors.driver}</FormErrorMessage>
                </FormControl>

                <FormControl isRequired isInvalid={Boolean(errors.team)}>
                    <FormLabel>Team</FormLabel>
                    <Select
                        placeholder={'Select a team'}
                        name={'team'}
                        value={data.team}
                        onChange={change}
                    >
                        {teams.map((team) => (
                            <option key={team.id} value={team.id}>
                                {team.name}
                            </option>
                        ))}
                    </Select>
                    <FormErrorMessage>{data.team}</FormErrorMessage>
                </FormControl>

                <FormControl isRequired isInvalid={Boolean(errors.start_date)}>
                    <FormLabel>Start date</FormLabel>
                    <Input
                        type="date"
                        name={'start_date'}
                        value={data.start_date}
                        onChange={change}
                    />
                    <FormErrorMessage></FormErrorMessage>
                </FormControl>

                <FormControl isInvalid={Boolean(errors.end_date)}>
                    <FormLabel>End date</FormLabel>
                    <InputGroup>
                        <Input
                            type="date"
                            name={'end_date'}
                            value={data.end_date}
                            onChange={change}
                        />
                        {data.end_date.length > 0 ? (
                            <InputRightElement>
                                <IconButton
                                    size={'sm'}
                                    bg={'none'}
                                    aria-label={'reset'}
                                    icon={<SmallCloseIcon />}
                                    onClick={() => reset('end_date')}
                                />
                            </InputRightElement>
                        ) : null}
                    </InputGroup>
                    <FormErrorMessage>{errors.end_date}</FormErrorMessage>
                </FormControl>

                <HStack>
                    <LinkBridge href={route('admin.teams.index')}>
                        <Button variant={'secondary'}>Cancel</Button>
                    </LinkBridge>
                    <Button
                        variant={'primary'}
                        type={'submit'}
                        isLoading={processing}
                        disabled={processing}
                    >
                        Save
                    </Button>
                </HStack>
            </Stack>
        </form>
    );
}
