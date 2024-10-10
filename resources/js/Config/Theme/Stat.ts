import { statAnatomy } from '@chakra-ui/anatomy';
import { createMultiStyleConfigHelpers } from '@chakra-ui/react';

const { definePartsStyle, defineMultiStyleConfig } =
    createMultiStyleConfigHelpers(statAnatomy.keys);

export const statTheme = defineMultiStyleConfig({
    variants: {
        countdown: definePartsStyle({
            label: {
                fontSize: 'sm',
                opacity: 0.5,
            },
            number: {
                fontSize: '4xl',
            },
        }),
    },
});
