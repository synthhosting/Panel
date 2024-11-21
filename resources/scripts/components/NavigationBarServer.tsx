import React, { useEffect, useRef, useState } from 'react';
import { 
    BellRing,
    Layers,
    Rocket,
    LayoutGrid,
    ExternalLink,
    User,
    Shield,
    LogOut,
} from "lucide-react"
import { useStoreState } from "easy-peasy";
import { ApplicationStore } from "@/state";
import { ServerContext } from "@/state/server";
import { Link, NavLink, useRouteMatch } from "react-router-dom";
import tw, { theme } from "twin.macro";
import styled from "styled-components/macro";
import http from "@/api/http";
import SpinnerOverlay from "@/components/elements/SpinnerOverlay";
import routes from '@/routers/routes';
import Can from '@/components/elements/Can';
import SearchContainer from "@/components/dashboard/search/SearchContainer";
import Navigation from "@/components/elements/helionix/navigation/Navigation";
import NavigationBar from "@/components/elements/helionix/navigation/NavigationBar";
import LogoContainer from "@/components/elements/helionix/navigation/LogoContainer";
import CategoryContainer from "@/components/elements/helionix/navigation/CategoryContainer";
import NavigationButton from "@/components/elements/helionix/navigation/NavigationButton";
import LcIcon from '@/components/elements/LcIcon';

export default () => {
    const logo = useStoreState((state: ApplicationStore) => state.helionix.data!.logo);
    const name = useStoreState((state: ApplicationStore) => state.settings.data!.name);
    const logo_only = useStoreState((state: ApplicationStore) => state.helionix.data!.logo_only);
    const logo_height = useStoreState((state: ApplicationStore) => state.helionix.data!.logo_height);
    const rootAdmin = useStoreState((state: ApplicationStore) => state.user.data!.rootAdmin);
    const announcement = useStoreState((state: ApplicationStore) => state.helionix.data!.announcements_status);
    const uptime = useStoreState((state: ApplicationStore) => state.helionix.data!.uptime_nodes_status);
    const [isLoggingOut, setIsLoggingOut] = useState(false);
    const [isNavVisible, setIsNavVisible] = useState(false);
    const navRef = useRef<HTMLDivElement>(null);
    const match = useRouteMatch<{ id: string }>();

    const onTriggerLogout = () => {
        setIsLoggingOut(true);
        http.post('/auth/logout').finally(() => {
            // @ts-expect-error this is valid
            window.location = '/';
        });
    };

    const toggleNav = () => {
        setIsNavVisible(!isNavVisible);
    };

    const closeNav = () => {
        setIsNavVisible(false);
    };

    const handleClickOutside = (event: MouseEvent) => {
        if (navRef.current && !navRef.current.contains(event.target as Node) && !document.getElementById('modal-portal')?.contains(event.target as Node)) {
            setIsNavVisible(false);
        }
    };

    React.useEffect(() => {
        if (isNavVisible) {
            document.addEventListener("mousedown", handleClickOutside);
        } else {
            document.removeEventListener("mousedown", handleClickOutside);
        }
        return () => {
            document.removeEventListener("mousedown", handleClickOutside);
        };
  }, [isNavVisible]);

  const serverId = ServerContext.useStoreState(
    (state) => state.server.data?.internalId
  );

  const to = (value: string, url = false) => {
    if (value === "/") {
        return url ? match.url : match.path;
    }
    return `${(url ? match.url : match.path).replace(
        /\/*$/,
        ""
    )}/${value.replace(/^\/+/, "")}`;
  };

    return (
      <>
        <header css={tw`flex mx-4 mt-4 rounded-2xl justify-between items-center p-4 bg-helionix-color2 shadow xl:hidden`}>
            <Link to={"/"} css={tw`flex items-center`}>
                <img src={logo} alt="Logo" css={tw`h-8 mr-2`} />
                {logo_only == false &&
                    <a css={tw`text-xl font-semibold whitespace-nowrap`}>{name}</a>
                }
            </Link>
            <button onClick={toggleNav}>
                <LcIcon icon={LayoutGrid} size={20} />
            </button>
        </header>
        {isNavVisible && <div css={tw`fixed inset-0 bg-black opacity-50 z-[11]`} />}
        <Navigation ref={navRef} isVisible={isNavVisible}>
            <Link to={"/"}>
                <LogoContainer>
                    <img src={logo} alt="Logo" css={`height:${logo_height};`}/>
                    {logo_only == false &&
                        <a>{name}</a>
                    }
                </LogoContainer>
            </Link>
            <NavigationBar>
                <CategoryContainer>
                    <a>Dashboard</a>
                </CategoryContainer>
                {announcement == true &&
                    <NavLink to={"/announcement"} exact onClick={closeNav} css={tw`flex`}>
                        <LcIcon icon={BellRing} size={20} />
                        <NavigationButton>Announcement</NavigationButton>
                    </NavLink>
                }
                <SearchContainer />
                <NavLink to={"/"} exact onClick={closeNav} css={tw`flex`}>
                    <LcIcon icon={Layers} size={20} />
                    <NavigationButton>Servers</NavigationButton>
                </NavLink>
                {uptime == true &&
                    <NavLink to={"/uptime"} exact onClick={closeNav} css={tw`flex`}>
                        <LcIcon icon={Rocket} size={20} />
                        <NavigationButton>Uptime</NavigationButton>
                    </NavLink>
                }
                <CategoryContainer>
                    <a>Server Control</a>
                </CategoryContainer>
                {routes.server
                    .filter((route) => !!route.name)
                    .map((route) =>
                    route.permission ? (
                        (!route.nestId || route.nestId === serverNestId) && (
                            <Can key={route.path} action={route.permission} matchAny>
                                <NavLink to={to(route.path, true)} exact={route.exact} css={tw`flex items-center`}>
                                    <LcIcon icon={route.icon} size={20}/>
                                    <NavigationButton>{route.name}</NavigationButton>
                                </NavLink>
                            </Can>
                        )
                    ) : (
                        <NavLink key={route.path} to={to(route.path, true)} exact={route.exact} css={tw`flex items-center`}>
                            <LcIcon icon={route.icon} size={20} />
                            <NavigationButton>{route.name}</NavigationButton>
                        </NavLink>
                    )
                    )}
                {rootAdmin && (
                    // eslint-disable-next-line react/jsx-no-target-blank
                    <a href={`/admin/servers/view/${serverId}`} target={"_blank"} css={tw`flex`}>
                        <LcIcon icon={ExternalLink} size={20} />
                        <NavigationButton>Manage</NavigationButton>
                    </a>
                )}
                <CategoryContainer>
                    <a>Management</a>
                </CategoryContainer>
                <NavLink to={"/account"} exact onClick={closeNav} css={tw`flex`}>
                    <LcIcon icon={User} size={20} />
                    <NavigationButton>Account</NavigationButton>
                </NavLink>
                {rootAdmin && (
                    <a href={"/admin"} rel={"noreferrer"} onClick={closeNav} css={tw`flex`}>
                        <LcIcon icon={Shield} size={20}/>
                        <NavigationButton>Admin</NavigationButton>
                    </a>
                )}
                <a onClick={onTriggerLogout} css={tw`flex`}>
                    <LcIcon icon={LogOut} size={20}/>
                    <NavigationButton>Sign Out</NavigationButton>
                </a>
            </NavigationBar>
        </Navigation>
      </>
    );
};
