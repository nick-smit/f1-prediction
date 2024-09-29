import Layout from '@/Layouts/Layout';
import ContractForm from '@/Pages/Admin/Contracts/Partials/ContractForm';
import { Driver, Team } from '@/types';

type Props = {
    drivers: Driver[];
    teams: Team[];
};

export default function Create({ drivers, teams }: Props) {
    return (
        <Layout title={'Create contract'}>
            <ContractForm drivers={drivers} teams={teams} />
        </Layout>
    );
}
