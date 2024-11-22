import http from '@/api/http';

export interface Schedule {
    id: number;
    name: string;
    cron: {
        dayOfWeek: string;
        month: string;
        dayOfMonth: string;
        hour: string;
        minute: string;
    };
    isActive: boolean;
    isProcessing: boolean;
    onlyWhenOnline: boolean;
    lastRunAt: Date | null;
    nextRunAt: Date | null;
    createdAt: Date;
    updatedAt: Date;

    tasks: Task[];

    serverTimezone: string;
    panelTimezone: string;
}

export interface Task {
    id: number;
    sequenceId: number;
    action: string;
    payload: string;
    timeOffset: number;
    isQueued: boolean;
    continueOnFailure: boolean;
    createdAt: Date;
    updatedAt: Date;
    serverTimezone: string;
    panelTimezone: string;
}

export const rawDataToServerTask = (data: any): Task => ({
    id: data.id,
    sequenceId: data.sequence_id,
    action: data.action,
    payload: data.payload,
    timeOffset: data.time_offset,
    isQueued: data.is_queued,
    continueOnFailure: data.continue_on_failure,
    createdAt: new Date(data.created_at),
    updatedAt: new Date(data.updated_at),
    serverTimezone: data.server_timezone,
    panelTimezone: data.panel_timezone,
});

export const rawDataToServerSchedule = (data: any): Schedule => {
    const serverTimezone = data.server_timezone;
    const panelTimezone = data.panel_timezone;

    const calculateHourOffsetAsString = (serverTimezone: string, panelTimezone: string): string => {
        const serverOffset = new Date().toLocaleString('en-US', { timeZone: serverTimezone, hour: 'numeric', hour12: false });
        const panelOffset = new Date().toLocaleString('en-US', { timeZone: panelTimezone, hour: 'numeric', hour12: false });
        const hourOffset = parseInt(serverOffset) - parseInt(panelOffset);
        return hourOffset.toString();
    };
    
    const hourOffset = parseInt(calculateHourOffsetAsString(serverTimezone, panelTimezone));
    
    const adjustCronHour = (hour: string): string => {
        if (hour.includes(',')) {
            return hour.split(',').map(part => {
                let adjustedHour = parseInt(part) + hourOffset;
                adjustedHour = adjustedHour < 0 ? 24 + adjustedHour : adjustedHour % 24; // Wrap around the clock
                return adjustedHour.toString();
            }).join(',');
        }
        
        if (hour.includes('-')) {
            const parts = hour.split('-').map(part => {
                let adjustedHour = parseInt(part) + hourOffset;
                adjustedHour = adjustedHour < 0 ? 24 + adjustedHour : adjustedHour % 24;
                return adjustedHour.toString();
            });
            return parts.join('-');
        }

        if (/[\*\/,]/.test(hour)) {
            return hour;
        }
        let adjustedHour = parseInt(hour) + hourOffset;
        if (adjustedHour < 0) {
            adjustedHour += 24;
        } else if (adjustedHour >= 24) {
            adjustedHour %= 24;
        }
        return adjustedHour.toString();
    };

    const convertToServerTimezone = (timestamp: string | null): Date | null => {
        if (!timestamp) return null;

        const date = new Date(timestamp);

        const convertedDate = new Date(date.toLocaleString('en-US', { timeZone: serverTimezone }));

        return convertedDate;
    };

    return {
        id: data.id,
        name: data.name,
        cron: {
            dayOfWeek: data.cron.day_of_week,
            month: data.cron.month,
            dayOfMonth: data.cron.day_of_month,
            hour: adjustCronHour(data.cron.hour),
            minute: data.cron.minute,
        },
        isActive: data.is_active,
        isProcessing: data.is_processing,
        onlyWhenOnline: data.only_when_online,
        lastRunAt: convertToServerTimezone(data.last_run_at),
        nextRunAt: convertToServerTimezone(data.next_run_at),
        createdAt: new Date(data.created_at),
        updatedAt: new Date(data.updated_at),
        tasks: (data.relationships?.tasks?.data || []).map((row: any) => rawDataToServerTask(row.attributes)),
        serverTimezone: data.server_timezone,
        panelTimezone: data.panel_timezone,
    };
};

export default async (uuid: string): Promise<Schedule[]> => {
    const { data } = await http.get(`/api/client/servers/${uuid}/schedules`, {
        params: {
            include: ['tasks'],
        },
    });

    return (data.data || []).map((row: any) => rawDataToServerSchedule(row.attributes));
};
