import http from '@/api/http';

export interface KnowledgebaseCategory {
    id: string;
    name: string;
    description: string;
    created_at: string;
    updated_at: string;
}

export const rawDataToKnowledgebaseCategory = (data: any): KnowledgebaseCategory => ({
    id: data.id,
    name: data.name,
    description: data.description,
    created_at: data.created_at,
    updated_at: data.updated_at,
});

export default (): Promise<KnowledgebaseCategory[]> => {
    return new Promise((resolve, reject) => {
        http.get(`/api/client/knowledgebase/categories`)
            .then((response) => resolve((response.data || []).map((item: any) => rawDataToKnowledgebaseCategory(item))))
            .catch(reject);
    });
};
