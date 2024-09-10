import { type MutableRefObject, type ReactElement } from 'react';
import {
    Drawer,
    DrawerBody,
    DrawerCloseButton,
    DrawerContent,
    DrawerHeader,
    DrawerOverlay,
    Stack,
} from '@chakra-ui/react';
import LinkBridge from '@/Components/LinkBridge';

interface Props {
    isOpen: boolean;
    onClose: () => void;
    finalFocusRef: MutableRefObject<HTMLButtonElement | null>;
}

export default function MenuDrawer({
    isOpen,
    onClose,
    finalFocusRef,
}: Props): ReactElement {
    // const {
    //   auth: { user },
    // } = usePage<PageProps>().props;

    return (
        <Drawer
            isOpen={isOpen}
            onClose={onClose}
            finalFocusRef={finalFocusRef}
            placement={'left'}
        >
            <DrawerOverlay />
            <DrawerContent>
                <DrawerCloseButton />
                <DrawerHeader>Menu</DrawerHeader>
                <DrawerBody>
                    <Stack>
                        {/*<LinkBridge href={route("home")}>My Darts</LinkBridge>*/}
                        {/*{user === null ? (*/}
                        {/*  <>*/}
                        {/*    <LinkBridge href={route("login")}>Sign in</LinkBridge>*/}
                        <LinkBridge href={route('register')}>
                            Sign up
                        </LinkBridge>
                        {/*  </>*/}
                        {/*) : (*/}
                        {/*  <>*/}
                        {/*    <LinkBridge href={route("dashboard")}>Dashboard</LinkBridge>*/}
                        {/*  </>*/}
                        {/*)}*/}
                    </Stack>
                </DrawerBody>
            </DrawerContent>
        </Drawer>
    );
}
