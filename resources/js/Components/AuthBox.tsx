import { Box, type BoxProps, useColorModeValue } from '@chakra-ui/react';
import { type ReactElement } from 'react';

export default function AuthBox(props: BoxProps): ReactElement {
    return (
        <Box
            __css={{
                w: {
                    base: '100%',
                    md: '32rem',
                },
                bg: useColorModeValue('gray.50', 'gray.700'),
                p: 4,
                borderRadius: 'sm',
            }}
            {...props}
        />
    );
}
