import React, { useEffect, useMemo, useState } from 'react';
import { useStoreState } from "easy-peasy";
import { ApplicationStore } from "@/state";
import { bytesToString, ip, mbToBytes } from '@/lib/formatters';
import { ServerContext } from '@/state/server';
import { SocketEvent, SocketRequest } from '@/components/server/events';
import UptimeDuration from '@/components/server/UptimeDuration';
import StatBlock from '@/components/server/console/StatBlock';
import useWebsocketEvent from '@/plugins/useWebsocketEvent';
import classNames from 'classnames';
import { capitalize } from '@/lib/strings';
import styles from './style.module.css';
import { AlarmClock, CloudDownload, CloudUpload, Cpu, HardDrive, MemoryStick, Radio } from 'lucide-react';
import LcIcon from '@/components/elements/LcIcon';

type Stats = Record<'memory' | 'cpu' | 'disk' | 'uptime' | 'rx' | 'tx', number>;

const getBackgroundColor = (value: number, max: number | null): string | undefined => {
    const delta = !max ? 0 : value / max;

    if (delta > 0.8) {
        if (delta > 0.9) {
            return 'bg-helionix-btnDanger';
        }
        return 'bg-helionix-btnSecondary';
    }

    return undefined;
};

const Limit = ({ limit, children }: { limit: string | null; children: React.ReactNode }) => (
    <>
        {children}
        <span className={'ml-1 text-[70%] select-none'}>/ {limit || <>&infin;</>}</span>
    </>
);

const ServerDetailsBlock = ({ className }: { className?: string }) => {
    const layout = useStoreState((state: ApplicationStore) => state.helionix.data!.layout_console);
    const bar_cpu = useStoreState((state: ApplicationStore) => state.helionix.data!.bar_cpu);
    const bar_memory = useStoreState((state: ApplicationStore) => state.helionix.data!.bar_memory);
    const bar_disk = useStoreState((state: ApplicationStore) => state.helionix.data!.bar_disk);
    const [stats, setStats] = useState<Stats>({ memory: 0, cpu: 0, disk: 0, uptime: 0, tx: 0, rx: 0 });

    const status = ServerContext.useStoreState((state) => state.status.value);
    const connected = ServerContext.useStoreState((state) => state.socket.connected);
    const instance = ServerContext.useStoreState((state) => state.socket.instance);
    const limits = ServerContext.useStoreState((state) => state.server.data!.limits);

    const textLimits = useMemo(
        () => ({
            cpu: limits?.cpu ? `${limits.cpu}%` : null,
            memory: limits?.memory ? bytesToString(mbToBytes(limits.memory)) : null,
            disk: limits?.disk ? bytesToString(mbToBytes(limits.disk)) : null,
        }),
        [limits]
    );

    const allocation = ServerContext.useStoreState((state) => {
        const match = state.server.data!.allocations.find((allocation) => allocation.isDefault);

        return !match ? 'n/a' : `${match.alias || ip(match.ip)}:${match.port}`;
    });

    useEffect(() => {
        if (!connected || !instance) {
            return;
        }

        instance.send(SocketRequest.SEND_STATS);
    }, [instance, connected]);

    useWebsocketEvent(SocketEvent.STATS, (data) => {
        let stats: any = {};
        try {
            stats = JSON.parse(data);
        } catch (e) {
            return;
        }

        setStats({
            memory: stats.memory_bytes,
            cpu: stats.cpu_absolute,
            disk: stats.disk_bytes,
            tx: stats.network.tx_bytes,
            rx: stats.network.rx_bytes,
            uptime: stats.uptime || 0,
        });
    });

    return (
        layout == 1 ?
        <div className={classNames('flex flex-wrap gap-4 mb-4', className)}>
            <div className={classNames(styles.stat_block, 'bg-helionix-color2 flex-1')}>
                <div className={classNames(styles.status_bar, getBackgroundColor(stats.cpu, limits.cpu) || 'bg-helionix-btnPrimary')} />
                <div className={classNames(styles.icon, getBackgroundColor(stats.cpu, limits.cpu) || 'bg-helionix-btnPrimary')}>
                    <LcIcon icon={Cpu} size={24}/>
                </div>
                <div className={'flex flex-col justify-center overflow-hidden w-full'}>
                    <p className={'text-xs md:text-sm'}>CPU Usage</p>
                    <div
                        className={'h-[1.75rem] w-full font-semibold truncate'}
                    >
                        {status === 'offline' ? (
                            <span>Offline</span>
                        ) : (
                            <p>
                                <Limit limit={textLimits.cpu}>{stats.cpu.toFixed(2)}%</Limit>
                            </p>
                        )}
                    </div>
                    {bar_cpu == true &&
                    <div className={`relative w-full h-[10px] mt-2 rounded-full overflow-hidden`} style={{ background: 'linear-gradient(90deg, rgba(45,206,137,1) 20%, rgba(255,240,0,1) 50%, rgba(255,58,98,1) 80%)'}}>
                        <div className={`absolute top-0 right-0 h-full bg-black bg-opacity-75`} style={{ width: `calc(100% - ${((stats.cpu / (limits.cpu || 1)) * 100).toFixed(2)}%)`, transition: 'width 0.3s ease'}}></div>
                    </div>
                    }
                </div>
            </div>
            <div className={classNames(styles.stat_block, 'bg-helionix-color2 flex-1')}>
                <div className={classNames(styles.status_bar, getBackgroundColor(stats.memory / 1024, limits.memory * 1024) || 'bg-helionix-btnPrimary')} />
                <div className={classNames(styles.icon, getBackgroundColor(stats.memory / 1024, limits.memory * 1024) || 'bg-helionix-btnPrimary')}>
                    <LcIcon icon={MemoryStick} size={24}/>
                </div>
                <div className={'flex flex-col justify-center overflow-hidden w-full'}>
                    <p className={'text-xs md:text-sm'}>Memory Usage</p>
                    <div
                        className={'h-[1.75rem] w-full font-semibold truncate'}
                    >
                        {status === 'offline' ? (
                            <span>Offline</span>
                        ) : (
                            <p>
                                <Limit limit={textLimits.memory}>{bytesToString(stats.memory)}</Limit>
                            </p>
                        )}
                    </div>
                    {bar_memory == true &&
                    <div className={`relative w-full h-[10px] mt-2 rounded-full overflow-hidden`} style={{ background: 'linear-gradient(90deg, rgba(45,206,137,1) 20%, rgba(255,240,0,1) 50%, rgba(255,58,98,1) 80%)'}}>
                        <div className={`absolute top-0 right-0 h-full bg-black bg-opacity-75`} style={{ width: `calc(100% - ${((stats.memory / mbToBytes(limits.memory || 1)) * 100).toFixed(2)}%)`, transition: 'width 0.3s ease'}}></div>
                    </div>
                    }
                </div>
            </div>
            <div className={classNames(styles.stat_block, 'bg-helionix-color2 flex-1')}>
                <div className={classNames(styles.status_bar, getBackgroundColor(stats.disk / 1024, limits.disk * 1024) || 'bg-helionix-btnPrimary')} />
                <div className={classNames(styles.icon, getBackgroundColor(stats.disk / 1024, limits.disk * 1024) || 'bg-helionix-btnPrimary')}>
                    <LcIcon icon={HardDrive} size={24}/>
                </div>
                <div className={'flex flex-col justify-center overflow-hidden w-full'}>
                    <p className={'text-xs md:text-sm'}>Disk Usage</p>
                    <div
                        className={'h-[1.75rem] w-full font-semibold truncate'}
                    >
                         <p>
                            <Limit limit={textLimits.disk}>{bytesToString(stats.disk)}</Limit>
                        </p>
                    </div>
                    {bar_disk == true &&
                    <div className={`relative w-full h-[10px] mt-2 rounded-full overflow-hidden`} style={{ background: 'linear-gradient(90deg, rgba(45,206,137,1) 20%, rgba(255,240,0,1) 50%, rgba(255,58,98,1) 80%)'}}>
                        <div className={`absolute top-0 right-0 h-full bg-black bg-opacity-75`} style={{ width: `calc(100% - ${((stats.disk / mbToBytes(limits.disk || 1)) * 100).toFixed(2)}%)`, transition: 'width 0.3s ease'}}></div>
                    </div>
                    }
                </div>
            </div>
            <div className="flex flex-wrap w-full gap-4">
                <StatBlock icon={CloudDownload} title={'Network Inbound'} className='flex-1'>
                    {status === 'offline' ? <span>Offline</span> : <p>{bytesToString(stats.rx)}</p>}
                </StatBlock>
                <StatBlock icon={CloudUpload} title={'Network Outbound'} className='flex-1'>
                    {status === 'offline' ? <span>Offline</span> : <p>{bytesToString(stats.tx)}</p>}
                </StatBlock>
            </div>
        </div>
        : layout == 2 ?
        <div className={classNames('flex flex-wrap gap-4 mb-4', className)}>
            <div className={classNames(styles.stat_block, 'bg-helionix-color2 flex-1')}>
                <div className={classNames(styles.status_bar, getBackgroundColor(stats.cpu, limits.cpu) || 'bg-helionix-btnPrimary')} />
                <div className={classNames(styles.icon, getBackgroundColor(stats.cpu, limits.cpu) || 'bg-helionix-btnPrimary')}>
                    <LcIcon icon={Cpu} size={24}/>
                </div>
                <div className={'flex flex-col justify-center overflow-hidden w-full'}>
                    <p className={'text-xs md:text-sm'}>CPU Usage</p>
                    <div
                        className={'h-[1.75rem] w-full font-semibold truncate'}
                    >
                        {status === 'offline' ? (
                            <span>Offline</span>
                        ) : (
                            <p>
                                <Limit limit={textLimits.cpu}>{stats.cpu.toFixed(2)}%</Limit>
                            </p>
                        )}
                    </div>
                    {bar_cpu == true &&
                    <div className={`relative w-full h-[10px] mt-2 rounded-full overflow-hidden`} style={{ background: 'linear-gradient(90deg, rgba(45,206,137,1) 20%, rgba(255,240,0,1) 50%, rgba(255,58,98,1) 80%)'}}>
                        <div className={`absolute top-0 right-0 h-full bg-black bg-opacity-75`} style={{ width: `calc(100% - ${((stats.cpu / (limits.cpu || 1)) * 100).toFixed(2)}%)`, transition: 'width 0.3s ease'}}></div>
                    </div>
                    }
                </div>
            </div>
            <div className={classNames(styles.stat_block, 'bg-helionix-color2 flex-1')}>
                <div className={classNames(styles.status_bar, getBackgroundColor(stats.memory / 1024, limits.memory * 1024) || 'bg-helionix-btnPrimary')} />
                <div className={classNames(styles.icon, getBackgroundColor(stats.memory / 1024, limits.memory * 1024) || 'bg-helionix-btnPrimary')}>
                    <LcIcon icon={MemoryStick} size={24}/>
                </div>
                <div className={'flex flex-col justify-center overflow-hidden w-full'}>
                    <p className={'text-xs md:text-sm'}>Memory Usage</p>
                    <div
                        className={'h-[1.75rem] w-full font-semibold truncate'}
                    >
                        {status === 'offline' ? (
                            <span>Offline</span>
                        ) : (
                            <p>
                                <Limit limit={textLimits.memory}>{bytesToString(stats.memory)}</Limit>
                            </p>
                        )}
                    </div>
                    {bar_memory == true &&
                    <div className={`relative w-full h-[10px] mt-2 rounded-full overflow-hidden`} style={{ background: 'linear-gradient(90deg, rgba(45,206,137,1) 20%, rgba(255,240,0,1) 50%, rgba(255,58,98,1) 80%)'}}>
                        <div className={`absolute top-0 right-0 h-full bg-black bg-opacity-75`} style={{ width: `calc(100% - ${((stats.memory / mbToBytes(limits.memory || 1)) * 100).toFixed(2)}%)`, transition: 'width 0.3s ease'}}></div>
                    </div>
                    }
                </div>
            </div>
            <div className={classNames(styles.stat_block, 'bg-helionix-color2 flex-1')}>
                <div className={classNames(styles.status_bar, getBackgroundColor(stats.disk / 1024, limits.disk * 1024) || 'bg-helionix-btnPrimary')} />
                <div className={classNames(styles.icon, getBackgroundColor(stats.disk / 1024, limits.disk * 1024) || 'bg-helionix-btnPrimary')}>
                    <LcIcon icon={HardDrive} size={24}/>
                </div>
                <div className={'flex flex-col justify-center overflow-hidden w-full'}>
                    <p className={'text-xs md:text-sm'}>Disk Usage</p>
                    <div
                        className={'h-[1.75rem] w-full font-semibold truncate'}
                    >
                         <p>
                            <Limit limit={textLimits.disk}>{bytesToString(stats.disk)}</Limit>
                        </p>
                    </div>
                    {bar_disk == true &&
                    <div className={`relative w-full h-[10px] mt-2 rounded-full overflow-hidden`} style={{ background: 'linear-gradient(90deg, rgba(45,206,137,1) 20%, rgba(255,240,0,1) 50%, rgba(255,58,98,1) 80%)'}}>
                        <div className={`absolute top-0 right-0 h-full bg-black bg-opacity-75`} style={{ width: `calc(100% - ${((stats.disk / mbToBytes(limits.disk || 1)) * 100).toFixed(2)}%)`, transition: 'width 0.3s ease'}}></div>
                    </div>
                    }
                </div>
            </div>
            <div className="flex flex-wrap w-full gap-4">
                <StatBlock icon={CloudDownload} title={'Network Inbound'} className='flex-1'>
                    {status === 'offline' ? <span>Offline</span> : <p>{bytesToString(stats.rx)}</p>}
                </StatBlock>
                <StatBlock icon={CloudUpload} title={'Network Outbound'} className='flex-1'>
                    {status === 'offline' ? <span>Offline</span> : <p>{bytesToString(stats.tx)}</p>}
                </StatBlock>
            </div>
        </div>
        :
        <div className={classNames('grid grid-cols-6 gap-2 md:gap-4', className)}>
            <StatBlock icon={Radio} title={'Address'} copyOnClick={allocation}>
                <p>
                    {allocation}
                </p>
            </StatBlock>
            <StatBlock
                icon={AlarmClock}
                title={'Uptime'}
                color={getBackgroundColor(status === 'running' ? 0 : status !== 'offline' ? 9 : 10, 10)}
            >
                <p>
                    {status === null ? (
                        'Offline'
                    ) : stats.uptime > 0 ? (
                        <UptimeDuration uptime={stats.uptime / 1000} />
                    ) : (
                        capitalize(status)
                    )}
                </p>
            </StatBlock>
            <StatBlock icon={Cpu} title={'CPU Load'} color={getBackgroundColor(stats.cpu, limits.cpu)}>
                {status === 'offline' ? (
                    <span>Offline</span>
                ) : (
                    <p>
                        <Limit limit={textLimits.cpu}>{stats.cpu.toFixed(2)}%</Limit>
                    </p>
                )}
            </StatBlock>
            <StatBlock
                icon={MemoryStick}
                title={'Memory'}
                color={getBackgroundColor(stats.memory / 1024, limits.memory * 1024)}
            >
                {status === 'offline' ? (
                    <span>Offline</span>
                ) : (
                    <p>
                        <Limit limit={textLimits.memory}>{bytesToString(stats.memory)}</Limit>
                    </p>
                )}
            </StatBlock>
            <StatBlock icon={HardDrive} title={'Disk'} color={getBackgroundColor(stats.disk / 1024, limits.disk * 1024)}>
                <p>
                    <Limit limit={textLimits.disk}>{bytesToString(stats.disk)}</Limit>
                </p>
            </StatBlock>
            <StatBlock icon={CloudDownload} title={'Network (Inbound)'}>
                {status === 'offline' ? <span>Offline</span> : <p>{bytesToString(stats.rx)}</p>}
            </StatBlock>
            <StatBlock icon={CloudUpload} title={'Network (Outbound)'}>
                {status === 'offline' ? <span>Offline</span> : <p>{bytesToString(stats.tx)}</p>}
            </StatBlock>
        </div>
    );
};

export default ServerDetailsBlock;
