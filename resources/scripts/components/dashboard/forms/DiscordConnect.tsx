import React, { useEffect, useState } from 'react';
import tw from 'twin.macro';
import Button from '@/components/elements/Button';
import useSWR from 'swr';
import getConnectionStatus from '@/api/account/discord/getConnectionStatus';
import useFlash from '@/plugins/useFlash';
import Spinner from '@/components/elements/Spinner';
import { httpErrorToHuman } from '@/api/http';
import getConnectURL from '@/api/account/discord/getConnectURL';
import verifyAuth from '@/api/account/discord/verifyAuth';
import unlinkDiscordAccount from '@/api/account/discord/unlinkDiscordAccount';

export interface DiscordConnectionResponse {
    discordId: string | null; // Allow null since it may not exist
}

export default () => {
    const search = window.location.search;
    const params = new URLSearchParams(search);

    const state = params.get('state');
    const code = params.get('code');

    const [disabled, setDisabled] = useState(false);
    const [isVerified, setVerified] = useState(false);

    const { clearFlashes, clearAndAddHttpError, addError, addFlash } = useFlash();
    const { data, error, mutate } = useSWR<DiscordConnectionResponse>('/account/discord', getConnectionStatus);

    const getUrl = () => {
        setDisabled(true);
        getConnectURL()
            .then((data) => {
                window.location = data.data.authUrl;
            })
            .catch((error: any) => {
                setDisabled(false);
                addError({ key: 'account:discord', message: httpErrorToHuman(error) });
            });
    };

    const verifyConnection = () => {
        if (!isVerified && state && code) {
            setDisabled(true);
            setVerified(true);

            verifyAuth(state, code)
                .then(() => {
                    mutate(); // Refresh the state after verification
                    setDisabled(false);
                    addFlash({
                        key: 'account:discord',
                        message: "You've successfully connected your Discord account with Pterodactyl.",
                        type: 'success',
                        title: 'Success',
                    });
                })
                .catch((error: any) => {
                    setDisabled(false);
                    addError({ key: 'account:discord', message: httpErrorToHuman(error) });
                });
        }
    };

    const handleUnlink = () => {
        setDisabled(true);
        unlinkDiscordAccount()
            .then(() => {
                mutate(); // Refresh the state after unlinking
                setDisabled(false);
                addFlash({
                    key: 'account:discord',
                    message: 'Your Discord account has been unlinked.',
                    type: 'success',
                    title: 'Unlinked',
                });
            })
            .catch((error: any) => {
                setDisabled(false);
                addError({ key: 'account:discord', message: httpErrorToHuman(error) });
            });
    };

    useEffect(() => {
        if (!error) {
            clearFlashes('account:discord');

            if (state && code) {
                verifyConnection();
            }
        } else {
            clearAndAddHttpError({ key: 'account:discord', error });
        }
    }, [error, state, code]);

    return (
        <div css={tw`max-w-md mx-auto bg-gray-900 p-6 rounded-lg shadow-lg`}>
            {!data ? (
                <div css={tw`w-full text-center`}>
                    <Spinner size={'large'} centered />
                </div>
            ) : (
                <>
                    <p css={tw`text-white text-center`}>
                        <span css={tw`font-bold text-2xl text-green-400`}>
                            {data.discordId !== null ? 'You’re Connected!' : 'Connect Your Discord Account'}
                        </span>
                        <br />
                        {data.discordId !== null ? (
                            <>
                                <span css={tw`text-sm text-gray-300`}>
                                    You are currently connected with Discord ID: <b>{data.discordId}</b>.
                                    <br />
                                    Use the <b>UNLINK</b> button below if you want to disconnect your account.
                                </span>
                            </>
                        ) : (
                            <>
                                <span css={tw`text-sm text-gray-300`}>
                                    Connect your Discord account to unlock exclusive features and automatically receive the client role in our Discord server.
                                </span>
                            </>
                        )}
                    </p>

                    <div css={tw`mt-6 text-center`}>
                        {data.discordId !== null ? (
                            <Button color={'red'} onClick={handleUnlink} disabled={disabled}>
                                UNLINK
                            </Button>
                        ) : (
                            <Button color={'primary'} onClick={getUrl} disabled={disabled}>
                                CONNECT
                            </Button>
                        )}
                    </div>
                </>
            )}
        </div>
    );
};
