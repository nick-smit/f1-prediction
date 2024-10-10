import { cardAnatomy } from '@chakra-ui/anatomy';
import { createMultiStyleConfigHelpers } from '@chakra-ui/react';

const { defineMultiStyleConfig } = createMultiStyleConfigHelpers(
    cardAnatomy.keys
);

export const cardTheme = defineMultiStyleConfig({
    baseStyle: {
        container: {
            bgColor: 'dark.1',
            color: 'text',
        },
    },
});
