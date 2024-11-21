import React, { useState } from 'react';
import { Schedule, Task } from '@/api/server/schedules/getServerSchedules';
import deleteScheduleTask from '@/api/server/schedules/deleteScheduleTask';
import { httpErrorToHuman } from '@/api/http';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import TaskDetailsModal from '@/components/server/schedules/TaskDetailsModal';
import Can from '@/components/elements/Can';
import useFlash from '@/plugins/useFlash';
import { ServerContext } from '@/state/server';
import tw from 'twin.macro';
import ConfirmationModal from '@/components/elements/ConfirmationModal';
import LcIcon from '@/components/elements/LcIcon';
import { AlarmClock, ArchiveRestore, ChevronsLeftRightEllipsis, CircleArrowDown, Pencil, Power, Terminal, Trash } from 'lucide-react';

interface Props {
    schedule: Schedule;
    task: Task;
}

const getActionDetails = (action: string): [string, any] => {
    switch (action) {
        case 'command':
            return ['Send Command', Terminal];
        case 'power':
            return ['Send Power Action', Power];
        case 'backup':
            return ['Create Backup', ArchiveRestore];
        default:
            return ['Unknown Action', ChevronsLeftRightEllipsis];
    }
};

export default ({ schedule, task }: Props) => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const { clearFlashes, addError } = useFlash();
    const [visible, setVisible] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [isEditing, setIsEditing] = useState(false);
    const appendSchedule = ServerContext.useStoreActions((actions) => actions.schedules.appendSchedule);

    const onConfirmDeletion = () => {
        setIsLoading(true);
        clearFlashes('schedules');
        deleteScheduleTask(uuid, schedule.id, task.id)
            .then(() =>
                appendSchedule({
                    ...schedule,
                    tasks: schedule.tasks.filter((t) => t.id !== task.id),
                })
            )
            .catch((error) => {
                console.error(error);
                setIsLoading(false);
                addError({ message: httpErrorToHuman(error), key: 'schedules' });
            });
    };

    const [title, icon] = getActionDetails(task.action);

    return (
        <div css={tw`sm:flex items-center p-3 sm:p-6 mt-4 bg-helionix-color2 rounded-2xl`}>
            <SpinnerOverlay visible={isLoading} fixed size={'large'} />
            <TaskDetailsModal
                schedule={schedule}
                task={task}
                visible={isEditing}
                onModalDismissed={() => setIsEditing(false)}
            />
            <ConfirmationModal
                title={'Confirm task deletion'}
                buttonText={'Delete Task'}
                onConfirmed={onConfirmDeletion}
                visible={visible}
                onModalDismissed={() => setVisible(false)}
            >
                <p>Are you sure you want to delete this task? This action cannot be undone.</p>
            </ConfirmationModal>
            <LcIcon icon={icon} css={tw`text-lg hidden md:block`} size={20}/>
            <div css={tw`flex-none sm:flex-1 w-full sm:w-auto overflow-x-auto`}>
                <p css={tw`md:ml-6 uppercase text-sm`}>{title}</p>
                {task.payload && (
                    <div css={tw`md:ml-6 mt-2`}>
                        {task.action === 'backup' && (
                            <p css={tw`text-xs uppercase mb-1`}>Ignoring files & folders:</p>
                        )}
                        <div
                            css={tw`font-mono bg-helionix-color3 rounded-lg py-1 px-2 text-sm w-auto inline-block whitespace-pre-wrap break-all`}
                        >
                            <span>{task.payload}</span>
                        </div>
                    </div>
                )}
            </div>
            <div css={tw`mt-3 sm:mt-0 flex items-center w-full sm:w-auto`}>
                {task.continueOnFailure && (
                    <div css={tw`mr-6`}>
                        <div css={tw`flex items-center px-2 py-1 bg-helionix-btnSecondary text-sm rounded-full`}>
                            <LcIcon icon={CircleArrowDown} css={tw`mr-2`} />
                            <p>Continues on Failure</p>
                        </div>
                    </div>
                )}
                {task.sequenceId > 1 && task.timeOffset > 0 && (
                    <div css={tw`mr-6`}>
                        <div css={tw`flex items-center px-2 py-1 bg-helionix-btnSecondary text-sm rounded-full`}>
                            <LcIcon icon={AlarmClock} css={tw`mr-2`} />
                            <p>{task.timeOffset}s later</p>
                        </div>
                    </div>
                )}
                <Can action={'schedule.update'}>
                    <button
                        type={'button'}
                        aria-label={'Edit scheduled task'}
                        css={tw`block bg-helionix-btnSecondary rounded-full text-sm p-2 mr-4 ml-auto sm:ml-0`}
                        onClick={() => setIsEditing(true)}
                    >
                        <LcIcon icon={Pencil} size={20}/>
                    </button>
                </Can>
                <Can action={'schedule.update'}>
                    <button
                        type={'button'}
                        aria-label={'Delete scheduled task'}
                        css={tw`block bg-helionix-btnDanger rounded-full text-sm p-2`}
                        onClick={() => setVisible(true)}
                    >
                        <LcIcon icon={Trash} size={20}/>
                    </button>
                </Can>
            </div>
        </div>
    );
};
