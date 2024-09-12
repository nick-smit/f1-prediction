import Layout from '@/Layouts/Layout';
import AdminBox from '@/Components/AdminBox';
import { Head } from '@inertiajs/react';
import React from 'react';
import { Heading, Stack } from '@chakra-ui/react';
import { Team } from '@/types';
import TeamForm from '@/Pages/Admin/Teams/Partials/TeamForm';

type Props = {
    team: Team;
};

export default function Edit({ team }: Props) {
    return (
        <Layout>
            <Head title={`Edit team ${team.name}`} />
            <AdminBox>
                <Stack spacing={2}>
                    <Heading size={'lg'} mb={8}>
                        Edit team {team.name}
                    </Heading>

                    <TeamForm team={team} />
                </Stack>
            </AdminBox>
        </Layout>
    );
}
