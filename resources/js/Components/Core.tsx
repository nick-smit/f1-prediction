import { type PropsWithChildren, type ReactElement } from 'react';
import { ChakraProvider, ColorModeScript } from '@chakra-ui/react';
import theme from '@/Config/theme';

export default function Core({ children }: PropsWithChildren): ReactElement {
    return (
        <>
            <ColorModeScript initialColorMode={theme.config.initialColorMode} />
            <ChakraProvider theme={theme}>{children}</ChakraProvider>
        </>
    );
}
