import {
    Button,
    FormControl,
    FormErrorMessage,
    FormLabel,
    HStack,
    Input,
    Stack,
} from '@chakra-ui/react';
import React from 'react';
import { Team } from '@/types';
import LinkBridge from '@/Components/LinkBridge';
import useTeamForm from '@/Pages/Admin/Teams/hooks/useTeamForm';

type Props = {
    team?: Team;
};

export default function TeamForm({ team }: Props) {
    const { data, errors, change, submit, processing } = useTeamForm(team);

    return (
        <form onSubmit={submit}>
            <Stack spacing={4}>
                <FormControl isRequired isInvalid={Boolean(errors.name)}>
                    <FormLabel>Name</FormLabel>
                    <Input name={'name'} value={data.name} onChange={change} />
                    <FormErrorMessage>{errors.name}</FormErrorMessage>
                </FormControl>
                <HStack spacing={4} justify={'end'}>
                    <LinkBridge href={route('admin.teams.index')}>
                        Cancel
                    </LinkBridge>
                    <Button
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
