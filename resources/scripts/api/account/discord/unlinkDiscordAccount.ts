import http from '@/api/http';

export default async (): Promise<void> => {
    return new Promise((resolve, reject) => {
        http.post('/api/client/account/discord/unlink')
            .then(() => resolve())
            .catch(reject);
    });
};
