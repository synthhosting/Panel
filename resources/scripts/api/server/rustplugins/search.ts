import http, { PaginatedResult } from '@/api/http';

export interface Plugin {
    author: string;
    authorIconUrl: string;
    authorId: string;
    categoryTags: string;
    createdAt: Date;
    createdAtAtom: Date;
    description: string;
    distribution: string;
    donateUrl: string;
    downloadUrl: string;
    downloads: number;
    downloadsShortened: string;
    iconUrl: string;
    jsonUrl: string;
    latestReleaseAt: Date;
    latestReleaseAtAtom: Date;
    latestReleaseVersion: string;
    latestReleaseVersionChecksum: string;
    latestReleaseVersionFormatted: string;
    name: string;
    publishedAt: Date;
    slug: string;
    tagsAll: string;
    title: string;
    updatedAt: Date;
    updatedAtAtom: Date;
    url: string;
    watchers: number;
    watchersShortened: string;
}

const rawDataToPlugin = (attributes: any): Plugin => ({
    author: attributes.author,
    authorIconUrl: attributes.author_icon_url,
    authorId: attributes.author_id,
    categoryTags: attributes.category_tags,
    createdAt: attributes.created_at,
    createdAtAtom: attributes.created_at_atom,
    description: attributes.description,
    distribution: attributes.distribution,
    donateUrl: attributes.donate_url,
    downloadUrl: attributes.download_url,
    downloads: attributes.downloads,
    downloadsShortened: attributes.downloads_shortened,
    iconUrl: attributes.icon_url,
    jsonUrl: attributes.json_url,
    latestReleaseAt: attributes.latest_release_at,
    latestReleaseAtAtom: attributes.latest_release_at_atom,
    latestReleaseVersion: attributes.latest_release_version,
    latestReleaseVersionChecksum: attributes.latest_release_version_checksum,
    latestReleaseVersionFormatted: attributes.latest_release_version_formatted ?? attributes.version,
    name: attributes.name,
    publishedAt: attributes.published_at,
    slug: attributes.slug,
    tagsAll: attributes.tags_all,
    title: attributes.title,
    updatedAt: attributes.updated_at,
    updatedAtAtom: attributes.updated_at_atom,
    url: attributes.url,
    watchers: attributes.watchers,
    watchersShortened: attributes.watchers_shortened,
});

export default (uuid: string, search: string, page: number, installed: boolean): Promise<PaginatedResult<Plugin>> => {
    return new Promise((resolve, reject) => {
        http.post(`/api/client/servers/${uuid}/rustplugins`, { search, page, installed })
            .then(({ data }) => {
                resolve({
                    items: (data.data || []).map(rawDataToPlugin),
                    pagination: {
                        total: installed ? 1 : data.last_page,
                        count: installed ? 1 : data.last_page,
                        perPage: installed ? 999 : data.per_page,
                        currentPage: installed ? 1 : data.current_page,
                        totalPages: installed ? 1 : data.last_page,
                    },
                });
            })
            .catch(reject);
    });
};