import LinkBridge from '@/Components/LinkBridge';
import React, { ReactElement } from 'react';
import { Paginator } from '@/types';

type Props = {
    links: Paginator<never>['links'];
};

export default function PaginationLinks({ links }: Props): ReactElement {
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
            >
                {link.label}
            </LinkBridge>
        );
    });

    return <>{linkElements}</>;
}
