import React, { useState } from 'react';
import styled from 'styled-components/macro';
import tw from 'twin.macro';
import { useStoreState } from "easy-peasy";
import { ApplicationStore } from "@/state";
import AlertContainer from '@/components/elements/helionix/alert/AlertContainer';
import LcIcon from '../../LcIcon';
import { OctagonAlert, OctagonX, ShieldAlert, TriangleAlert, X } from 'lucide-react';

const Alert = () => {
    const AlertType = useStoreState((state: ApplicationStore) => state.helionix.data!.alert_type);
    const AlertClossable = useStoreState((state: ApplicationStore) => state.helionix.data!.alert_clossable);
    const AlertMessage = useStoreState((state: ApplicationStore) => state.helionix.data!.alert_message);
    const [alertClosed, setAlertClosed] = useState(false);

    const handleCloseAlert = () => {
        setAlertClosed(true);
    };

    const getAlertColor = (type: string) => {
        switch (type) {
            case 'information':
                return tw`bg-helionix-alertColorInformation`;
            case 'update':
                return tw`bg-helionix-alertColorUpdate`;
            case 'warning':
                return tw`bg-helionix-alertColorWarning`;
            case 'error':
                return tw`bg-helionix-alertColorError`;
            default:
                return tw`bg-helionix-color2`;
        }
    };

    const getAlertIcon = (type: string) => {
        switch (type) {
            case 'information':
                return OctagonAlert;
            case 'update':
                return ShieldAlert;
            case 'warning':
                return TriangleAlert;
            case 'error':
                return OctagonX;
            default:
                return OctagonAlert;
        }
    };

    return (
        <>
            {!alertClosed && (
                <AlertContainer css={[getAlertColor(AlertType)]}>
                    <div css={tw`mr-2 font-bold`}>
                        <div css={tw`flex`}>
                            <LcIcon icon={getAlertIcon(AlertType)} css={tw`mr-1`} size={24}/>
                            <p css={tw`uppercase font-bold`}>{AlertType}</p>
                        </div>
                        <p css={tw`font-normal`}>{AlertMessage}</p>
                    </div>
                    {AlertClossable == true && (
                        <button onClick={handleCloseAlert} css={tw`ml-auto`}>
                            <LcIcon icon={X} size={24} />
                        </button>
                    )}
                </AlertContainer>
            )}
        </>
    );
};

export default Alert;