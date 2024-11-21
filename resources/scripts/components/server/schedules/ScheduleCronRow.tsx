import React from 'react';
import { Schedule } from '@/api/server/schedules/getServerSchedules';
import classNames from 'classnames';

interface Props {
    cron: Schedule['cron'];
    className?: string;
}

const ScheduleCronRow = ({ cron, className }: Props) => (
    <div className={classNames('flex', className)}>
        <div className={'w-1/5 sm:w-auto text-center'}>
            <p className={'font-medium'}>{cron.minute}</p>
            <span className={'text-2xs uppercase'}>Minute</span>
        </div>
        <div className={'w-1/5 sm:w-auto text-center ml-4'}>
            <p className={'font-medium'}>{cron.hour}</p>
            <span className={'text-2xs uppercase'}>Hour</span>
        </div>
        <div className={'w-1/5 sm:w-auto text-center ml-4'}>
            <p className={'font-medium'}>{cron.dayOfMonth}</p>
            <span className={'text-2xs uppercase'}>Day (Month)</span>
        </div>
        <div className={'w-1/5 sm:w-auto text-center ml-4'}>
            <p className={'font-medium'}>{cron.month}</p>
            <span className={'text-2xs uppercase'}>Month</span>
        </div>
        <div className={'w-1/5 sm:w-auto text-center ml-4'}>
            <p className={'font-medium'}>{cron.dayOfWeek}</p>
            <span className={'text-2xs uppercase'}>Day (Week)</span>
        </div>
    </div>
);

export default ScheduleCronRow;
