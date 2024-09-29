import dayjs from 'dayjs';
import localizedFormat from 'dayjs/plugin/localizedFormat';

dayjs.extend(localizedFormat);
export function formatLocalizedDate(date: string) {
    return dayjs(date).format('L');
}

export function formatLocalizedDateTime(datetime: string) {
    return dayjs(datetime).format('L HH:mm');
}

export function formatFullLocalizedDateTime(datetime: string) {
    return dayjs(datetime).format('LLLL HH:mm');
}

export function formatForInput(value: string | null) {
    if (!value) {
        return '';
    }

    return dayjs(value).format('YYYY-MM-DD');
}
