import { type ReactElement } from 'react';
import LinkBridge from '@/Components/LinkBridge';
import { User } from '@/types';
import { Heading, Stack } from '@chakra-ui/react';

type Props = {
    user: User | null;
};

export default function MenuItems({ user }: Props): ReactElement {
    return (
        <Stack>
            <LinkBridge href={route('home')}>Home</LinkBridge>
            {user === null ? (
                <>
                    <LinkBridge href={route('login')}>Sign in</LinkBridge>
                    <LinkBridge href={route('register')}>Sign up</LinkBridge>
                </>
            ) : null}
            {user?.is_admin ? (
                <>
                    <Heading size={'md'}>Admin</Heading>
                    <LinkBridge href={route('admin.drivers.index')}>
                        Drivers
                    </LinkBridge>
                    <LinkBridge href={route('admin.teams.index')}>
                        Teams
                    </LinkBridge>
                </>
            ) : null}
        </Stack>
    );
}
