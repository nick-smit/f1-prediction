import { tableAnatomy } from '@chakra-ui/anatomy';
import { createMultiStyleConfigHelpers } from '@chakra-ui/react';

const { definePartsStyle, defineMultiStyleConfig } =
    createMultiStyleConfigHelpers(tableAnatomy.keys);

export const tableTheme = defineMultiStyleConfig({
    defaultProps: {
        variant: 'default',
    },
    variants: {
        default: definePartsStyle({
            tbody: {
                tr: {
                    _hover: {
                        bgColor: 'dark.1',
                    },
                },
            },
        }),
    },
});
