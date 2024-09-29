import { GridItem } from '@chakra-ui/react';

const GRID_BOX_SIZE = 200;
const GRID_BOX_VW_SIZE = GRID_BOX_SIZE / 5;

export default function GridBox() {
    return (
        <GridItem
            __css={{
                w: `min(${GRID_BOX_SIZE}px, ${GRID_BOX_VW_SIZE}vw)`,
                aspectRatio: 2.5,
                borderColor: 'rgba(255,255,255,0.4)',
                borderWidth: {
                    base: 5,
                    md: 7,
                },
                borderTopRadius: 3,
                borderBottom: 'none',
                ':nth-child(even)': {
                    mt: `min(${GRID_BOX_SIZE * 0.6}px, ${GRID_BOX_VW_SIZE * 0.6}vw)`,
                    mb: `min(${GRID_BOX_SIZE * 0.2}px, ${GRID_BOX_VW_SIZE * 0.2}vw)`,
                },
            }}
        />
    );
}
