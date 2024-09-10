import { Link as ChakraLink, type LinkProps } from '@chakra-ui/react';
import { Link as IntertiaLink } from '@inertiajs/react';
import { type ReactElement } from 'react';

export default function LinkBridge(props: Omit<LinkProps, 'as'>): ReactElement {
    return <ChakraLink as={IntertiaLink} {...props} />;
}
