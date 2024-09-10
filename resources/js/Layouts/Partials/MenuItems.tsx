import { type ReactElement } from 'react';
import LinkBridge from '@/Components/LinkBridge';
import { User } from '@/types';

type Props = {
    user: User | null;
};

export default function MenuItems({ user }: Props): ReactElement {
    return (
        <>
            <LinkBridge href={route('home')}>Home</LinkBridge>
            {user === null ? (
                <>
                    <LinkBridge href={route('login')}>Sign in</LinkBridge>
                    <LinkBridge href={route('register')}>Sign up</LinkBridge>
                </>
            ) : null}
        </>
    );
}
