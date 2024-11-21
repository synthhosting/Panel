import React, { useEffect, useState } from 'react';
import getNodes from '@/api/helionix/getNodes';
import PageContentBlock from '@/components/elements/PageContentBlock';
import tw from 'twin.macro';
import { useStoreState } from "easy-peasy";
import { ApplicationStore } from "@/state";
import LcIcon from '@/components/elements/LcIcon';
import { HardDrive, MemoryStick, Moon, Sun } from 'lucide-react';

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

const formatUptime = (seconds: number) => {
    const days = Math.floor(seconds / (24 * 3600));
    const hours = Math.floor((seconds % (24 * 3600)) / 3600);
    const minutes = Math.floor((seconds % 3600) / 60);
    return `${days}d ${hours}h ${minutes}m`;
};

export default () => {
    const unit = useStoreState((state: ApplicationStore) => state.helionix.data!.uptime_nodes_unit);
    const [nodes, setNodes] = useState<Node[]>([]);
    const [loading, setLoading] = useState(true);

    const fetchNodes = async () => {
        try {
            const allNodes = await getNodes();
            const nodeData = allNodes.map(node => ({
                ...node,
                uptime_duration: node.uptime_duration || 0,
            }));
            setNodes(nodeData);
        } catch (error) {
            console.error(error);
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        fetchNodes();
        const intervalId = setInterval(fetchNodes, 60000);
        return () => clearInterval(intervalId);
    }, []);

    return (
        <PageContentBlock title={'Uptime Nodes'}>
            <div css={tw`w-full`}>
                <div css={tw`grid grid-cols-1 gap-2 mb-2`}>
                    {loading ? (
                        <div css={tw`flex items-center justify-center`}>
                            <p>Loading... Please Wait</p>
                        </div>
                    ) : (
                        nodes.length > 0 ? (
                            nodes.map((node, index) => (
                                <div key={index} css={[tw`sm:block xl:grid grid-cols-12 gap-4 w-full h-auto bg-helionix-color2 rounded-xl p-4 shadow-lg border-b-4`, node.uptime_duration > 0 ? tw`border-helionix-btnPrimary` : tw`border-helionix-btnDanger`]}>
                                    <div css={tw`flex items-center col-span-8`}>
                                        {node.uptime_duration > 0 ? (
                                            <div css={tw`w-auto bg-helionix-btnPrimary p-2 rounded-full mr-4`}>
                                                <LcIcon icon={Sun} size={24} />
                                            </div>
                                        ) : (
                                            <div css={tw`w-auto bg-helionix-btnDanger p-2 rounded-full mr-4`}>
                                                <LcIcon icon={Moon} size={24} />
                                            </div>
                                        )}
                                        <div>
                                            <p css={tw`text-lg break-words`}>{node.name}</p>
                                            <span css={tw`text-sm break-words line-clamp-1`}>
                                                {node.uptime_duration > 0 ? `Node is up for ${formatUptime(node.uptime_duration)}` : 'N/A or Node is down'}
                                            </span>
                                        </div>
                                    </div>
                                    <div css={tw`items-center col-span-4 mt-4 xl:mt-0`}>
                                        <div css={tw`grid grid-cols-2 gap-4`}>
                                            <div css={tw`bg-helionix-color3 p-3 rounded-xl`}>
                                                <div css={tw`flex items-center`}>
                                                    <LcIcon icon={MemoryStick} size={16}/>
                                                    <p css={tw`ml-2 uppercase font-semibold`}>Memory</p>
                                                </div>
                                                {unit === 'percent' &&
                                                    <p css={tw`font-semibold break-words`}>{((node.memory_use / node.memory_all) * 100).toFixed(2)}%</p>
                                                }
                                                {unit === 'mb' &&
                                                    <p css={tw`font-semibold break-words`}>{node.memory_use} MiB / {node.memory_all} MiB</p>
                                                }
                                                {unit === 'gb' &&
                                                    <p css={tw`font-semibold break-words`}>{(node.memory_use / 1024).toFixed(2)} GiB / {(node.memory_all / 1024).toFixed(2)} GiB</p>
                                                }
                                                {unit === 'tb' &&
                                                    <p css={tw`font-semibold break-words`}>{(node.memory_use / (1024 * 1024)).toFixed(2)} TiB / {(node.memory_all / (1024 * 1024)).toFixed(2)} TiB</p>
                                                }
                                                {unit === 'none' &&
                                                    <p css={tw`font-semibold break-words`}>N/A</p>
                                                }
                                            </div>
                                            <div css={tw`bg-helionix-color3 p-3 rounded-xl`}>
                                                <div css={tw`flex items-center`}>
                                                    <LcIcon icon={HardDrive} size={16} />
                                                    <p css={tw`ml-2 uppercase font-semibold`}>Disk</p>
                                                </div>
                                                {unit === 'percent' &&
                                                    <p css={tw`font-semibold break-words`}>{((node.disk_use / node.disk_all) * 100).toFixed(2)}%</p>
                                                }
                                                {unit === 'mb' &&
                                                    <p css={tw`font-semibold break-words`}>{node.disk_use} MiB / {node.disk_all} MiB</p>
                                                }
                                                {unit === 'gb' &&
                                                    <p css={tw`font-semibold break-words`}>{(node.disk_use / 1024).toFixed(2)} GiB / {(node.disk_all / 1024).toFixed(2)} GiB</p>
                                                }
                                                {unit === 'tb' &&
                                                    <p css={tw`font-semibold break-words`}>{(node.disk_use / (1024 * 1024)).toFixed(2)} TiB / {(node.disk_all / (1024 * 1024)).toFixed(2)} TiB</p>
                                                }
                                                {unit === 'none' &&
                                                    <p css={tw`font-semibold break-words`}>N/A</p>
                                                }
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            <div css={tw`flex items-center justify-center`}>
                                <p>No Nodes Found</p>
                            </div>
                        )
                    )}
                </div>
            </div>
        </PageContentBlock>
    );
};
