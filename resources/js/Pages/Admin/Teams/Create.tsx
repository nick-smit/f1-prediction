import Layout from '@/Layouts/Layout';
import AdminBox from '@/Components/AdminBox';
import { Head } from '@inertiajs/react';
import React from 'react';
import { Heading, Stack } from '@chakra-ui/react';
import TeamForm from '@/Pages/Admin/Teams/Partials/TeamForm';

export default function Create() {
    return (
        <Layout>
            <Head title={`Create team`} />
            <AdminBox>
                <Stack spacing={2}>
                    <Heading size={'lg'} mb={8}>
                        Create team
                    </Heading>

                    <TeamForm />
                </Stack>
            </AdminBox>
        </Layout>
    );
}
