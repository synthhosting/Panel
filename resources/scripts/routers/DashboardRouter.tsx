import React from 'react';
import { NavLink, Route, Switch } from 'react-router-dom';
import NavigationBar from '@/components/NavigationBar';
import DashboardContainer from '@/components/dashboard/DashboardContainer';
import UptimeContainer from '@/components/dashboard/UptimeContainer';
import AnnouncementContainer from '@/components/dashboard/AnnouncementContainer';
import { NotFound } from '@/components/elements/ScreenBlock';
import TransitionRouter from '@/TransitionRouter';
import SubNavigation from '@/components/elements/SubNavigation';
import { useLocation } from 'react-router';
import Spinner from '@/components/elements/Spinner';
import routes from '@/routers/routes';
import tw from "twin.macro"; 
import { useStoreState } from "easy-peasy";
import { ApplicationStore } from "@/state";
import Alert from '@/components/elements/helionix/alert/Alert';


export default () => {
    const AlertType = useStoreState((state: ApplicationStore) => state.helionix.data!.alert_type);
    const uptime = useStoreState((state: ApplicationStore) => state.helionix.data!.uptime_nodes_status);
    const announcement = useStoreState((state: ApplicationStore) => state.helionix.data!.announcements_status);
    const location = useLocation();

    return (
        <>
            <NavigationBar />
            <div css={tw`mx-auto w-full`}>
                {AlertType != 'disable' &&
                    <div css={tw`max-w-[1200px] m-4 xl:mx-auto`}>
                        <Alert />
                    </div>
                }
                <TransitionRouter>
                    <React.Suspense fallback={<Spinner centered />}>
                        <Switch location={location}>
                            {announcement == true &&
                                <Route path={'/announcement'} exact>
                                    <AnnouncementContainer />
                                </Route>
                            }
                            <Route path={'/'} exact>
                                <DashboardContainer />
                            </Route>
                            {uptime == true &&
                                <Route path={'/uptime'} exact>
                                    <UptimeContainer />
                                </Route>
                            }
                            {routes.account.map(({ path, component: Component }) => (
                                <Route key={path} path={`/account/${path}`.replace('//', '/')} exact>
                                    <Component />
                                </Route>
                            ))}
                            <Route path={'*'}>
                                <NotFound />
                            </Route>
                        </Switch>
                    </React.Suspense>
                </TransitionRouter>
            </div>
        </>
    );
};
