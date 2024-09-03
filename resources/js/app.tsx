import {createInertiaApp} from '@inertiajs/react';
import {createRoot} from 'react-dom/client';

require('bootstrap')

let appName = 'Laravel';
if (
    import.meta.env.VITE_APP_NAME !== undefined &&
    String(import.meta.env.VITE_APP_NAME) !== null
) {
    appName = String(import.meta.env.VITE_APP_NAME);
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        const pages = import.meta.glob('./Pages/**/*.tsx', { eager: true });
        return pages[`./Pages/${name}.tsx`];
    },
    setup({ el, App, props }) {
        const root = createRoot(el);

        root.render(<App {...props} />);
    },
}).catch((error) => {
    console.error(error);
    throw error;
});
