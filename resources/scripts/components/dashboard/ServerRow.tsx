import React, { memo, useEffect, useRef, useState } from 'react';
import { Link } from 'react-router-dom';
import { Server } from '@/api/server/getServer';
import getServerResourceUsage, { ServerPowerState, ServerStats } from '@/api/server/getServerResourceUsage';
import { bytesToString, ip, mbToBytes } from '@/lib/formatters';
import tw from 'twin.macro';
import CopyOnClick from "@/components/elements/CopyOnClick";
import GreyRowBox from '@/components/elements/GreyRowBox';
import Spinner from '@/components/elements/Spinner';
import styled from 'styled-components/macro';
import isEqual from 'react-fast-compare';
import { useStoreState } from "easy-peasy";
import { ApplicationStore } from "@/state";
import { Cpu, Fingerprint, HardDrive, MemoryStick, ServerCog } from 'lucide-react';
import LcIcon from '@/components/elements/LcIcon';

// Determines if the current value is in an alarm threshold so we can show it in red rather
// than the more faded default style.
const isAlarmState = (current: number, limit: number): boolean => limit > 0 && current / (limit * 1024 * 1024) >= 0.9;

const StatusIndicatorBox = styled(GreyRowBox)<{ $status: ServerPowerState | undefined }>`
    ${tw`grid grid-cols-12 gap-4 relative`};

    & .status-bar {
        ${tw`w-4 bg-red-500 absolute right-[5px] z-20 rounded-full m-1 opacity-50 transition-all duration-150`};
        height: calc(100% - 1rem);

        ${({ $status }) =>
            !$status || $status === 'offline'
                ? tw`bg-red-500`
                : $status === 'running'
                ? tw`bg-green-500`
                : tw`bg-yellow-500`};
    }

    &:hover .status-bar {
        ${tw`opacity-75`};
    }
`;

type Timer = ReturnType<typeof setInterval>;

export default ({ server, className }: { server: Server; className?: string }) => {
    const layout = useStoreState((state: ApplicationStore) => state.helionix.data!.dash_layout);
    const interval = useRef<Timer>(null) as React.MutableRefObject<Timer>;
    const [isSuspended, setIsSuspended] = useState(server.status === 'suspended');
    const [stats, setStats] = useState<ServerStats | null>(null);

    const getStats = () =>
        getServerResourceUsage(server.uuid)
            .then((data) => setStats(data))
            .catch((error) => console.error(error));

    useEffect(() => {
        setIsSuspended(stats?.isSuspended || server.status === 'suspended');
    }, [stats?.isSuspended, server.status]);

    useEffect(() => {
        // Don't waste a HTTP request if there is nothing important to show to the user because
        // the server is suspended.
        if (isSuspended) return;

        getStats().then(() => {
            interval.current = setInterval(() => getStats(), 30000);
        });

        return () => {
            interval.current && clearInterval(interval.current);
        };
    }, [isSuspended]);

    const alarms = { cpu: false, memory: false, disk: false };
    if (stats) {
        alarms.cpu = server.limits.cpu === 0 ? false : stats.cpuUsagePercent >= server.limits.cpu * 0.9;
        alarms.memory = isAlarmState(stats.memoryUsageInBytes, server.limits.memory);
        alarms.disk = server.limits.disk === 0 ? false : isAlarmState(stats.diskUsageInBytes, server.limits.disk);
    }

    const diskLimit = server.limits.disk !== 0 ? bytesToString(mbToBytes(server.limits.disk)) : 'Unlimited';
    const memoryLimit = server.limits.memory !== 0 ? bytesToString(mbToBytes(server.limits.memory)) : 'Unlimited';
    const cpuLimit = server.limits.cpu !== 0 ? server.limits.cpu + ' %' : 'Unlimited';

    return (
        <>
        {layout == 1 ?
            <div className="backdrop rounded-xl overflow-hidden flex flex-col" css={'background-color:var(--color-3);'}>
                <div className={'bg-center bg-cover bg-no-repeat relative px-6 pt-5 z-10 flex-1'} css={`background-image:url(${server.eggImage ? server.eggImage : 'https://cdn.flydev.one/images/helionix/dashboard/server_bg.png'})`}>
                    <div className={'z-[-1] absolute inset-0'} css={'background-image:linear-gradient(0deg, var(--color-3) 0%, color-mix(in srgb, var(--color-3) 0%, transparent) 100%);'}/>
                    <div className="flex items-center justify-between pb-5">
                        <div>
                            <p className="text-lg font-semibold">{server.name}</p>
                            <p className="text-base">
                                {server.allocations
                                    .filter((alloc) => alloc.isDefault)
                                    .map((allocation) => (
                                        <React.Fragment key={allocation.ip + allocation.port.toString()}>
                                            {allocation.alias || allocation.ip}:{allocation.port}
                                        </React.Fragment>
                                    ))
                                }
                            </p>
                        </div>
                        <span className={`py-1 px-2 rounded-lg
                            ${stats?.status === 'offline'
                                ? 'bg-helionix-btnDanger'
                                : stats?.status === 'running' 
                                ? 'bg-helionix-btnPrimary'
                                : stats?.status === 'starting' 
                                ? 'bg-helionix-btnSecondary'
                                : stats?.status === 'stopping'
                                ? 'bg-helionix-btnDangerHover'
                                : ''
                            }
                        `}>
                            {stats?.status === 'offline'
                                ? 'offline'
                                : stats?.status === 'running'
                                ? 'online'
                                : stats?.status === 'starting'
                                ? 'starting'
                                : stats?.status === 'stopping'
                                ? 'stopping'
                                : ''
                            }
                        </span>
                    </div>
                    {!stats || isSuspended ? (
                        isSuspended ? (
                            <div className="flex justify-center">
                                <span css={tw`bg-helionix-btnDanger rounded-lg px-2 py-1`}>
                                    {server.status === 'suspended' ? 'suspended' : 'connection-error'}
                                </span>
                            </div>
                        ) : server.isTransferring || server.status ? (
                            <div className="flex justify-center">
                                <span css={tw`bg-helionix-btnSecondary rounded-lg px-2 py-1`}>
                                    {server.isTransferring
                                        ? 'transferring'
                                        : server.status === 'installing'
                                        ? 'installing'
                                        : server.status === 'restoring_backup'
                                        ? 'restoring-backup'
                                        : 'unavailable'
                                    }
                                </span>
                            </div>
                        ) : (
                            <div className="flex justify-center">
                                <Spinner size={'small'} />
                            </div>
                        )
                    ) : (
                    <div className="grid md:grid-cols-2 lg:grid-cols-none xl:grid-cols-2 gap-2 mt-4 flex-1">
                        <div className="flex items-center gap-1">
                            <LcIcon icon={Fingerprint} size={14} />
                            <span className="text-sm font-semibold">ID:</span>
                            <p>{server.id}</p>
                        </div>
                        <div className="flex items-center gap-1">
                            <LcIcon icon={Cpu} size={14} />
                            <span className="text-sm font-semibold">CPU:</span>
                            <p className={alarms.cpu ? 'text-helionix-btnDanger' : ''}>{stats.cpuUsagePercent.toFixed(2)}%</p>
                            <span className="text-sm">/ {cpuLimit}</span>
                        </div>
                        <div className="flex items-center gap-1">
                            <LcIcon icon={MemoryStick} size={14} />
                            <span className="text-sm font-semibold">RAM:</span>
                            <p className={alarms.memory ? 'text-helionix-btnDanger' : ''}>{bytesToString(stats.memoryUsageInBytes)}</p>
                            <span className="text-sm">/ {memoryLimit}</span>
                        </div>
                        <div className="flex items-center gap-1">
                            <LcIcon icon={HardDrive} size={14} />
                            <span className="text-sm font-semibold">DISK:</span>
                            <p className={alarms.disk ? 'text-helionix-btnDanger' : ''}>{bytesToString(stats.diskUsageInBytes)}</p>
                            <span className="text-sm">/ {diskLimit}</span>
                        </div>
                    </div>
                    )}
                </div>
                <div className={'px-6 pt-4 pb-5'}>
                    <Link to={`/server/${server.id}`} className={'w-full block p-3 border-2 bg-helionix-btnPrimary hover:bg-helionix-btnPrimaryHover border-helionix-btnPrimaryHover hover:border-helionix-btnPrimary rounded-xl text-center duration-300'}>
                        Manage Server
                    </Link>
                </div>
            </div>
            : layout == 2 ?
            <a href={`/server/${server.id}`} className={'bg-helionix-color3 rounded-xl overflow-hidden flex flex-col hover:border-helionix-btnPrimaryHover border-2 border-transparent transition-colors duration-150'}>
                <div className={'bg-center bg-cover bg-no-repeat relative p-6 z-10 flex-1'} css={`background: linear-gradient(0deg, var(--color-2) 30%, var(--color-3) 70%);`}>
                    <div className="flex items-center justify-between pb-5">
                        <div>
                            <p className="text-lg font-semibold">{server.name}</p>
                            <p className="text-base">
                                {server.allocations
                                    .filter((alloc) => alloc.isDefault)
                                    .map((allocation) => (
                                        <React.Fragment key={allocation.ip + allocation.port.toString()}>
                                            {allocation.alias || allocation.ip}:{allocation.port}
                                        </React.Fragment>
                                    ))
                                }
                            </p>
                        </div>
                        <span className={`py-1 px-2 rounded-lg
                            ${stats?.status === 'offline'
                                ? 'bg-helionix-btnDanger'
                                : stats?.status === 'running' 
                                ? 'bg-helionix-btnPrimary'
                                : stats?.status === 'starting' 
                                ? 'bg-helionix-btnSecondary'
                                : stats?.status === 'stopping'
                                ? 'bg-helionix-btnDangerHover'
                                : ''
                            }
                        `}>
                            {stats?.status === 'offline'
                                ? 'offline'
                                : stats?.status === 'running'
                                ? 'online'
                                : stats?.status === 'starting'
                                ? 'starting'
                                : stats?.status === 'stopping'
                                ? 'stopping'
                                : ''
                            }
                        </span>
                    </div>
                    {!stats || isSuspended ? (
                        isSuspended ? (
                            <div className="flex justify-center">
                                <span css={tw`bg-helionix-btnDanger rounded-lg px-2 py-1`}>
                                    {server.status === 'suspended' ? 'suspended' : 'connection-error'}
                                </span>
                            </div>
                        ) : server.isTransferring || server.status ? (
                            <div className="flex justify-center">
                                <span css={tw`bg-helionix-btnSecondary rounded-lg px-2 py-1`}>
                                    {server.isTransferring
                                        ? 'transferring'
                                        : server.status === 'installing'
                                        ? 'installing'
                                        : server.status === 'restoring_backup'
                                        ? 'restoring-backup'
                                        : 'unavailable'
                                    }
                                </span>
                            </div>
                        ) : (
                            <div className="flex justify-center">
                                <Spinner size={'small'} />
                            </div>
                        )
                    ) : (
                    <div className="grid md:grid-cols-2 lg:grid-cols-none xl:grid-cols-2 gap-2 mt-4 flex-1">
                        <div className="flex items-center gap-1">
                            <LcIcon icon={Fingerprint} size={14} />
                            <span className="text-sm font-semibold">ID:</span>
                            <p>{server.id}</p>
                        </div>
                        <div className="flex items-center gap-1">
                            <LcIcon icon={Cpu} size={14} />
                            <span className="text-sm font-semibold">CPU:</span>
                            <p className={alarms.cpu ? 'text-helionix-btnDanger' : ''}>{stats.cpuUsagePercent.toFixed(2)}%</p>
                            <span className="text-sm">/ {cpuLimit}</span>
                        </div>
                        <div className="flex items-center gap-1">
                            <LcIcon icon={MemoryStick} size={14} />
                            <span className="text-sm font-semibold">RAM:</span>
                            <p className={alarms.memory ? 'text-helionix-btnDanger' : ''}>{bytesToString(stats.memoryUsageInBytes)}</p>
                            <span className="text-sm">/ {memoryLimit}</span>
                        </div>
                        <div className="flex items-center gap-1">
                            <LcIcon icon={HardDrive} size={14} />
                            <span className="text-sm font-semibold">DISK:</span>
                            <p className={alarms.disk ? 'text-helionix-btnDanger' : ''}>{bytesToString(stats.diskUsageInBytes)}</p>
                            <span className="text-sm">/ {diskLimit}</span>
                        </div>
                    </div>
                    )}
                </div>
            </a>
            :
            <StatusIndicatorBox as={Link} to={`/server/${server.id}`} className={className} $status={stats?.status}>
                <div css={tw`flex items-center col-span-12 sm:col-span-5 lg:col-span-6`}>
                    <div className={'!w-auto !bg-helionix-color3 icon mr-4'}>
                        <LcIcon icon={ServerCog} size={16} />
                    </div>
                    <div>
                        <p css={tw`text-lg break-words`}>{server.name}</p>
                        {!!server.description && (
                            <span css={tw`text-sm break-words line-clamp-2`}>{server.description}</span>
                        )}
                    </div>
                </div>
                <div css={tw`flex-1 ml-4 xl:block xl:col-span-2 hidden`}>
                    <div css={tw`flex justify-center items-center`}>
                        <span css={tw`text-sm ml-2`}>
                            {server.allocations
                                .filter((alloc) => alloc.isDefault)
                                .map((allocation) => (
                                    <React.Fragment key={allocation.ip + allocation.port.toString()}>
                                        {allocation.alias || ip(allocation.ip)}:{allocation.port}
                                    </React.Fragment>
                                ))
                            }
                        </span>
                    </div>
                </div>
                <div css={tw`hidden col-span-7 lg:col-span-4 sm:flex items-baseline justify-center`}>
                    {!stats || isSuspended ? (
                        isSuspended ? (
                            <div css={tw`flex-1 text-center`}>
                                <span css={tw`bg-helionix-btnDanger rounded-lg px-2 py-1 text-xs`}>
                                    {server.status === 'suspended' ? 'Suspended' : 'Connection Error'}
                                </span>
                            </div>
                        ) : server.isTransferring || server.status ? (
                            <div css={tw`flex-1 text-center`}>
                                <span css={tw`bg-helionix-btnSecondary rounded-lg px-2 py-1 text-xs`}>
                                    {server.isTransferring
                                        ? 'Transferring'
                                        : server.status === 'installing'
                                        ? 'Installing'
                                        : server.status === 'restoring_backup'
                                        ? 'Restoring Backup'
                                        : 'Unavailable'}
                                </span>
                            </div>
                        ) : (
                            <Spinner size={'small'} />
                        )
                    ) : (
                        <React.Fragment>
                            <div css={tw`flex-1 ml-4 xl:block hidden`}>
                                <div css={tw`flex items-center`}>
                                    <LcIcon icon={Cpu} size={16}/>
                                    <p className={alarms.cpu ? 'text-helionix-btnDanger ml-2' : 'ml-2'}>{stats.cpuUsagePercent.toFixed(2)}%</p>
                                </div>
                                <span css={tw`text-xs text-center mt-1`}>of {cpuLimit}</span>
                            </div>
                            <div css={tw`flex-1 ml-4 xl:block hidden`}>
                                <div css={tw`flex items-center`}>
                                    <LcIcon icon={MemoryStick} size={16}/>
                                    <p className={alarms.memory ? 'text-helionix-btnDanger ml-2' : 'ml-2'}>{bytesToString(stats.memoryUsageInBytes)}</p>
                                </div>
                                <span css={tw`text-xs text-center mt-1`}>of {memoryLimit}</span>
                            </div>
                            <div css={tw`flex-1 ml-4 xl:block hidden`}>
                                <div css={tw`flex items-center`}>
                                    <LcIcon icon={HardDrive} size={16}/>
                                    <p className={alarms.disk ? 'text-helionix-btnDanger ml-2' : 'ml-2'}>{bytesToString(stats.diskUsageInBytes)}</p>
                                </div>
                                <span css={tw`text-xs text-center mt-1`}>of {diskLimit}</span>
                            </div>
                        </React.Fragment>
                    )}
                </div>
                <div className={'status-bar'} />
            </StatusIndicatorBox>
        }
        </>
    );
};
