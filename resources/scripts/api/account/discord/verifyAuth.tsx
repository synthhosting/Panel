import http from '@/api/http';

export const verifyAuth = (state: string, code: string): Promise<void> => {
    return new Promise((resolve, reject) => {
        http.post('/api/client/account/discord/verify', { state, code })
            .then(() => resolve())
            .catch(reject);
    });
};

export const unlinkDiscordAccount = (): Promise<void> => {
    return new Promise((resolve, reject) => {
        http.post('/api/client/account/discord/unlink')
            .then(() => resolve())
            .catch(reject);
    });
};

export default verifyAuth;
