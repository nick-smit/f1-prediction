import { Box, Flex, Heading, HStack } from '@chakra-ui/react';
import Background from '@/Layouts/Partials/Background';
import React, { PropsWithChildren } from 'react';
import theme from '@/Config/theme';
import Color from 'color';

type Props = PropsWithChildren<{
    title: string;
}>;

export default function Main({ title, children }: Props) {
    return (
        <Flex minH={'100vh'} justify={'center'}>
            <Box w={'min(800px, calc(100% - 20px))'}>
                <Background />
                <Box
                    mt={{ lg: '-20px', base: '-14px' }}
                    __css={{
                        background: Color(theme.semanticTokens.colors.dark['1'])
                            .alpha(0.2)
                            .string(),
                        borderRadius: '16px',
                        boxShadow: '0 4px 30px rgba(0, 0, 0, 0.1)',
                        backdropFilter: 'blur(2px)',
                        border: `1px solid ${Color(theme.semanticTokens.colors.dark['2']).alpha(0.3).string()}`,
                    }}
                >
                    <HStack
                        as={'header'}
                        __css={{
                            p: 4,
                            borderBottom: `1px solid ${Color(theme.semanticTokens.colors.dark['2']).alpha(0.3).string()}`,
                        }}
                        align={'center'}
                    >
                        <Box
                            bgColor={'#bfbfbf'}
                            w={15}
                            h={15}
                            borderRadius={'25%'}
                        />
                        <Box
                            bgColor={'#bfbfbf'}
                            w={15}
                            h={15}
                            borderRadius={'25%'}
                        />
                        <Box
                            bgColor={'#bfbfbf'}
                            w={15}
                            h={15}
                            borderRadius={'25%'}
                        />
                        <Heading ml={4} flexGrow={1}>
                            {title}
                        </Heading>
                    </HStack>
                    <Box
                        as={'main'}
                        borderTop={`2px solid ${Color(theme.semanticTokens.colors.dark['2']).alpha(0.3).string()}`}
                        p={4}
                    >
                        {children}
                    </Box>
                </Box>
            </Box>
        </Flex>
    );
}
