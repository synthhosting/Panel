import http from '@/api/http';
import { Plugin } from '@/api/server/rustplugins/search';

export default (uuid: string, directory: string, url: string, data?: Plugin, update?: boolean): Promise<void> => {
    return new Promise((resolve, reject) => {
        http.post(`/api/client/servers/${uuid}/rustplugins/install`, {
            url,
            directory,
            data: data
                ? {
                      url: data.url,
                      title: data.title,
                      name: data.name,
                      tags_all: data.tagsAll,
                      icon_url: data.iconUrl,
                      author: data.author,
                      downloads_shortened: data.downloadsShortened,
                      donate_url: data.donateUrl,
                      version: data.latestReleaseVersionFormatted,
                      update,
                  }
                : null,
        })
            .then(() => resolve())
            .catch(reject);
    });
};