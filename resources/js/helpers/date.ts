import dayjs from 'dayjs';
import localizedFormat from 'dayjs/plugin/localizedFormat';
import duration from 'dayjs/plugin/duration';
import { Date, DateTime } from '@/types';

dayjs.extend(localizedFormat);
dayjs.extend(duration);
export function formatLocalizedDate(date: Date | DateTime) {
    return dayjs(date).format('L');
}

export function formatLocalizedDateTime(datetime: DateTime) {
    return dayjs(datetime).format('L HH:mm');
}

export function formatFullLocalizedDateTime(datetime: DateTime) {
    return dayjs(datetime).format('LLLL');
}

export function formatForInput(value: Date | null) {
    if (!value) {
        return '';
    }

    return dayjs(value).format('YYYY-MM-DD');
}

export function getDuration(toDate: DateTime) {
    return dayjs.duration(dayjs(toDate).diff());
}

export function isInFuture(date: DateTime) {
    return dayjs().isBefore(dayjs(date));
}
