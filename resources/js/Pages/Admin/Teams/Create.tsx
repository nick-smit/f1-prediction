import Layout from '@/Layouts/Layout';
import React from 'react';
import TeamForm from '@/Pages/Admin/Teams/Partials/TeamForm';

export default function Create() {
    return (
        <Layout title={`Create team`}>
            <TeamForm />
        </Layout>
    );
}
