import Layout from '@/Layouts/Layout';
import AdminBox from '@/Components/AdminBox';
import { Head } from '@inertiajs/react';
import React from 'react';
import { Heading, Stack } from '@chakra-ui/react';
import DriverForm from '@/Pages/Admin/Drivers/Partials/DriverForm';
import { Driver } from '@/types';

type Props = {
    driver: Driver;
};

export default function Edit({ driver }: Props) {
    return (
        <Layout>
            <Head title={`Edit driver ${driver.name}`} />
            <AdminBox>
                <Stack spacing={2}>
                    <Heading size={'lg'} mb={8}>
                        Edit driver {driver.name}
                    </Heading>

                    <DriverForm driver={driver} />
                </Stack>
            </AdminBox>
        </Layout>
    );
}
