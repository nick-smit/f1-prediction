import { AxiosInstance } from 'axios';
import { Config as ZiggyConfig, route as routeFn } from 'ziggy-js';

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    const route: typeof routeFn;
    const Ziggy: ZiggyConfig;
}
