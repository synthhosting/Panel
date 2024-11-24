import http from '@/api/http';

export default async (uuid: string, directory: string, query: string): Promise<any> => {
    return new Promise((resolve, reject) => {
        http.post(
            `/api/client/servers/${uuid}/files/search/smart`,
            { root: directory, query },
            {
                timeout: 300000,
                timeoutErrorMessage: 'It looks like this action is taking a long time to search all the files.',
            }
        )
            .then((data) => {
                resolve(data.data || []);
            })
            .catch(reject);
    });
};
