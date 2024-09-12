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
import useDriverForm from '@/Pages/Admin/Drivers/hooks/useDriverForm';
import { Driver } from '@/types';
import LinkBridge from '@/Components/LinkBridge';

type Props = {
    driver?: Driver;
};

export default function DriverForm({ driver }: Props) {
    const { data, errors, change, submit, processing } = useDriverForm(driver);

    return (
        <form onSubmit={submit}>
            <Stack spacing={4} maxW={300}>
                <FormControl isRequired isInvalid={Boolean(errors.name)}>
                    <FormLabel>Name</FormLabel>
                    <Input name={'name'} value={data.name} onChange={change} />
                    <FormErrorMessage>{errors.name}</FormErrorMessage>
                </FormControl>
                <FormControl isRequired isInvalid={Boolean(errors.number)}>
                    <FormLabel>Number</FormLabel>
                    <Input
                        type={'number'}
                        name={'number'}
                        value={data.number}
                        onChange={change}
                    />
                    <FormErrorMessage>{errors.number}</FormErrorMessage>
                </FormControl>
                <HStack>
                    <LinkBridge href={route('admin.drivers.index')}>
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
