import React, { PropsWithChildren, ReactElement, useRef } from 'react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import { router, usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import {
    Avatar,
    Box,
    Button,
    Flex,
    HStack,
    IconButton,
    Menu,
    MenuButton,
    MenuDivider,
    MenuItem,
    MenuList,
    Stack,
    useColorModeValue,
    useDisclosure,
} from '@chakra-ui/react';
import LinkBridge from '@/Components/LinkBridge';
import { Icon } from '@chakra-ui/icons';
import MenuDrawer from '@/Layouts/Partials/MenuDrawer';
import { RiMenuLine } from 'react-icons/ri';
import useCheckVerified from '@/hooks/useCheckVerified';
import MenuItems from '@/Layouts/Partials/MenuItems';

type Props = PropsWithChildren<{
    controlledPadding?: boolean;
}>;

export default function Layout({
    children,
    controlledPadding = false,
}: Props): ReactElement {
    const {
        auth: { user },
    } = usePage<PageProps>().props;
    const menuDrawerBtnRef = useRef<HTMLButtonElement>(null);

    const { isOpen, onOpen, onClose } = useDisclosure();

    useCheckVerified();

    return (
        <Stack spacing={0} minH={'100vh'}>
            <Box as={'nav'} bg={useColorModeValue('gray.50', 'gray.700')}>
                <Flex my={1} mx={2} align={'center'} justify={'space-between'}>
                    <HStack spacing={2}>
                        <IconButton
                            size={'md'}
                            icon={<Icon as={RiMenuLine} />}
                            aria-label={'Open Menu'}
                            display={{ md: 'none' }}
                            onClick={onOpen}
                            ref={menuDrawerBtnRef}
                        />
                        <Box w={8} aspectRatio={1}>
                            <ApplicationLogo />
                        </Box>
                    </HStack>
                    {user !== null ? (
                        <Menu>
                            <MenuButton
                                as={Button}
                                rounded={'full'}
                                variant={'link'}
                                cursor={'pointer'}
                                minW={0}
                            >
                                <Avatar size={'sm'} />
                            </MenuButton>
                            <MenuList>
                                {/*<MenuLink href={route("profile.edit")}>My Profile</MenuLink>*/}
                                {/*<MenuItem onClick={openFeedbackForm}>Leave feedback</MenuItem>*/}
                                <MenuDivider />
                                {/*<MenuLink href={route("settings.index")}>Settings</MenuLink>*/}
                                <MenuDivider />
                                <MenuItem
                                    onClick={() => {
                                        router.post(route('logout'));
                                    }}
                                >
                                    Sign off
                                </MenuItem>
                            </MenuList>
                        </Menu>
                    ) : (
                        <HStack spacing={2}>
                            <LinkBridge href={route('register')}>
                                Sign up
                            </LinkBridge>
                            <LinkBridge href={route('login')}>
                                Sign in
                            </LinkBridge>{' '}
                        </HStack>
                    )}
                </Flex>
            </Box>
            <HStack flexGrow={1} alignItems={'stretch'}>
                <Box
                    as={'nav'}
                    bg={useColorModeValue('gray.50', 'gray.700')}
                    minW={200}
                    display={{ md: 'block', base: 'none' }}
                >
                    <MenuItems user={user} />
                </Box>
                <Flex
                    flexGrow={1}
                    p={controlledPadding ? undefined : { base: 2, md: 12 }}
                    justify={'center'}
                    w={'100%'}
                >
                    {children}
                </Flex>
            </HStack>
            <MenuDrawer
                isOpen={isOpen}
                onClose={onClose}
                finalFocusRef={menuDrawerBtnRef}
            />
        </Stack>
    );
}
