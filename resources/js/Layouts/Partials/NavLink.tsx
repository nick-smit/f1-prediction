import { Box } from '@chakra-ui/react';
import Color from 'color';
import theme from '@/Config/theme';
import LinkBridge from '@/Components/LinkBridge';
import React, { PropsWithChildren } from 'react';

type Props = PropsWithChildren<{ key?: string; href: string }>;

export default function NavLink({ href, children }: Props) {
    return (
        <Box
            p={2}
            borderRight={`1px solid ${Color(theme.semanticTokens.colors.dark['2']).alpha(0.3).string()}`}
            __css={{
                ':last-of-type': {
                    borderRight: 'none',
                },
            }}
        >
            <LinkBridge href={href}>{children}</LinkBridge>
        </Box>
    );
}
