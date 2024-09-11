import { type ComponentStyleConfig } from '@chakra-ui/react';

const buttonVariants = {
    primary: {
        color: 'white',
        bgSize: '100%',
        bgImage:
            'radial-gradient(ellipse at top 15% left 10%, #6457CF 0%, #6457CF 20%, #3E3A9C 100%)',
        transition: 'background-size 0.5s ease-in',
        _hover: {
            bgSize: '150%',
        },
    },
    secondary: {
        color: '#f1f1f1',
        bgSize: '150%',
        bgImage:
            'radial-gradient(ellipse at top 15% left 10%, #525c6b 0%, #404a59 80%, #283240 100%)',
        transition: 'background-size color transform 0.5s ease-in',
        _hover: {
            bgSize: '200%',
            color: 'white',
            transform: 'scale(1.2)',
        },
    },
    danger: {
        color: '#f1f1f1',
        bgSize: '100%',
        bgImage:
            'radial-gradient(ellipse at top 15% left 10%, #872525 0%, #6F1522 80%, #6F1522 100%)',
        transition: 'background-size color transform 0.5s ease-in',
        _hover: {
            bgSize: '150%',
            color: 'white',
            transform: 'scale(1.2)',
        },
    },
};
const Button: ComponentStyleConfig = {
    variants: buttonVariants,
};

const IconButton: ComponentStyleConfig = {
    variants: buttonVariants,
};

export { Button, IconButton };
