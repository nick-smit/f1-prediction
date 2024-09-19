export type Action = 'import-results' | 'calculate-scores';

export default function getActionText(action: Action): string {
    switch (action) {
        case 'import-results':
            return 'The results are not imported yet.';
        case 'calculate-scores':
            return 'Not all scores have been calculated.';
        default:
            return 'Invalid action';
    }
}
