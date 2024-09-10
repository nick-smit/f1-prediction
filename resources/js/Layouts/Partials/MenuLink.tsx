import LinkBridge from '@/Components/LinkBridge';
import { type PropsWithChildren, type ReactElement } from 'react';
import { MenuItem } from '@chakra-ui/react';

export default function MenuLink(
    props: PropsWithChildren<{ href: string }>
): ReactElement {
    return (
        <LinkBridge
            _hover={{
                textDecoration: 'none',
                bg: 'gray.700', // might need to be color mode dependent
            }}
            href={props.href}
        >
            <MenuItem>{props.children}</MenuItem>
        </LinkBridge>
    );
}
