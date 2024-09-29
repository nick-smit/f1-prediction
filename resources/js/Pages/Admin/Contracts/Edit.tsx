import Layout from '@/Layouts/Layout';
import ContractForm from '@/Pages/Admin/Contracts/Partials/ContractForm';
import { Driver, DriverContract, Team } from '@/types';

type Props = {
    contract: DriverContract;
    drivers: Driver[];
    teams: Team[];
};

export default function Create({ contract, drivers, teams }: Props) {
    return (
        <Layout title={'Create contract'}>
            <ContractForm contract={contract} drivers={drivers} teams={teams} />
        </Layout>
    );
}
