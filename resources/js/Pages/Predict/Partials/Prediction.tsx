import { Driver } from '@/types';
import React from 'react';
import {
    Box,
    Divider,
    Flex,
    IconButton,
    List,
    ListItem,
    OrderedList,
    Stack,
} from '@chakra-ui/react';
import usePrediction from '@/Pages/Predict/hooks/usePrediction';
import { SmallCloseIcon } from '@chakra-ui/icons';
import { closestCenter, DndContext } from '@dnd-kit/core';
import {
    SortableContext,
    verticalListSortingStrategy,
} from '@dnd-kit/sortable';
import SortableItem from '@/Components/SortableItem';

type Props = {
    sessionId: number;
    prediction: Driver[];
    drivers: Driver[];
};
export default function Prediction(props: Props) {
    const { sessionId, drivers } = props;
    const {
        prediction,
        selectableDrivers,
        handleAddDriver,
        handleRemoveDriver,

        dndSensors,
        handleDragEnd,
    } = usePrediction(sessionId, props.prediction, drivers);

    return (
        <Stack bg={'dark.1'}>
            <OrderedList variant="prediction">
                <DndContext
                    sensors={dndSensors}
                    collisionDetection={closestCenter}
                    onDragEnd={handleDragEnd}
                >
                    <SortableContext
                        items={prediction}
                        strategy={verticalListSortingStrategy}
                    >
                        {prediction.map((driver) => (
                            <ListItem key={driver.id}>
                                <Flex justify="space-between" align="center">
                                    <SortableItem id={driver.id}>
                                        {driver.name}
                                    </SortableItem>
                                    <IconButton
                                        onClick={() =>
                                            handleRemoveDriver(driver)
                                        }
                                        aria-label={`Remove ${driver.name} from prediction`}
                                        variant="action"
                                        icon={<SmallCloseIcon />}
                                    />
                                </Flex>
                            </ListItem>
                        ))}
                    </SortableContext>
                </DndContext>
            </OrderedList>
            <Divider />
            <List variant="prediction" pb={2}>
                {selectableDrivers.map((driver) => (
                    <ListItem ml={4} key={driver.id}>
                        <Box
                            as="button"
                            w={'100%'}
                            textAlign={'left'}
                            onClick={() => handleAddDriver(driver)}
                        >
                            {driver.name}
                        </Box>
                    </ListItem>
                ))}
            </List>
            ;
        </Stack>
    );
}
