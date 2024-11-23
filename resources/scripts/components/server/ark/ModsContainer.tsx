import { httpErrorToHuman } from '@/api/http';
import getModIds from '@/api/server/ark/getModIds';
import getInstalled from '@/api/server/ark/getInstalled';
import getMods, { Mod, ModResponse } from '@/api/server/ark/getMods';
import updateStartupVariable from '@/api/server/updateStartupVariable';
import FlashMessageRender from '@/components/FlashMessageRender';
import MessageBox from '@/components/MessageBox';
import Input from '@/components/elements/Input';
import Modal from '@/components/elements/Modal';
import Select from '@/components/elements/Select';
import ServerContentBlock from '@/components/elements/ServerContentBlock';
import Spinner from '@/components/elements/Spinner';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import { Button } from '@/components/elements/button/index';
import useFlash from '@/plugins/useFlash';
import { ServerContext } from '@/state/server';
import React, { useEffect, useState } from 'react';
import tw from 'twin.macro';

export default () => {
    const { addError, clearFlashes } = useFlash();

    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);

    const [loading, setLoading] = useState<boolean>(false);
    const [input, setInput] = useState<string>('');
    const [mod, setMod] = useState<Mod | null>(null);
    const [mods, setMods] = useState<ModResponse | null>(null);
    const [installed, setInstalled] = useState('');
    const [showInstalled, setShowInstalled] = useState(false);
    const [loader, setLoader] = useState<boolean>(false);

    const install = (mod: Mod) => {
        clearFlashes();
        setLoading(true);

        updateStartupVariable(uuid, 'MOD_IDS', installed ? installed + ',' + mod.id : String(mod.id))
            .catch((error) => {
                console.error(error);
                addError({ message: httpErrorToHuman(error), key: 'ark:mods' });
            })
            .then(() => {
                setMod(null);
                setLoading(false);
            });
    };

    const remove = (mod: Mod) => {
        clearFlashes();
        setLoading(true);

        updateStartupVariable(
            uuid,
            'MOD_IDS',
            installed.includes(',' + mod.id + ',')
                ? installed.replace(mod.id + ',', '')
                : installed.includes(',' + mod.id)
                ? installed.replace(',' + mod.id, '')
                : installed.includes(mod.id + ',')
                ? installed.replace(mod.id + ',', '')
                : installed.replace(String(mod.id), '')
        )
            .catch((error) => {
                console.error(error);
                addError({ message: httpErrorToHuman(error), key: 'ark:mods' });
            })
            .then(() => {
                setInstalled(
                    installed.includes(',' + mod.id + ',')
                        ? installed.replace(mod.id + ',', '')
                        : installed.includes(',' + mod.id)
                        ? installed.replace(',' + mod.id, '')
                        : installed.includes(mod.id + ',')
                        ? installed.replace(mod.id + ',', '')
                        : installed.replace(String(mod.id), '')
                );
                setMod(null);
                setLoading(false);
            });
    };

    useEffect(() => {
        getModIds(uuid)
            .then((response) => {
                if (response) setInstalled(String(response));
            })
            .catch((error) => {
                console.error(error);
                addError({ message: httpErrorToHuman(error), key: 'ark:mods' });
            });
    });

    useEffect(() => {
        clearFlashes();
        setLoader(true);

        if (showInstalled) {
            getInstalled(uuid, input)
                .then((data) => setMods(data))
                .catch((error) => {
                    console.error(error);
                    addError({ key: 'ark:mods', message: httpErrorToHuman(error) });
                })
                .then(() => setLoader(false));
        } else {
            const delayDebounceFn = setTimeout(() => {
                getMods(uuid, input)
                    .then((data) => setMods(data))
                    .catch((error) => {
                        console.error(error);
                        addError({ key: 'ark:mods', message: httpErrorToHuman(error) });
                    })
                    .then(() => setLoader(false));
            }, 500);

            return () => clearTimeout(delayDebounceFn);
        }

        return undefined;
    }, [input, showInstalled]);

    return (
        <ServerContentBlock title={'Mods'} showFlashKey={'ark:mods'}>
            <div css={tw`mb-5`}>
                <MessageBox type={'warning'}>
                    Note that you need to restart your server before mods will be working in-game.
                </MessageBox>
            </div>
            <div css={tw`grid grid-cols-12 mb-5`}>
                <Select
                    onChange={(e) => {
                        setShowInstalled(e.currentTarget.value === 'installed');
                    }}
                    css={tw`col-span-2`}
                >
                    <option key={'all'} value={'all'}>
                        All
                    </option>
                    <option key={'installed'} value={'installed'}>
                        Installed
                    </option>
                </Select>
                <Input
                    value={input}
                    type={'search'}
                    css={tw`col-span-10`}
                    placeholder={'Search'}
                    onChange={(e) => setInput(e.currentTarget.value)}
                />
            </div>
            {!mods || loader ? (
                <Spinner size={'large'} centered />
            ) : (
                <>
                    {mods.data.length === 0 ? (
                        <p css={tw`text-center text-sm text-neutral-400`}>There are no mods to display.</p>
                    ) : (
                        <div css={tw`grid grid-cols-2 gap-4`}>
                            {mods.data.map((mod, index) => (
                                <TitledGreyBox title={mod.name} link={mod.links.websiteUrl} key={index}>
                                    <div css={tw`grid grid-cols-4 gap-4 cursor-pointer`} onClick={() => setMod(mod)}>
                                        <img width={'50'} src={mod.logo.url} />
                                        <div css={tw`m-auto`}>
                                            <b>Author</b>
                                            <br />
                                            {mod.authors[0].name}
                                        </div>
                                        <div css={tw`m-auto`}>
                                            <b>Downloads</b>
                                            <br />
                                            {mod.downloadCount}
                                        </div>
                                        {installed.includes(String(mod.id)) ? (
                                            <Button.Danger css={tw`m-auto`}>Remove</Button.Danger>
                                        ) : (
                                            <Button css={tw`m-auto`}>Install</Button>
                                        )}
                                    </div>
                                </TitledGreyBox>
                            ))}
                        </div>
                    )}
                    {mods.pagination && mods.pagination.totalCount > mods.data.length && (
                        <div css={tw`text-center mt-6`}>
                            Showing 50 out of {mods.pagination.totalCount} mods, add search query to show specific mods.
                        </div>
                    )}
                </>
            )}
            {mod && (
                <Modal
                    visible={!!mod}
                    onDismissed={() => setMod(null)}
                    closeOnBackground={true}
                    showSpinnerOverlay={loading}
                >
                    <FlashMessageRender key={'feature:gslToken'} css={tw`mb-4`} />
                    <h2 css={tw`text-2xl mb-2 text-neutral-100`}>{mod.name}</h2>
                    <div css={tw`mt-4`}>
                        <b>Description:</b> {mod.summary}
                        <br />
                        <b>Author:</b> {mod.authors[0].name}
                        <br />
                        <b>Uploaded At:</b> {mod.dateCreated}
                        <br />
                        <b>Last Release Date:</b> {mod.dateModified}
                        <br />
                        <b>Downloads:</b> {mod.downloadCount}
                        <br />
                    </div>
                    <div css={tw`mt-8 sm:flex items-center justify-end`}>
                        {installed.includes(String(mod.id)) ? (
                            <Button.Danger onClick={() => remove(mod)} css={tw`mt-4 sm:mt-0 sm:ml-4 w-full sm:w-auto`}>
                                Remove
                            </Button.Danger>
                        ) : (
                            <Button onClick={() => install(mod)} css={tw`mt-4 sm:mt-0 sm:ml-4 w-full sm:w-auto`}>
                                Install
                            </Button>
                        )}
                    </div>
                </Modal>
            )}
        </ServerContentBlock>
    );
};