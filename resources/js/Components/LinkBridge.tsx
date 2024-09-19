import { Link as ChakraLink, type LinkProps } from '@chakra-ui/react';
import { Link as IntertiaLink } from '@inertiajs/react';
import { type ReactElement } from 'react';
import { InertiaLinkProps } from '@inertiajs/react/types/Link';

export default function LinkBridge(
    props: Omit<LinkProps, 'as'> & InertiaLinkProps
): ReactElement {
    return <ChakraLink as={IntertiaLink} {...props} />;
}
