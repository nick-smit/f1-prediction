import { type ComponentStyleConfig } from '@chakra-ui/react';

const Button: ComponentStyleConfig = {
    variants: {
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
        danger: {
            color: 'white',
            bgSize: '100%',
            bgImage:
                'radial-gradient(ellipse at top 15% left 10%, #872525 0%, #6F1522 80%, #6F1522 100%)',
            transition: 'background-size 0.5s ease-in',
            _hover: {
                bgSize: '150%',
            },
        },
    },
};

export default Button;
