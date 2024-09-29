import Layout from '@/Layouts/Layout';
import React from 'react';
import { Team } from '@/types';
import TeamForm from '@/Pages/Admin/Teams/Partials/TeamForm';

type Props = {
    team: Team;
};

export default function Edit({ team }: Props) {
    return (
        <Layout title={`Edit team ${team.name}`}>
            <TeamForm team={team} />
        </Layout>
    );
}
