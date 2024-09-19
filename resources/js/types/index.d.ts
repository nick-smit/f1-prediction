import type { FormEventHandler } from 'react';
import useChange from '@/hooks/useChange';

type Date = string;
type DateTime = string;

type Timestampable = {
    created_at: DateTime;
    updated_at: DateTime;
};

export type User = Timestampable & {
    id: number;
    name: string;
    email: string;
    email_verified_at: DateTime;
    is_admin: boolean;
};

export type Driver = Timestampable & {
    id: number;
    number: number;
    name: string;
};

export type Team = Timestampable & {
    id: number;
    name: string;
};

export type DriverContract = Timestampable & {
    id: number;
    driver_id: number;
    team_id: number;
    start_date: Date;
    end_date: Date | null;
};

export enum SessionType {
    Practice = 'practice',
    SprintQualification = 'sprint_qualification',
    SprintRace = 'sprint_race',
    Qualification = 'qualification',
    Race = 'race',
}

export type PageProps<
    T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
    auth: {
        user: User | null;
    };
};

export type Paginator<T> = {
    current_page: number;
    data: Array<T>;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: Array<{
        url: string | null;
        label: string;
        active: false;
    }>;
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
};

export type UseForm<FormValues> = {
    data: FormValues;
    errors: Partial<Record<keyof FormValues, string>>;
    change: ReturnType<typeof useChange>;
    submit: FormEventHandler;
    processing: boolean;
    reset?: (...fields: (keyof FormValues)[]) => void;
};
