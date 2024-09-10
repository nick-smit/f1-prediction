import { useToast } from '@chakra-ui/react';
import { useEffect } from 'react';

export default function useCheckVerified() {
    const verified = new URLSearchParams(window.location.search).get(
        'verified'
    );
    const toast = useToast();

    useEffect(() => {
        if (verified === '1') {
            toast({
                title: 'Verified email.',
                description: 'Your email address has been verified.',
                status: 'success',
                duration: 5000,
                isClosable: true,
            });
        }
    }, [verified, toast]);
}
