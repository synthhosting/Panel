import http from '@/api/http';

export interface ModResponse {
    data: Mod[];
    pagination: {
        totalCount: number;
    };
}

export interface Mod {
    id: number;
    name: string;
    summary: string;
    downloadCount: number;
    links: {
        websiteUrl: string;
    };
    logo: {
        url: string;
    };
    authors: {
        name: string;
    }[];
    latestFiles: {
        fileName: string;
    }[];
    mainFileId: number;
    dateCreated: Date;
    dateModified: Date;
}

export default (uuid: string, search: string): Promise<ModResponse> => {
    return new Promise((resolve, reject) => {
        http.post(`/api/client/servers/${uuid}/ark/mods`, { search })
            .then(({ data }) => {
                resolve(data || []);
            })
            .catch(reject);
    });
};