import { listAnatomy as parts } from '@chakra-ui/anatomy';
import { createMultiStyleConfigHelpers } from '@chakra-ui/react';

const { definePartsStyle, defineMultiStyleConfig } =
    createMultiStyleConfigHelpers(parts.keys);

export const listTheme = defineMultiStyleConfig({
    variants: {
        prediction: definePartsStyle({
            container: {
                px: 6,
            },
            item: {
                my: 2,
            },
            icon: {},
        }),
    },
});
