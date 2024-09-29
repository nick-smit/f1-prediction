import Layout from '@/Layouts/Layout';
import React from 'react';
import DriverForm from '@/Pages/Admin/Drivers/Partials/DriverForm';

export default function Create() {
    return (
        <Layout title={`Create driver`}>
            <DriverForm />
        </Layout>
    );
}
