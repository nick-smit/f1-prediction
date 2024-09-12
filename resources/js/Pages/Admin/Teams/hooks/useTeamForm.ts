import { useForm } from '@inertiajs/react';
import { type FormEventHandler, useCallback } from 'react';
import useChange from '@/hooks/useChange';
import { Team, UseForm } from '@/types';

interface TeamFormValues {
    name: string;
}

export default function useTeamForm(team?: Team): UseForm<TeamFormValues> {
    const { data, setData, post, put, processing, errors } =
        useForm<TeamFormValues>({
            name: String(team?.name ?? ''),
        });

    const teamId = team?.id;
    const submit = useCallback<FormEventHandler>(
        (e) => {
            e.preventDefault();

            if (teamId) {
                put(route('admin.teams.update', { team: teamId }));
            } else {
                post(route('admin.teams.store'));
            }
        },
        [put, teamId]
    );

    const change = useChange(setData);

    return {
        data,
        errors,
        change,
        submit,
        processing,
    };
}
