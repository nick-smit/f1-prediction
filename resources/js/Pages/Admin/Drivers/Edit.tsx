import Layout from '@/Layouts/Layout';
import React from 'react';
import DriverForm from '@/Pages/Admin/Drivers/Partials/DriverForm';
import { Driver } from '@/types';

type Props = {
    driver: Driver;
};

export default function Edit({ driver }: Props) {
    return (
        <Layout title={`Edit driver ${driver.name}`}>
            <DriverForm driver={driver} />
        </Layout>
    );
}
