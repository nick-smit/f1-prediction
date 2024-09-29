import { Heading } from '@chakra-ui/react';

export default function Background() {
    return (
        <Heading
            as="h1"
            textAlign={'center'}
            opacity={0.4}
            size={{ lg: '4xl', base: '2xl' }}
            userSelect={'none'}
        >
            GrandPrixGuessr
        </Heading>
    );
}
