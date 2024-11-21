import React, { useState, useEffect } from 'react';
import TitledGreyBox from '@/components/elements/TitledGreyBox';
import { ServerContext } from '@/state/server';
import FlashMessageRender from '@/components/FlashMessageRender';
import tw from 'twin.macro';
import Input from '@/components/elements/Input';
import ServerContentBlock from '@/components/elements/ServerContentBlock';
import { Button } from '@/components/elements/button/index';
import search, { Plugin } from '@/api/server/rustplugins/search';
import installPlugin from '@/api/server/rustplugins/installPlugin';
import getFileContents from '@/api/server/files/getFileContents';
import deleteFiles from '@/api/server/files/deleteFiles';
import useFlash from '@/plugins/useFlash';
import { PaginatedResult, httpErrorToHuman } from '@/api/http';
import Pagination from '@/components/elements/Pagination';
import Spinner from '@/components/elements/Spinner';
import Modal from '@/components/elements/Modal';
import MessageBox from '@/components/MessageBox';
import ConfirmationModal from '@/components/elements/ConfirmationModal';
import Switch from '@/components/elements/Switch';
import updateStartupVariable from '@/api/server/updateStartupVariable';
import Select from '@/components/elements/Select';
import loadDirectory from '@/api/server/files/loadDirectory';
import { FileObject } from '@/api/server/files/loadDirectory';

export default () => {
    const { addError, clearFlashes, clearAndAddHttpError } = useFlash();

    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const instance = ServerContext.useStoreState((state) => state.socket.instance);

    const [page, setPage] = useState<number>(1);
    const [input, setInput] = useState<string>('');
    const [plugins, setPlugins] = useState<PaginatedResult<Plugin> | null>(null);
    const [plugin, setPlugin] = useState<Plugin | null>(null);
    const [installed, setInstalled] = useState<FileObject[] | null>(null);
    const [framework, setFramework] = useState<string>('');
    const [oxideInstall, setOxideInstall] = useState<boolean>(false);
    const [loading, setLoading] = useState<boolean>(false);
    const [loader, setLoader] = useState<boolean>(false);
    const [loaded, setLoaded] = useState<boolean>(false);
    const [reload, setReload] = useState<boolean>(false);
    const [update, setUpdate] = useState<boolean>(false);
    const [filter, setFilter] = useState<boolean>(false);

    const install = (data: Plugin) => {
        setLoading(true);

        installPlugin(
            uuid,
            framework === 'oxide' ? '/oxide/plugins' : '/carbon/plugins',
            data.downloadUrl,
            data,
            update
        ).then(() => {
            setReload(true);
            setPlugin(null);
            setLoading(false);
        });
    };

    const remove = (plugin: string) => {
        setLoading(true);

        deleteFiles(uuid, framework === 'oxide' ? '/oxide/plugins' : '/carbon/plugins', [`${plugin}.cs`]).then(() => {
            deleteFiles(uuid, framework === 'oxide' ? '/oxide/config' : '/carbon/configs', [`${plugin}.json`]).then(
                () => {
                    setReload(true);
                    setPlugin(null);
                    setLoading(false);
                }
            );
        });
    };

    const installOxide = () => {
        setLoading(true);

        updateStartupVariable(uuid, 'FRAMEWORK', 'oxide')
            .then(() => instance?.send('set state', 'restart'))
            .catch((error) => {
                console.error(error);
                clearAndAddHttpError({ error, key: 'rust:plugins' });
            })
            .then(() => {
                setOxideInstall(false);
                setLoading(false);
            });
    };

    useEffect(() => {
        clearFlashes();
        setLoader(true);

        const delayDebounceFn = setTimeout(() => {
            search(uuid, input, page, filter)
                .then((data) => setPlugins(data))
                .catch((error) => {
                    console.error(error);
                    addError({ key: 'rust:plugins', message: httpErrorToHuman(error) });
                })
                .then(() => setLoader(false));

            if (framework !== '') loadDirectory(uuid, framework === 'oxide' ? '/oxide/plugins' : '/carbon/plugins').then((data) => {
                setInstalled(data);
                setReload(false);
            });
        }, 500);

        return () => clearTimeout(delayDebounceFn);
    }, [input, page, reload, filter]);

    useEffect(() => {
        if (loaded || framework !== '') return;

        getFileContents(uuid, '/oxide/oxide.config.json')
            .then(() => setFramework('oxide'))
            .catch(() => getFileContents(uuid, '/carbon/config.json')
                .then(() => setFramework('carbon'))
                .catch(() => setFramework('')));

        setLoaded(true);
    });

    return (
        <ServerContentBlock title={'Settings'}>
            {!framework && (
                <div css={tw`mb-5`}>
                    <MessageBox type={'error'}>
                        You have to install a modding framework before you can install plugins, click{' '}
                        <a onClick={() => setOxideInstall(true)} css={tw`underline cursor-pointer`}>
                            here
                        </a>{' '}
                        to install Oxide.
                    </MessageBox>
                </div>
            )}
            <div css={tw`mb-5`}>
                <MessageBox type={'warning'}>
                    Note that you may need to restart your server before actions will apply in-game.
                </MessageBox>
            </div>
            <FlashMessageRender byKey={'rust:plugins'} css={tw`mb-4`} />
            <div css={tw`grid grid-cols-12 mb-5`}>
                <Select
                    onChange={(e) => {
                        setFilter(e.currentTarget.value === 'installed');
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
                    onChange={(e) => { setInput(e.currentTarget.value); setPage(1); }}
                />
            </div>
            {!plugins || loader ? (
                <Spinner centered size={'large'} />
            ) : (
                <Pagination data={plugins} onPageSelect={setPage}>
                    {({ items }) =>
                        items.length === 0 ? (
                            <p css={tw`text-center text-sm text-neutral-400`}>There are no plugins to display.</p>
                        ) : (
                            <div css={tw`grid grid-cols-2 gap-4`}>
                                {items.map((plugin, index) => (
                                    <TitledGreyBox title={plugin.title} link={plugin.url} key={index}>
                                        <div
                                            css={tw`grid grid-cols-4 gap-4 cursor-pointer`}
                                            onClick={() => setPlugin(plugin)}
                                        >
                                            <img width={'50'} src={plugin.iconUrl} />
                                            <div css={tw`m-auto`}>
                                                <b>Author</b>
                                                <br />
                                                {plugin.author ?? 'Unknown'}
                                            </div>
                                            <div css={tw`m-auto`}>
                                                <b>Downloads</b>
                                                <br />
                                                {plugin.downloadsShortened}
                                            </div>
                                            {installed?.find((i) => i.name.replace('.cs', '') === plugin.name) ? (
                                                <Button.Danger css={tw`m-auto`} disabled={!framework}>
                                                    Remove
                                                </Button.Danger>
                                            ) : (
                                                <Button css={tw`m-auto`} disabled={!framework}>
                                                    Install
                                                </Button>
                                            )}
                                        </div>
                                    </TitledGreyBox>
                                ))}
                            </div>
                        )
                    }
                </Pagination>
            )}
            {plugin && (
                <Modal
                    visible={!!plugin}
                    onDismissed={() => setPlugin(null)}
                    closeOnBackground={true}
                    showSpinnerOverlay={loading}
                >
                    <FlashMessageRender key={'feature:gslToken'} css={tw`mb-4`} />
                    <h2 css={tw`text-2xl mb-2 text-neutral-100`}>{plugin.title}</h2>
                    {plugin.tagsAll.split(',').map((tag, index) => (
                        <small css={tw`px-2 py-1 bg-primary-500 rounded-full mr-1 text-xs`} key={index}>
                            {tag}
                        </small>
                    ))}
                    <div css={tw`mt-4`}>
                        <b>Description:</b> {plugin.description}
                        <br />
                        <div css={tw`flex`}>
                            <p>
                                <b>Author:</b> {plugin.author ?? <i>Unknown</i>}{' '}
                            </p>
                            <img width={'20'} src={plugin.authorIconUrl} />
                        </div>
                        <b>Uploaded At:</b> {plugin.publishedAt}
                        <br />
                        <b>Latest Version:</b> {plugin.latestReleaseVersionFormatted}
                        <br />
                        <b>Last Release Date:</b> {plugin.latestReleaseAt}
                        <br />
                        <b>Downloads:</b> {plugin.downloadsShortened}
                        <br />
                        <b>Watchers:</b> {plugin.watchersShortened}
                        <br />
                    </div>
                    <div css={tw`mt-8`}>
                        {!installed?.find((i) => i.name.replace('.cs', '') === plugin.name) && (
                            <Switch
                                name={'update'}
                                description={
                                    'This will check every day if a new update for this plugin is released, and if there is, it will install it. (restart needs to be performed manually)'
                                }
                                label={'Automatic Update'}
                                onChange={() => setUpdate(!update)}
                            />
                        )}
                    </div>
                    <div css={tw`mt-8 sm:flex items-center place-content-between`}>
                        <a href={plugin.donateUrl}>
                            <Button color='green'>Donate</Button>
                        </a>
                        {installed?.find((i) => i.name.replace('.cs', '') === plugin.name) ? (
                            <Button.Danger
                                disabled={!framework}
                                onClick={() => remove(plugin.name)}
                                css={tw`mt-4 sm:mt-0 sm:ml-4 w-full sm:w-auto`}
                            >
                                Remove
                            </Button.Danger>
                        ) : (
                            <Button
                                disabled={!framework}
                                onClick={() => install(plugin)}
                                css={tw`mt-4 sm:mt-0 sm:ml-4 w-full sm:w-auto`}
                            >
                                Install
                            </Button>
                        )}
                    </div>
                </Modal>
            )}
            <ConfirmationModal
                title={'Install Oxide'}
                buttonText={'Install Oxide'}
                onConfirmed={installOxide}
                visible={oxideInstall}
                onModalDismissed={() => setOxideInstall(false)}
                showSpinnerOverlay={loading}
            >
                Installing Oxide will restart your server, are you sure you want to continue.
                <br />
                <br />
                Note that it may take a few minutes for oxide to appear to be installed, due to a server restarting
                taking a few minutes.
            </ConfirmationModal>
        </ServerContentBlock>
    );
};