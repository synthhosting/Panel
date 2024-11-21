import http from '@/api/http';
import { DowntimeResponse } from '@/components/server/console/ServerConsoleContainer';

export default async (uuid: string): Promise<DowntimeResponse> => {
    const { data } = await http.get(`/api/client/servers/${uuid}/downtime`);

    return data || [];
};
