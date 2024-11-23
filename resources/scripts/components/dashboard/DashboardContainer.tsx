import React, { useEffect, useState } from 'react';
import { Server } from '@/api/server/getServer';
import getServers from '@/api/getServers';
import ServerRow from '@/components/dashboard/ServerRow';
import Spinner from '@/components/elements/Spinner';
import PageContentBlock from '@/components/elements/PageContentBlock';
import useFlash from '@/plugins/useFlash';
import { useStoreState } from 'easy-peasy';
import { ApplicationStore } from "@/state";
import { usePersistedState } from '@/plugins/usePersistedState';
import Switch from '@/components/elements/Switch';
import tw from 'twin.macro';
import useSWR from 'swr';
import { PaginatedResult } from '@/api/http';
import Pagination from '@/components/elements/Pagination';
import { useLocation } from 'react-router-dom';
import LcIcon from '@/components/elements/LcIcon';
import { CircleHelp, CreditCard, Globe, Rocket } from 'lucide-react';
import { faCogs, faLayerGroup, faSignOutAlt, faFolderOpen } from '@fortawesome/free-solid-svg-icons';

export default () => {
    const layout = useStoreState((state: ApplicationStore) => state.helionix.data!.dash_layout);
    const dash_billing_status = useStoreState((state: ApplicationStore) => state.helionix.data!.dash_billing_status);
    const dash_billing_url = useStoreState((state: ApplicationStore) => state.helionix.data!.dash_billing_url);
    const dash_billing_blank = useStoreState((state: ApplicationStore) => state.helionix.data!.dash_billing_blank);
    const dash_website_status = useStoreState((state: ApplicationStore) => state.helionix.data!.dash_website_status);
    const dash_support_status = useStoreState((state: ApplicationStore) => state.helionix.data!.dash_support_status);
    const dash_support_url = useStoreState((state: ApplicationStore) => state.helionix.data!.dash_support_url);
    const dash_support_blank= useStoreState((state: ApplicationStore) => state.helionix.data!.dash_support_blank);
    const dash_uptime_status = useStoreState((state: ApplicationStore) => state.helionix.data!.dash_uptime_status);
    const dash_uptime_url = useStoreState((state: ApplicationStore) => state.helionix.data!.dash_uptime_url);
    const dash_uptime_blank = useStoreState((state: ApplicationStore) => state.helionix.data!.dash_uptime_blank);
    const dash = [
        { status: dash_billing_status, url: dash_billing_url, blank: dash_billing_blank, icon: CreditCard, title: "Billing", description: "Manage your service" },
        { status: dash_website_status, url: '/knowledgebase', blank: false, icon: Globe, title: "Knowledge Base", description: "Visit our knowledge base" },
        { status: dash_support_status, url: dash_support_url, blank: dash_support_blank, icon: CircleHelp, title: "Support", description: "Get our support" },
        { status: dash_uptime_status, url: dash_uptime_url, blank: dash_uptime_blank, icon: Rocket, title: "Status", description: "Check node status" },
    ];
    

    const { search } = useLocation();
    const defaultPage = Number(new URLSearchParams(search).get('page') || '1');

    const [page, setPage] = useState(!isNaN(defaultPage) && defaultPage > 0 ? defaultPage : 1);
    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const uuid = useStoreState((state) => state.user.data!.uuid);
    const [showOnlyAdmin, setShowOnlyAdmin] = usePersistedState(`${uuid}:show_all_servers`, false);

    const { data: servers, error } = useSWR<PaginatedResult<Server>>(
        ['/api/client/servers', showOnlyAdmin, page],
        () => getServers({ page, type: showOnlyAdmin ? 'admin' : undefined })
    );

    useEffect(() => {
        if (!servers) return;
        if (servers.pagination.currentPage > 1 && !servers.items.length) {
            setPage(1);
        }
    }, [servers?.pagination.currentPage]);

    useEffect(() => {
        // Don't use react-router to handle changing this part of the URL, otherwise it
        // triggers a needless re-render. We just want to track this in the URL incase the
        // user refreshes the page.
        window.history.replaceState(null, document.title, `/${page <= 1 ? '' : `?page=${page}`}`);
    }, [page]);

    useEffect(() => {
        if (error) clearAndAddHttpError({ key: 'dashboard', error });
        if (!error) clearFlashes('dashboard');
    }, [error]);

    return (
        <PageContentBlock title={'Dashboard'} showFlashKey={'dashboard'}>
            <div css={tw`w-full`}>
                <div css={tw`flex flex-wrap gap-2 mb-2`}>
                    {dash.filter(dash => dash.status == true).map((dash, index) => (
                        <a 
                            key={index}
                            href={dash.url}
                            target={dash.blank == true ? '_blank' : undefined} 
                            css={tw`flex-1 h-20 bg-helionix-color2 rounded-xl p-4 hover:border-[#1873d3] border-2 border-transparent transition-colors duration-150`}
                        >
                            <div css={tw`flex items-center`}>
                                <div className={'w-auto mr-4'}>
                                    <LcIcon icon={dash.icon} size={32} />
                                </div>
                                <div>
                                    <p css={tw`text-lg break-words`}>{dash.title}</p>
                                    <span css={tw`text-sm break-words line-clamp-1`}>{dash.description}</span>
                                </div>
                            </div>
                        </a>
                    ))}
                </div>
                <div css={tw`mb-2 flex justify-end items-center`}>
                    <p css={tw`uppercase text-xs text-neutral-400 mr-2`}>
                        {showOnlyAdmin ? "Showing others' servers" : 'Showing your servers'}
                    </p>
                    <Switch
                        name={'show_all_servers'}
                        defaultChecked={showOnlyAdmin}
                        onChange={() => setShowOnlyAdmin((s) => !s)}
                    />
                </div>
                {!servers ? (
                    <Spinner centered size={'large'} />
                ) : (layout == 1 ?
                    <Pagination data={servers} onPageSelect={setPage}>
                        {({ items }) =>
                            items.length > 0 ? (
                                <div css={tw`grid lg:grid-cols-2 gap-2`}>
                                    {items.map((server, index) => (
                                        <ServerRow key={server.uuid} server={server} css={index > 0 ? tw`mt-2` : undefined} />
                                    ))}
                                </div>
                            ) : (
                            <p css={tw`text-center text-sm mt-8`}>
                                    {showOnlyAdmin
                                        ? 'There are no other servers to display.'
                                        : 'There are no servers associated with your account.'}
                                </p>
                            )
                        }
                    </Pagination>
                    : layout == 2 ?
                    <Pagination data={servers} onPageSelect={setPage}>
                        {({ items }) =>
                            items.length > 0 ? (
                                <div css={tw`grid lg:grid-cols-2 gap-2`}>
                                    {items.map((server, index) => (
                                        <ServerRow key={server.uuid} server={server} css={index > 0 ? tw`mt-2` : undefined} />
                                    ))}
                                </div>
                            ) : (
                                <p css={tw`text-center text-sm mt-8`}>
                                    {showOnlyAdmin
                                        ? 'There are no other servers to display.'
                                        : 'There are no servers associated with your account.'}
                                </p>
                            )
                        }
                    </Pagination>
                    :
                    <Pagination data={servers} onPageSelect={setPage}>
                        {({ items }) =>
                            items.length > 0 ? (
                                items.map((server, index) => (
                                    <ServerRow key={server.uuid} server={server} css={index > 0 ? tw`mt-2` : undefined} />
                                ))
                            ) : (
                                <p css={tw`text-center text-sm mt-8`}>
                                    {showOnlyAdmin
                                        ? 'There are no other servers to display.'
                                        : 'There are no servers associated with your account.'}
                                </p>
                            )
                        }
                    </Pagination>
                )}
            </div>
        </PageContentBlock>
    );
};