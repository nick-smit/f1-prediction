import React, { PropsWithChildren, ReactElement } from 'react';
import { Head, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import { Stack } from '@chakra-ui/react';
import useCheckVerified from '@/hooks/useCheckVerified';
import Main from '@/Layouts/Partials/Main';
import Nav from '@/Layouts/Partials/Nav';

type Props = PropsWithChildren<{
    title: string;
}>;

export default function Layout({ title, children }: Props): ReactElement {
    const {
        auth: { user },
    } = usePage<PageProps>().props;

    useCheckVerified();

    return (
        <Stack direction={'column-reverse'} spacing={4}>
            <Head title={title} />
            <Main title={title}>{children}</Main>
            <Nav user={user} />
        </Stack>
    );
}
