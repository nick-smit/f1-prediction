import { menuAnatomy } from '@chakra-ui/anatomy';
import { createMultiStyleConfigHelpers } from '@chakra-ui/react';

const { definePartsStyle, defineMultiStyleConfig } =
    createMultiStyleConfigHelpers(menuAnatomy.keys);

export const menuTheme = defineMultiStyleConfig({
    variants: {
        nav: definePartsStyle({
            button: {
                _hover: {
                    textDecoration: 'underline',
                },
            },
        }),
    },
    baseStyle: definePartsStyle({
        list: {
            bg: 'dark.1',
            borderColor: 'dark.2',
        },
        item: {
            bg: 'inherit',
        },
    }),
});
