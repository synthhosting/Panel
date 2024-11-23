import React, { useEffect, useState } from 'react';
import tw from 'twin.macro';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faList, faUsers } from '@fortawesome/free-solid-svg-icons';
import Spinner from '@/components/elements/Spinner';
import useSWR from 'swr';
import getPlayers from '@/api/server/getPlayers';
import { ServerContext } from '@/state/server';
import useFlash from '@/plugins/useFlash';
import FlashMessageRender from '@/components/FlashMessageRender';
import styled from 'styled-components/macro';
import classNames from 'classnames';
import styles from '@/components/server/console/style.module.css';
import Button from '@/components/elements/Button';
import Modal from '@/components/elements/Modal';

const Code = styled.code`${tw`font-mono py-1 px-2 bg-neutral-900 rounded text-sm inline-block m-2`}`;

export interface PlayersResponse {
    show: number;
    maxPlayers: number;
    onlinePlayers: number;
    players: string[];
    timer: string;
}

const PlayerCounter = () => {
    const uuid = ServerContext.useStoreState(state => state.server.data!.uuid);

    const [ hidden, setHidden ] = useState(true);
    const [ visible, setVisible ] = useState(false);

    const { clearFlashes, clearAndAddHttpError } = useFlash();

    const { data, error } = useSWR<PlayersResponse>([ uuid, '/counter' ], key => getPlayers(key), {
        revalidateOnFocus: true,
        refreshInterval: 10000,
    });

    useEffect(() => {
        if (!error) {
            setHidden(true);
            clearFlashes('server:players');
        } else {
            setHidden(false);
            clearAndAddHttpError({ key: 'server:players', error });
        }
    }, [ error ]);

    return (
        <>
            {!hidden &&
                <div css={tw`flex items-center w-full col-span-6`}>
                    <FlashMessageRender byKey={'server:players'} />
                </div>
            }
            {!data ? (
                <div css={tw`flex items-center w-full col-span-6`}>
                    <Spinner size={'small'} centered />
                </div>
            ) : (
                <>
                    <div>
                        <div className={'flex flex-col justify-center overflow-hidden w-full'}>
                            <div className={`h-[${data.show === 1 ? '3.5' : '2.5'}rem] w-full font-semibold text-gray-100 truncate`}>
                                {data.show === 1 ? (
                                    <>
                                        <p css={tw`mb-4`}>
                                            <span onClick={() => setVisible(true)} css={tw`cursor-pointer`}>
                                                Players: {data.onlinePlayers}/{data.maxPlayers}
                                            </span>
                                        </p>
                                    </>
                                ) : (
                                    <div css={tw`mb-4`}>
                                    Players: Error
                                    </div>
                                )}
                            </div>
                        </div>
                    </div>
                    <Modal visible={visible} onDismissed={() => setVisible(false)}>
                        <>
                            <b>Online Players:</b><br /><br />
                            {data.players.length > 0 ? (
                                <div css={tw`w-full mt-4`}>
                                    {data.players.map((item, key) => (
                                        <Code key={key}>{item}</Code>
                                    ))}
                                </div>
                            ) : (
                                <>
                                    There are no online players.
                                </>
                            )}
                        </>
                    </Modal>
                </>
            )}
        </>
    );
};

export default PlayerCounter;