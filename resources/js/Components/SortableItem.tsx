import React, { PropsWithChildren } from 'react';
import { useSortable } from '@dnd-kit/sortable';
import { CSS } from '@dnd-kit/utilities';
import { Box } from '@chakra-ui/react';

type Props = PropsWithChildren<{
    id: string | number;
}>;

export default function SortableItem({ id, children }: Props) {
    const { attributes, listeners, setNodeRef, transform, transition } =
        useSortable({ id });

    const style = {
        transform: CSS.Transform.toString(transform),
        transition,
        flexGrow: 1,
        textAlign: 'left',
        userSelect: 'none',
        touchAction: 'none',
    };

    return (
        <Box
            as="button"
            ref={setNodeRef}
            __css={style}
            {...attributes}
            {...listeners}
        >
            {children}
        </Box>
    );
}
