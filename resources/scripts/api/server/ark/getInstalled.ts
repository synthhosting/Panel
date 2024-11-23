import http from '@/api/http';
import { ModResponse } from '@/api/server/ark/getMods';

export default (uuid: string, search: string): Promise<ModResponse> => {
    return new Promise((resolve, reject) => {
        http.post(`/api/client/servers/${uuid}/ark/installed`, { search })
            .then(({ data }) => {
                resolve(data || []);
            })
            .catch(reject);
    });
};