import http from '@/api/http';

interface Node {
    name: string;
    fqdn: string;
    port: number;
    memory_all: number;
    memory_use: number;
    disk_all: number;
    disk_use: number;
    uptime_duration: number;
}

export default async (): Promise<Node[]> => {
    return new Promise((resolve, reject) => {
        http.get('/api/client/uptimes', {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            }
        })
        .then(({ data }) => {
            resolve((data.data || []).map((datum: any) => ({
                name: datum.name,
                fqdn: datum.fqdn,
                port: datum.port,
                memory_all: datum.memory,
                memory_use: datum.allocated_resources.memory,
                disk_all: datum.disk,
                disk_use: datum.allocated_resources.disk,
                uptime_duration: datum.uptime_duration,
            })));
        })
        .catch(reject);
    });
};