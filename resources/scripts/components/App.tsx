import React, { lazy } from 'react';
import { hot } from 'react-hot-loader/root';
import { Route, Router, Switch } from 'react-router-dom';
import { StoreProvider } from 'easy-peasy';
import { store } from '@/state';
import { SiteSettings } from '@/state/settings';
import ProgressBar from '@/components/elements/ProgressBar';
import { NotFound } from '@/components/elements/ScreenBlock';
import tw from 'twin.macro';
import GlobalStylesheet from '@/assets/css/GlobalStylesheet';
import { history } from '@/components/history';
import { setupInterceptors } from '@/api/interceptors';
import AuthenticatedRoute from '@/components/elements/AuthenticatedRoute';
import { ServerContext } from '@/state/server';
import '@/assets/tailwind.css';
import Spinner from '@/components/elements/Spinner';
import { HelionixSettings } from '@/state/helionix';

const DashboardRouter = lazy(() => import(/* webpackChunkName: "dashboard" */ '@/routers/DashboardRouter'));
const ServerRouter = lazy(() => import(/* webpackChunkName: "server" */ '@/routers/ServerRouter'));
const AuthenticationRouter = lazy(() => import(/* webpackChunkName: "auth" */ '@/routers/AuthenticationRouter'));

interface ExtendedWindow extends Window {
    SiteConfiguration?: SiteSettings;
    HelionixConfiguration?: HelionixSettings;
    PterodactylUser?: {
        uuid: string;
        username: string;
        email: string;
        /* eslint-disable camelcase */
        root_admin: boolean;
        use_totp: boolean;
        language: string;
        updated_at: string;
        created_at: string;
        /* eslint-enable camelcase */
    };
}

setupInterceptors(history);

const App = () => {
    const { PterodactylUser, SiteConfiguration, HelionixConfiguration } = window as ExtendedWindow;
    if (PterodactylUser && !store.getState().user.data) {
        store.getActions().user.setUserData({
            uuid: PterodactylUser.uuid,
            username: PterodactylUser.username,
            email: PterodactylUser.email,
            language: PterodactylUser.language,
            rootAdmin: PterodactylUser.root_admin,
            useTotp: PterodactylUser.use_totp,
            createdAt: new Date(PterodactylUser.created_at),
            updatedAt: new Date(PterodactylUser.updated_at),
        });
    }

    if (!store.getState().settings.data) {
        store.getActions().settings.setSettings(SiteConfiguration!);
    }

    if (!store.getState().helionix.data) {
        store.getActions().helionix.setSettings(HelionixConfiguration!);
    }

    return (
        <>
            <GlobalStylesheet />
            <StoreProvider store={store}>
                <ProgressBar />
                <Router history={history}>
                    <Switch>
                        <Route path={"/auth"}>
                            <div css={tw`mx-auto w-auto h-[100vh]`}>
                                <Spinner.Suspense>
                                    <AuthenticationRouter />
                                </Spinner.Suspense>
                            </div>
                        </Route>
                        <AuthenticatedRoute path={"/server/:id"}>
                        <div css={tw`xl:flex justify-center ml-auto mr-auto`}>
                            <Spinner.Suspense>
                                <ServerContext.Provider>
                                    <ServerRouter />
                                </ServerContext.Provider>
                            </Spinner.Suspense>
                        </div>
                        </AuthenticatedRoute>
                        <AuthenticatedRoute path={"/"}>
                            <div css={tw`xl:flex justify-center ml-auto mr-auto`}>
                                <Spinner.Suspense>
                                    <DashboardRouter />
                                </Spinner.Suspense>
                            </div>
                        </AuthenticatedRoute>
                        <Route path={"*"}>
                        <NotFound />
                        </Route>
                    </Switch>
                </Router>
            </StoreProvider>
        </>
    );
};

export default hot(App);
