import React from 'react';
import { Route, Switch, useRouteMatch } from 'react-router-dom';
import LoginContainer from '@/components/auth/LoginContainer';
import RegisterContainer from '@/components/auth/RegisterContainer';
import ForgotPasswordContainer from '@/components/auth/ForgotPasswordContainer';
import ResetPasswordContainer from '@/components/auth/ResetPasswordContainer';
import LoginCheckpointContainer from '@/components/auth/LoginCheckpointContainer';
import { NotFound } from '@/components/elements/ScreenBlock';
import { useHistory, useLocation } from 'react-router';
import { useStoreState } from 'easy-peasy';
import { ApplicationStore } from '@/state';
import tw from 'twin.macro';

export default () => {
    const title = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_title);
    const description = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_description);
    const layout = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_layout);
    const auth_register_status = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_register_status);

    const history = useHistory();
    const location = useLocation();
    const { path } = useRouteMatch();

    return (
        <>
        {layout == 1 ?
        <div className="auth-container">
            <div className="side-container">
                <div className="w-full">
                    <h1 className="title">{title}</h1>
                    <p className="description">{description}</p>
                </div>
            </div>
            <div className={'form-container'}>
                <Switch location={location}>
                <Route path={`${path}/login`} component={LoginContainer} exact />
                {auth_register_status == true &&
                    <Route path={`${path}/register`} component={RegisterContainer} exact />
                }
                <Route path={`${path}/login/checkpoint`} component={LoginCheckpointContainer} />
                <Route path={`${path}/password`} component={ForgotPasswordContainer} exact />
                <Route path={`${path}/password/reset/:token`} component={ResetPasswordContainer} />
                <Route path={`${path}/checkpoint`} />
                <Route path={'*'}>
                    <NotFound onBack={() => history.push('/auth/login')} />
                    </Route>
                </Switch>
            </div>
        </div> 
        : layout == 2 ?
        <div css={tw`absolute w-full h-full flex flex-col justify-center`}>
            <Switch location={location}>
                <Route path={`${path}/login`} component={LoginContainer} exact />
                {auth_register_status == true &&
                    <Route path={`${path}/register`} component={RegisterContainer} exact />
                }
                <Route path={`${path}/login/checkpoint`} component={LoginCheckpointContainer} />
                <Route path={`${path}/password`} component={ForgotPasswordContainer} exact />
                <Route path={`${path}/password/reset/:token`} component={ResetPasswordContainer} />
                <Route path={`${path}/checkpoint`} />
                <Route path={'*'}>
                    <NotFound onBack={() => history.push('/auth/login')} />
                </Route>
            </Switch>
        </div>
        : 
        <div css={tw`absolute w-full h-full flex flex-col justify-center`}>
            <Switch location={location}>
                <Route path={`${path}/login`} component={LoginContainer} exact />
                {auth_register_status == true &&
                    <Route path={`${path}/register`} component={RegisterContainer} exact />
                }
                <Route path={`${path}/login/checkpoint`} component={LoginCheckpointContainer} />
                <Route path={`${path}/password`} component={ForgotPasswordContainer} exact />
                <Route path={`${path}/password/reset/:token`} component={ResetPasswordContainer} />
                <Route path={`${path}/checkpoint`} />
                <Route path={'*'}>
                    <NotFound onBack={() => history.push('/auth/login')} />
                </Route>
            </Switch>
        </div>
        }
        </>
    );
};