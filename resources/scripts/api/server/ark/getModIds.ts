import http from '@/api/http';

export default (uuid: string): Promise<string> => {
    return new Promise((resolve, reject) => {
        http.post(`/api/client/servers/${uuid}/ark/modids`)
            .then(({ data }) => {
                resolve(data || '');
            })
            .catch(reject);
    });
};