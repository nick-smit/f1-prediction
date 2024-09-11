import { type MutableRefObject, type ReactElement } from 'react';
import {
    Drawer,
    DrawerBody,
    DrawerCloseButton,
    DrawerContent,
    DrawerHeader,
    DrawerOverlay,
} from '@chakra-ui/react';
import { usePage } from '@inertiajs/react';
import { PageProps } from '@/types';
import MenuItems from '@/Layouts/Partials/MenuItems';

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
    const {
        auth: { user },
    } = usePage<PageProps>().props;

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
                    <MenuItems user={user} />
                </DrawerBody>
            </DrawerContent>
        </Drawer>
    );
}
