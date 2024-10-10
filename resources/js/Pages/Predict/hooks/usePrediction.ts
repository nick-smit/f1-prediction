import { Driver } from '@/types';
import { useCallback, useEffect, useMemo, useRef, useState } from 'react';
import { ToastId, useToast } from '@chakra-ui/react';
import axios from 'axios';
import { arrayMove } from '@dnd-kit/sortable';
import {
    DragEndEvent,
    MouseSensor,
    TouchSensor,
    useSensor,
    useSensors,
} from '@dnd-kit/core';

export default function usePrediction(
    sessionId: number,
    currentPrediction: Driver[],
    drivers: Driver[]
) {
    const toast = useToast();
    const warningToastId = useRef<ToastId>(-1);

    const [localPrediction, setLocalPrediction] =
        useState<Driver[]>(currentPrediction);
    const predictionDirty = useRef<boolean>(false);

    const selectableDrivers = useMemo(() => {
        const idsInPrediction = localPrediction.map((driver) => driver.id);

        return drivers.filter((driver) => !idsInPrediction.includes(driver.id));
    }, [localPrediction, drivers]);

    const handleAddDriver = useCallback(
        (driver: Driver) => {
            return setLocalPrediction((prevState) => {
                if (prevState.length === 10) {
                    if (!toast.isActive(warningToastId.current)) {
                        setTimeout(() => {
                            warningToastId.current = toast({
                                isClosable: true,
                                duration: 3000,
                                position: 'bottom',
                                status: 'warning',
                                title: 'A prediction cannot be more than 10 positions',
                                description:
                                    'Please remove a driver before adding another one.',
                            });
                        }, 0);
                    }

                    return prevState;
                }
                predictionDirty.current = true;

                return [...prevState, driver];
            });
        },
        [setLocalPrediction, toast, warningToastId]
    );

    const handleRemoveDriver = useCallback(
        (driver: Driver) => {
            return setLocalPrediction((prevState) => {
                toast.close(warningToastId.current);
                predictionDirty.current = true;

                return prevState.filter((item) => item.id !== driver.id);
            });
        },
        [setLocalPrediction, warningToastId]
    );

    useEffect(() => {
        // Save the prediction if the length is 10 and dirty
        if (localPrediction.length !== 10 || !predictionDirty.current) {
            return;
        }

        axios
            .post(route('prediction.store', { raceSession: sessionId }), {
                prediction: localPrediction.map((driver) => driver.id),
            })
            .then(() =>
                toast({
                    isClosable: true,
                    duration: 3000,
                    position: 'bottom',
                    status: 'success',
                    title: 'Saved!',
                    description: 'Your prediction is saved',
                })
            )
            .catch(() =>
                toast({
                    isClosable: true,
                    duration: 3000,
                    position: 'bottom',
                    status: 'error',
                    title: 'Error',
                    description: 'Your prediction could not be saved',
                })
            );
    }, [localPrediction, sessionId]);

    const dndSensors = useSensors(
        useSensor(MouseSensor, {
            activationConstraint: {
                distance: 8,
            },
        }),
        useSensor(TouchSensor, {
            activationConstraint: {
                distance: 8,
            },
        })
    );

    const handleDragEnd = useCallback(
        (event: DragEndEvent) => {
            if (event.active.id === event.over?.id) {
                return;
            }

            predictionDirty.current = true;
            setLocalPrediction((items) => {
                const oldIndex = items.findIndex(
                    (driver) => driver.id === event.active.id
                );
                const newIndex = items.findIndex(
                    (driver) => driver.id === event.over?.id
                );

                return arrayMove(items, oldIndex, newIndex);
            });
        },
        [setLocalPrediction, predictionDirty]
    );

    return {
        prediction: localPrediction,
        selectableDrivers,
        handleAddDriver,
        handleRemoveDriver,

        dndSensors,
        handleDragEnd,
    };
}
