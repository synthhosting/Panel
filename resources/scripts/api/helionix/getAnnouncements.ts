import http from '@/api/http';

interface Announcement {
    id: number;
    title: string;
    description: string;
    created_at: string;
}

export default async (): Promise<Announcement[]> => {
    return new Promise((resolve, reject) => {
        http.get('/api/client/announcements', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(({ data }) => {
            resolve((data.data.announcements || []).map((datum: any) => ({
                id: datum.id,
                title: datum.title,
                description: datum.description,
                created_at: datum.created_at,
            })));
        })
        .catch(reject);
    });
};