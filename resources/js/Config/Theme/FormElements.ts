import {
    createMultiStyleConfigHelpers,
    defineStyleConfig,
} from '@chakra-ui/react';
import { inputAnatomy, selectAnatomy } from '@chakra-ui/anatomy';

export const buttonTheme = defineStyleConfig({
    defaultProps: {
        colorScheme: 'brand',
    },
    variants: {
        action: {
            width: 4,
            minWidth: 'auto',
            height: 4,
            background: 'transparent',
            _hover: {
                transition: 'transform ease-out 0.1s',
                transform: 'scale(1.1)',
            },
        },
    },
});

const {
    definePartsStyle: defineInputPartsStyle,
    defineMultiStyleConfig: defineInputMultiStyleConfig,
} = createMultiStyleConfigHelpers(inputAnatomy.keys);

const inputPartsStyle = defineInputPartsStyle({
    field: {
        border: '1px solid',
        borderColor: 'dark.2',
        bgColor: 'dark.1',
        color: 'text',
        _invalid: {
            borderColor: 'error',
        },
    },
    element: {
        color: 'dark.2',
    },
});
export const inputTheme = defineInputMultiStyleConfig({
    variants: {
        default: inputPartsStyle,
    },
    defaultProps: {
        variant: 'default',
    },
});

export const checkboxTheme = defineStyleConfig({
    defaultProps: {
        colorScheme: 'brand',
    },
});

const {
    definePartsStyle: defineSelectPartsStyle,
    defineMultiStyleConfig: defineSelectMultiStyleConfig,
} = createMultiStyleConfigHelpers(selectAnatomy.keys);

const baseStyle = defineSelectPartsStyle({
    field: {
        border: '1px solid',
        borderColor: 'dark.2',
        bgColor: 'dark.1',
        color: 'text',
        _invalid: {
            borderColor: 'error',
        },
    },
    icon: {
        color: 'text',
    },
});

export const selectTheme = defineSelectMultiStyleConfig({ baseStyle });
