import { Box, type BoxProps, useColorModeValue } from '@chakra-ui/react';
import { type ReactElement } from 'react';

export default function AdminBox(props: BoxProps): ReactElement {
    return (
        <Box
            __css={{
                w: {
                    base: '100%',
                },
                bg: useColorModeValue('gray.50', 'gray.700'),
                p: 4,
                borderRadius: 'sm',
            }}
            {...props}
        />
    );
}
