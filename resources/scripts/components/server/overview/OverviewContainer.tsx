import React, { useEffect, useMemo, useState } from "react";
import { useStoreState } from "easy-peasy";
import { ApplicationStore } from "@/state";
import { bytesToString, ip, mbToBytes } from "@/lib/formatters";
import { ServerContext } from "@/state/server";
import { SocketEvent, SocketRequest } from "@/components/server/events";
import UptimeDuration from "@/components/server/UptimeDuration";
import StatBlock from "@/components/server/console/StatBlock";
import useWebsocketEvent from "@/plugins/useWebsocketEvent";
import tw from "twin.macro";
import CopyOnClick from "@/components/elements/CopyOnClick";
import { capitalize } from "@/lib/strings";
import TitledGreyBox from "@/components/elements/TitledGreyBox";
import ServerContentBlock from "@/components/elements/ServerContentBlock";
import { Activity, AlarmClock, Blocks, Cpu, Fingerprint, HardDrive, MemoryStick, Navigation, Package, Radio, ServerCog } from "lucide-react";
import LcIcon from "@/components/elements/LcIcon";

type Stats = Record<"memory" | "cpu" | "disk" | "uptime" | "rx" | "tx", number>;

export default () => {
  const bar_cpu = useStoreState((state: ApplicationStore) => state.helionix.data!.bar_cpu);
  const bar_memory = useStoreState((state: ApplicationStore) => state.helionix.data!.bar_memory);
  const bar_disk = useStoreState((state: ApplicationStore) => state.helionix.data!.bar_disk);
  const eggImage = ServerContext.useStoreState((state) => state.server.data!.eggImage);
  const eggName = ServerContext.useStoreState((state) => state.server.data!.eggName);
  const [stats, setStats] = useState<Stats>({
    memory: 0,
    cpu: 0,
    disk: 0,
    uptime: 0,
    tx: 0,
    rx: 0,
  });

  const status = ServerContext.useStoreState((state) => state.status.value);
  const connected = ServerContext.useStoreState(
    (state) => state.socket.connected
  );
  const instance = ServerContext.useStoreState(
    (state) => state.socket.instance
  );

  const name = ServerContext.useStoreState((state) => state.server.data!.name);
  const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
  const node = ServerContext.useStoreState((state) => state.server.data!.node);
  const limits = ServerContext.useStoreState(
    (state) => state.server.data!.limits
  );

  const diskLimit =
    limits.disk !== 0 ? bytesToString(mbToBytes(limits.disk)) : "Unlimited";
  const memoryLimit =
    limits.memory !== 0 ? bytesToString(mbToBytes(limits.memory)) : "Unlimited";
  const cpuLimit = limits.cpu !== 0 ? limits.cpu + "%" : "Unlimited";

  const allocation = ServerContext.useStoreState((state) => {
    const match = state.server.data!.allocations.find(
      (allocation) => allocation.isDefault
    );

    return !match ? "n/a" : `${match.alias || ip(match.ip)}:${match.port}`;
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
    <ServerContentBlock title="Overview">
      <div className={'bg-center bg-cover bg-no-repeat rounded-lg w-full h-[25vh]  md:h-[35vh] mb-4'} css={`background-image:url(${eggImage ? eggImage : 'https://cdn.flydev.one/images/helionix/dashboard/server_bg.png'})`}></div>
      <div css={tw`w-full md:flex gap-4`}>
      <div css={tw`w-full md:flex-1 md:mb-0 mb-4`}>
        <TitledGreyBox icon={ServerCog} title={"Server Info"}>
          <div css={tw`overflow-hidden whitespace-nowrap`}>
            <div css={tw`flex items-center`}>
              <div>
                <LcIcon icon={Fingerprint} />
              </div>
              <a css={tw`ml-2 uppercase font-semibold`}>NAME</a>
            </div>
            <p css={tw`mb-4`}>{name}</p>
          </div>
          <div css={tw`overflow-hidden whitespace-nowrap`}>
            <div css={tw`flex items-center`}>
              <div>
                <LcIcon icon={Activity} />
              </div>
              <a css={tw`ml-2 uppercase font-semibold`}>STATUS</a>
            </div>
            <p css={tw`mb-4`}>
              {status === "starting" ||
              status === "stopping" ||
              status === "running"
                ? capitalize(status)
                : "Offline"}
            </p>
          </div>
          <div css={tw`overflow-hidden whitespace-nowrap`}>
            <div css={tw`flex items-center`}>
              <div>
                <LcIcon icon={Blocks} />
              </div>
              <a css={tw`ml-2 uppercase font-semibold`}>EGG</a>
            </div>
            <p css={tw`mb-4`}>{eggName}</p>
          </div>
          <div css={tw`overflow-hidden whitespace-nowrap`}>
            <div css={tw`flex items-center`}>
              <div>
                <LcIcon icon={Package} />
              </div>
              <a css={tw`ml-2 uppercase font-semibold`}>UUID</a>
            </div>
            <CopyOnClick text={uuid}>
              <p css={tw`mb-4`}>{uuid}</p>
            </CopyOnClick>
          </div>
          <div css={tw`overflow-hidden whitespace-nowrap`}>
            <div css={tw`flex items-center`}>
              <div>
                <LcIcon icon={Navigation} />
              </div>
              <a css={tw`ml-2 uppercase font-semibold`}>NODE</a>
            </div>
            <p css={tw`mb-4`}>{node}</p>
          </div>
          <div css={tw`overflow-hidden whitespace-nowrap`}>
            <div css={tw`flex items-center`}>
              <div>
                <LcIcon icon={Radio} />
              </div>
              <a css={tw`ml-2 uppercase font-semibold`}>ADDRESS</a>
            </div>
            <CopyOnClick text={allocation}>
              <p css={tw`mb-4`}>{allocation}</p>
            </CopyOnClick>
          </div>
        </TitledGreyBox>
      </div>
      <div css={tw`w-full flex flex-col gap-4 md:flex-1`}>
        <div css={tw`bg-helionix-color2 p-4 rounded-lg`}>
          <div css={tw`flex`}>
            <div>
              <p css={tw`text-lg`}>UPTIME</p>
              <p css={tw`mt-1`}>
                {status === "starting" || status === "stopping" ? (
                  capitalize(status)
                ) : stats.uptime > 0 ? (
                  <UptimeDuration uptime={stats.uptime / 1000} />
                ) : (
                  "Offline"
                )}
              </p>
            </div>
            <div css={tw`flex items-center p-4 bg-helionix-btnPrimary rounded-full ml-auto`}>
              <LcIcon icon={AlarmClock} size={28}/>
            </div>
          </div>
        </div>
        <div css={tw`bg-helionix-color2 p-4 rounded-lg`}>
          <div css={tw`flex`}>
            <div>
              <p css={tw`text-lg`}>CPU</p>
              <p css={tw`mt-1`}>{stats.cpu.toFixed(2)}% / {cpuLimit}</p>
            </div>
            <div css={tw`flex items-center p-4 bg-helionix-btnPrimary rounded-full ml-auto`}>
              <LcIcon icon={Cpu} size={28}/>
            </div>
          </div>
          {bar_cpu == true &&
          <div className={`relative w-full h-[10px] mt-2 rounded-full overflow-hidden`} style={{ background: 'linear-gradient(90deg, rgba(45,206,137,1) 20%, rgba(255,240,0,1) 50%, rgba(255,58,98,1) 80%)'}}>
            <div className={`absolute top-0 right-0 h-full bg-black bg-opacity-75`} style={{ width: `calc(100% - ${((stats.cpu / (limits.cpu || 1)) * 100).toFixed(2)}%)`, transition: 'width 0.3s ease'}}></div>
          </div>
          }
        </div>
        <div css={tw`bg-helionix-color2 p-4 rounded-lg`}>
          <div css={tw`flex`}>
            <div>
              <p css={tw`text-lg`}>MEMORY</p>
              <p css={tw`mt-1`}>{bytesToString(stats.memory)} / {memoryLimit}</p>
            </div>
            <div css={tw`flex items-center p-4 bg-helionix-btnPrimary rounded-full ml-auto`}>
              <LcIcon icon={MemoryStick} size={28}/>
            </div>
          </div>
          {bar_memory == true &&
          <div className={`relative w-full h-[10px] mt-2 rounded-full overflow-hidden`} style={{ background: 'linear-gradient(90deg, rgba(45,206,137,1) 20%, rgba(255,240,0,1) 50%, rgba(255,58,98,1) 80%)'}}>
            <div className={`absolute top-0 right-0 h-full bg-black bg-opacity-75`} style={{ width: `calc(100% - ${((stats.memory / mbToBytes(limits.memory || 1)) * 100).toFixed(2)}%)`, transition: 'width 0.3s ease'}}></div>
          </div>
          }
        </div>
        <div css={tw`bg-helionix-color2 p-4 rounded-lg`}>
          <div css={tw`flex`}>
            <div>
              <p css={tw`text-lg`}>DISK</p>
              <p css={tw`mt-1`}>{bytesToString(stats.disk)} / {diskLimit}</p>
            </div>
            <div css={tw`flex items-center p-4 bg-helionix-btnPrimary rounded-full ml-auto`}>
              <LcIcon icon={HardDrive} size={28}/>
            </div>
          </div>
          {bar_disk == true &&
          <div className={`relative w-full h-[10px] mt-2 rounded-full overflow-hidden`} style={{ background: 'linear-gradient(90deg, rgba(45,206,137,1) 20%, rgba(255,240,0,1) 50%, rgba(255,58,98,1) 80%)'}}>
            <div className={`absolute top-0 right-0 h-full bg-black bg-opacity-75`} style={{ width: `calc(100% - ${((stats.disk / mbToBytes(limits.disk || 1)) * 100).toFixed(2)}%)`, transition: 'width 0.3s ease'}}></div>
          </div>
          }
        </div>
      </div>
    </div>
    </ServerContentBlock>
  );  
};
