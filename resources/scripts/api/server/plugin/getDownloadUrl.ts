import http from '@/api/http';
import { Source, Plugin } from '@/components/server/plugin/types';
import getVersions from './getVersions';

/**
 * Gets the url to download the plugin
 * @param id The id of the plugin
 * @param source The source of the plugin
 * @param version The version of the plugin
 * @param token Polymart auth token for premium resources
 * @returns Download url for the plugin
 */
export default function (
    plugin: Plugin, //Only for Modrinth currently
    id: number | string,
    source: Source,
    version = -1,
    token: string | null = null
): Promise<string> {
    return new Promise((resolve, reject) => {
        switch (source) {
            case Source.Polymart: {
                if (version > -1) {
                    resolve(`https://polymart.org/r/${id}/updates?update=${version}`);
                } else if (token === null) {
                    http.get(`https://api.polymart.org/v1/getDownloadURL?resource_id=${id}`, { withCredentials: false })
                        .then(
                            ({
                                data: {
                                    response: {
                                        result: { url },
                                    },
                                },
                            }) => {
                                resolve(url);
                            }
                        )
                        .catch(reject);
                } else {
                    http.post(
                        `https://api.polymart.org/v1/getDownloadURL`,
                        { resource_id: id, token: token },
                        { withCredentials: false }
                    )
                        .then(
                            ({
                                data: {
                                    response: {
                                        result: { url },
                                    },
                                },
                            }) => {
                                resolve(url);
                            }
                        )
                        .catch(reject);
                }
                break;
            }
            case Source.Spigot: {
                if (version > -1) {
                    resolve(`https://spigotmc.org/resources/${id}/download?version=${version}`);
                } else {
                    resolve(`https://cdn.spiget.org/file/spiget-resources/${id}.jar`);
                }
                break;
            }
            case Source.Modrinth: {
                getVersions(plugin)
                    .then((versions) => {
                        resolve(versions[0].downloadUrl ? versions[0].downloadUrl.replace('cdn', 'cdn-raw') : '');
                    })
                    .catch(reject);
                break;
            }
        }
    });
}