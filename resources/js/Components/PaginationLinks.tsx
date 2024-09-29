import LinkBridge from '@/Components/LinkBridge';
import React, { ReactElement } from 'react';
import { Paginator } from '@/types';

type Props = {
    links: Paginator<never>['links'];
    only?: string[];
};

export default function PaginationLinks({ links, only }: Props): ReactElement {
    const linkElements = links.map((link) => {
        if (link.url === null) {
            return;
        }

        return (
            <LinkBridge
                key={link.label}
                href={link.url}
                mx={1}
                fontWeight={link.active ? 'bold' : 'normal'}
                only={only}
                preserveScroll
            >
                {link.label}
            </LinkBridge>
        );
    });

    return <>{linkElements}</>;
}
