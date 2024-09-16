import Layout from '@/Layouts/Layout';
import { Head } from '@inertiajs/react';
import AdminBox from '@/Components/AdminBox';
import { Heading } from '@chakra-ui/react';
import ContractForm from '@/Pages/Admin/Contracts/Partials/ContractForm';
import { Driver, Team } from '@/types';

type Props = {
    drivers: Driver[];
    teams: Team[];
};

export default function Create({ drivers, teams }: Props) {
    return (
        <Layout>
            <Head title={'Create contract'} />
            <AdminBox>
                <Heading size={'lg'} mb={8}>
                    Create contract
                </Heading>

                <ContractForm drivers={drivers} teams={teams} />
            </AdminBox>
        </Layout>
    );
}
