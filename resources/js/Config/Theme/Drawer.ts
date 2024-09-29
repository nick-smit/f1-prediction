import { drawerAnatomy as parts } from '@chakra-ui/anatomy';
import { createMultiStyleConfigHelpers } from '@chakra-ui/styled-system';

const { definePartsStyle, defineMultiStyleConfig } =
    createMultiStyleConfigHelpers(parts.keys);

const baseStyle = definePartsStyle({
    dialog: {
        bg: 'dark.1', //change the background
    },
});

export const drawerTheme = defineMultiStyleConfig({
    baseStyle,
});
