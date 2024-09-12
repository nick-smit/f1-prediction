import type { ChangeEvent, FormEventHandler } from 'react';

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    is_admin: boolean;
}

export type Driver = {
    id: number;
    number: number;
    name: string;
    created_at: string;
    updated_at: string;
};

export type Team = {
    id: number;
    name: string;
    created_at: string;
    updated_at: string;
};

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
    change: (e: ChangeEvent<HTMLInputElement>) => void;
    submit: FormEventHandler;
    processing: boolean;
};
