import http from '@/api/http';

export interface KnowledgebaseTopic {
    id: string;
    author: string;
    subject: string;
    information: string;
    category: string;
    created_at: string;
    updated_at: string;
}

export const rawDataToKnowledgebaseTopic = (data: any): KnowledgebaseTopic => ({
    id: data.id,
    author: data.author,
    subject: data.subject,
    information: data.information,
    category: data.category,
    created_at: data.created_at,
    updated_at: data.updated_at,
});

export default (id: string): Promise<KnowledgebaseTopic> => {
    return new Promise((resolve, reject) => {
        http.get(`/api/client/knowledgebase/topic/${id}`)
            .then((response) => resolve(rawDataToKnowledgebaseTopic(response.data)))
            .catch(reject);
    });
};
