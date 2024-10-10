import { createMultiStyleConfigHelpers } from '@chakra-ui/react';
import { tabsAnatomy } from '@chakra-ui/anatomy';

const { defineMultiStyleConfig } = createMultiStyleConfigHelpers(
    tabsAnatomy.keys
);

export const tabsTheme = defineMultiStyleConfig({
    defaultProps: {
        colorScheme: 'brand',
    },
});
