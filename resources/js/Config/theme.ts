import { extendTheme, type ThemeConfig } from '@chakra-ui/react';
import { drawerTheme } from '@/Config/Theme/Drawer';
import backgroundImg from '@/assets/asphalt.png';
import {
    buttonTheme,
    checkboxTheme,
    inputTheme,
    selectTheme,
} from '@/Config/Theme/FormElements';
import { tableTheme } from '@/Config/Theme/Table';
import { menuTheme } from '@/Config/Theme/Menu';
import { cardTheme } from '@/Config/Theme/Card';
import { statTheme } from '@/Config/Theme/Stat';
import { listTheme } from '@/Config/Theme/List';
import { tabsTheme } from '@/Config/Theme/Tabs';

const config: ThemeConfig = {
    initialColorMode: 'dark',
    useSystemColorMode: false,
};

const theme = extendTheme({
    config,
    styles: {
        global: {
            body: {
                bgColor: '#1b1a1a',
                bgImg: backgroundImg, // Might want to optimize with imageSet
                color: 'text',
            },
            'body, #app': {
                minH: '100vh',
            },
        },
    },
    colors: {
        brand: {
            50: '#90BBBD',
            100: '#84B3B6',
            200: '#76ABAE',
            300: '#64A0A3',
            400: '#589295',
            500: '#4F8386',
            600: '#477679',
            700: '#406A6D',
            800: '#3A5F62',
            900: '#345558',
        },
    },
    semanticTokens: {
        colors: {
            dark: {
                1: '#222831',
                2: '#424b5a',
            },
            text: '#CECECE',
            error: 'red.300',
        },
    },
    components: {
        Button: buttonTheme,
        Card: cardTheme,
        Checkbox: checkboxTheme,
        Drawer: drawerTheme,
        List: listTheme,
        Menu: menuTheme,
        Input: inputTheme,
        Select: selectTheme,
        Stat: statTheme,
        Table: tableTheme,
        Tabs: tabsTheme,
    },
});

export default theme;
