import tw from 'twin.macro';
import React, { useState } from 'react';
import { useFlashKey } from '@/plugins/useFlash';
import { deleteSSHKey, useSSHKeys } from '@/api/account/ssh-keys';
import { Dialog } from '@/components/elements/dialog';
import Code from '@/components/elements/Code';
import LcIcon from '@/components/elements/LcIcon';
import { Trash } from 'lucide-react';

export default ({ name, fingerprint }: { name: string; fingerprint: string }) => {
    const { clearAndAddHttpError } = useFlashKey('account');
    const [visible, setVisible] = useState(false);
    const { mutate } = useSSHKeys();

    const onClick = () => {
        clearAndAddHttpError();

        Promise.all([
            mutate((data) => data?.filter((value) => value.fingerprint !== fingerprint), false),
            deleteSSHKey(fingerprint),
        ]).catch((error) => {
            mutate(undefined, true).catch(console.error);
            clearAndAddHttpError(error);
        });
    };

    return (
        <>
            <Dialog.Confirm
                open={visible}
                title={'Delete SSH Key'}
                confirm={'Delete Key'}
                onConfirmed={onClick}
                onClose={() => setVisible(false)}
            >
                <p>Removing the <Code>{name}</Code> SSH key will invalidate its usage across the Panel.</p>
            </Dialog.Confirm>
            <button css={tw`ml-4 p-2 text-sm bg-helionix-btnDanger rounded-full`} onClick={() => setVisible(true)}>
                <LcIcon icon={Trash} size={20}/>
            </button>
        </>
    );
};
