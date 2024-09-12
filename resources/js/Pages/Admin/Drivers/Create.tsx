import Layout from '@/Layouts/Layout';
import AdminBox from '@/Components/AdminBox';
import { Head } from '@inertiajs/react';
import React from 'react';
import { Heading, Stack } from '@chakra-ui/react';
import DriverForm from '@/Pages/Admin/Drivers/Partials/DriverForm';

export default function Create() {
    return (
        <Layout>
            <Head title={`Create driver`} />
            <AdminBox>
                <Stack spacing={2}>
                    <Heading size={'lg'} mb={8}>
                        Create driver
                    </Heading>

                    <DriverForm />
                </Stack>
            </AdminBox>
        </Layout>
    );
}
