import { extendTheme, type ThemeConfig } from '@chakra-ui/react';
import { Button, IconButton } from '@/Config/Theme/Button';
import Link from '@/Config/Theme/Link';
import Stat from '@/Config/Theme/Stat';

const config: ThemeConfig = {
    initialColorMode: 'light',
    useSystemColorMode: false,
};

const theme = extendTheme({
    config,
    styles: {
        global: {
            'body, #app': {
                minH: '100vh',
            },
        },
    },
    semanticTokens: {
        colors: {
            primary: {
                default: '#6457CF',
                _dark: '#A8A0E4',
            },
        },
    },
    components: {
        Button,
        IconButton,
        Link,
        Stat,
    },
});

export default theme;
