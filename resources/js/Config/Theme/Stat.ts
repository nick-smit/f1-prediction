import { createMultiStyleConfigHelpers } from '@chakra-ui/react';
import { statAnatomy } from '@chakra-ui/anatomy';

const { definePartsStyle, defineMultiStyleConfig } =
    createMultiStyleConfigHelpers(statAnatomy.keys);

const Stat = defineMultiStyleConfig({
    baseStyle: definePartsStyle({
        container: {
            padding: '2',
            textAlign: 'center',
        },
    }),
});

export default Stat;
