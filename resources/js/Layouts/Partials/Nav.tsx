import {
    Drawer,
    DrawerBody,
    DrawerCloseButton,
    DrawerContent,
    DrawerHeader,
    DrawerOverlay,
    Grid,
    HStack,
    IconButton,
    Menu,
    MenuButton,
    MenuItem,
    MenuList,
    useDisclosure,
} from '@chakra-ui/react';
import LinkBridge from '@/Components/LinkBridge';
import React, { useMemo, useRef } from 'react';
import { User } from '@/types';
import NavLink from '@/Layouts/Partials/NavLink';
import { ChevronDownIcon, HamburgerIcon } from '@chakra-ui/icons';
import ApplicationLogo from '@/Layouts/Partials/ApplicationLogo';

type Props = {
    user: User | null;
};

type NavigationArray = Array<
    | {
          type: 'href';
          href: string;
          text: string;
          check: (user: User | null) => boolean;
      }
    | {
          type: 'group';
          text: string;
          items: Array<{
              href: string;
              text: string;
          }>;
          check: (user: User | null) => boolean;
      }
>;

const navigation: NavigationArray = [
    {
        type: 'href',
        href: route('home'),
        text: 'Home',
        check: () => true,
    },
    {
        type: 'href',
        href: '#',
        text: 'Leaderboard',
        check: () => true,
    },
    {
        type: 'group',
        text: 'Admin',
        items: [
            {
                href: route('admin.drivers.index'),
                text: 'Drivers',
            },
            {
                href: route('admin.teams.index'),
                text: 'Teams',
            },
            {
                href: route('admin.race-sessions.index'),
                text: 'Race Sessions',
            },
        ],
        check: (user) => Boolean(user?.is_admin),
    },
];

// Todo: Implement layout on all pages
// Todo: Remove old layout
// Todo: Create a logo
// Todo: Fix border in mobile nav

export default function Nav({ user }: Props) {
    const navigationElements = useMemo(() => {
        return navigation.map((item) => {
            if (!item.check(user)) {
                return null;
            }

            if (item.type === 'href') {
                return (
                    <NavLink key={item.text} href={item.href}>
                        {item.text}
                    </NavLink>
                );
            }

            return (
                <Menu key={item.text} variant={'nav'}>
                    <MenuButton p={2}>
                        {item.text}
                        <ChevronDownIcon />
                    </MenuButton>
                    <MenuList>
                        {item.items.map((menuItem) => (
                            <LinkBridge
                                key={menuItem.href}
                                href={menuItem.href}
                            >
                                <MenuItem>{menuItem.text}</MenuItem>
                            </LinkBridge>
                        ))}
                    </MenuList>
                </Menu>
            );
        });
    }, [user]);

    const {
        isOpen: menuIsOpen,
        onOpen: handleOpenMenu,
        onClose: handleCloseMenu,
    } = useDisclosure();
    const menuButtonRef = useRef<HTMLButtonElement>(null);

    return (
        <Grid
            as={'nav'}
            bgColor={'dark.1'}
            gridTemplateColumns={{ lg: '1fr auto 1fr', base: 'auto 1fr 1fr' }}
            px={2}
            gap={2}
            alignItems={'center'}
        >
            <IconButton
                aria-label={'Open navigation'}
                icon={<HamburgerIcon />}
                size={'sm'}
                display={{ lg: 'none' }}
                ref={menuButtonRef}
                onClick={handleOpenMenu}
            />

            <Drawer
                size={'full'}
                placement={'left'}
                isOpen={menuIsOpen}
                onClose={handleCloseMenu}
                finalFocusRef={menuButtonRef}
            >
                <DrawerOverlay />
                <DrawerContent>
                    <DrawerCloseButton />
                    <DrawerHeader>Menu</DrawerHeader>

                    <DrawerBody>{navigationElements}</DrawerBody>
                </DrawerContent>
            </Drawer>

            <ApplicationLogo />

            <HStack spacing={0} display={{ lg: 'flex', base: 'none' }}>
                {navigationElements}
            </HStack>

            <HStack justify="end" spacing={0}>
                {user === null ? (
                    <>
                        <NavLink href={route('login')}>Sign in</NavLink>
                        <NavLink href={route('register')}>Sign up</NavLink>
                    </>
                ) : (
                    <>
                        <NavLink href={'#'}>Profile</NavLink>
                        <NavLink href={route('logout')}>Sign out</NavLink>
                    </>
                )}
            </HStack>
        </Grid>
    );
}
