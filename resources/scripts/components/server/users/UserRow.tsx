import React, { useState } from 'react';
import { Subuser } from '@/state/server/subusers';
import RemoveSubuserButton from '@/components/server/users/RemoveSubuserButton';
import EditSubuserModal from '@/components/server/users/EditSubuserModal';
import Can from '@/components/elements/Can';
import { useStoreState } from 'easy-peasy';
import tw from 'twin.macro';
import GreyRowBox from '@/components/elements/GreyRowBox';
import LcIcon from '@/components/elements/LcIcon';
import { Lock, Pen, Unlock } from 'lucide-react';

interface Props {
    subuser: Subuser;
}

export default ({ subuser }: Props) => {
    const uuid = useStoreState((state) => state.user!.data!.uuid);
    const [visible, setVisible] = useState(false);

    return (
        <GreyRowBox css={tw`mb-2`}>
            <EditSubuserModal subuser={subuser} visible={visible} onModalDismissed={() => setVisible(false)} />
            <div css={tw`w-10 h-10 rounded-full bg-helionix-color3 border-2 border-helionix-color3 overflow-hidden block`}>
                <img css={tw`w-full h-full`} src={`${subuser.image}?s=400`} />
            </div>
            <div css={tw`ml-4 flex-1 overflow-hidden`}>
                <p css={tw`text-sm truncate`}>{subuser.email}</p>
            </div>
            <div css={tw`ml-4 hidden md:flex flex-col items-center`}>
                <LcIcon
                    icon={subuser.twoFactorEnabled ? Lock : Unlock}
                    size={20}
                    css={!subuser.twoFactorEnabled ? tw`text-helionix-btnDanger` : undefined}
                />
                <span css={tw`text-2xs uppercase hidden md:block mt-2`}>2FA Enabled</span>
            </div>
            <div css={tw`ml-4 hidden md:block`}>
                <p css={tw`font-medium text-center`}>
                    {subuser.permissions.filter((permission) => permission !== 'websocket.connect').length}
                </p>
                <span css={tw`text-2xs uppercase`}>Permissions</span>
            </div>
            {subuser.uuid !== uuid && (
                <>
                    <Can action={'user.update'}>
                        <button
                            type={'button'}
                            aria-label={'Edit subuser'}
                            css={tw`block text-sm p-1 p-2 bg-helionix-btnSecondary rounded-full mx-4`}
                            onClick={() => setVisible(true)}
                        >
                            <LcIcon icon={Pen} size={20}/>
                        </button>
                    </Can>
                    <Can action={'user.delete'}>
                        <RemoveSubuserButton subuser={subuser} />
                    </Can>
                </>
            )}
        </GreyRowBox>
    );
};
