import React from 'react';
import { Schedule } from '@/api/server/schedules/getServerSchedules';
import { format } from 'date-fns';
import tw from 'twin.macro';
import ScheduleCronRow from '@/components/server/schedules/ScheduleCronRow';
import LcIcon from '@/components/elements/LcIcon';
import { Calendar } from 'lucide-react';

export default ({ schedule }: { schedule: Schedule }) => (
    <>
        <div css={tw`hidden md:block`}>
            <LcIcon icon={Calendar} size={20} />
        </div>
        <div css={tw`flex-1 md:ml-4`}>
            <p>{schedule.name}</p>
            <span css={tw`text-xs`}>
                Last run at: {schedule.lastRunAt ? format(schedule.lastRunAt, "MMM do 'at' h:mma") : 'never'}
            </span>
        </div>
        <div>
            <p
                css={[
                    tw`py-1 px-3 rounded text-xs uppercase text-white sm:hidden`,
                    schedule.isActive ? tw`bg-green-600` : tw`bg-neutral-400`,
                ]}
            >
                {schedule.isActive ? 'Active' : 'Inactive'}
            </p>
        </div>
        <ScheduleCronRow cron={schedule.cron} css={tw`mx-auto sm:mx-8 w-full sm:w-auto mt-4 sm:mt-0`} />
        <div>
            <p
                css={[
                    tw`py-2 px-3 rounded-lg text-xs uppercase hidden sm:block`,
                    schedule.isActive && !schedule.isProcessing ? tw`bg-helionix-btnPrimary` : tw`bg-helionix-btnSecondary`,
                ]}
            >
                {schedule.isProcessing ? 'Processing' : schedule.isActive ? 'Active' : 'Inactive'}
            </p>
        </div>
    </>
);
