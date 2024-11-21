import React, { useEffect, useRef, useState } from 'react';
import { useStoreState } from "easy-peasy";
import { ApplicationStore } from "@/state";
import { Link, NavLink } from 'react-router-dom';
import SearchContainer from "@/components/dashboard/search/SearchContainer";
import tw, { theme } from "twin.macro";
import styled from "styled-components/macro";
import getUserRole from '@/api/getUserRole';
import http from "@/api/http";
import SpinnerOverlay from "@/components/elements/SpinnerOverlay";
import routes from '@/routers/routes';
import Navigation from "@/components/elements/helionix/navigation/Navigation";
import NavigationBar from "@/components/elements/helionix/navigation/NavigationBar";
import LogoContainer from "@/components/elements/helionix/navigation/LogoContainer";
import CategoryContainer from "@/components/elements/helionix/navigation/CategoryContainer";
import NavigationButton from "@/components/elements/helionix/navigation/NavigationButton";
import LcIcon from '@/components/elements/LcIcon';
import { BellRing, History, KeyRound, Layers, LayoutGrid, Lock, LogOut, Rocket, Shield, User } from 'lucide-react';

export default () => {
    const logo = useStoreState((state: ApplicationStore) => state.helionix.data!.logo);
    const name = useStoreState((state: ApplicationStore) => state.settings.data!.name);
    const logo_only = useStoreState((state: ApplicationStore) => state.helionix.data!.logo_only);
    const logo_height = useStoreState((state: ApplicationStore) => state.helionix.data!.logo_height);
    const [userRoleData, setUserRoleData] = React.useState(false);
    
    React.useEffect(() => {
        async function getUserRoleData () {
            const user = await getUserRole();
            setUserRoleData(user.role);
        }
        getUserRoleData();
    }, []);
    const announcement = useStoreState((state: ApplicationStore) => state.helionix.data!.announcements_status);
    const uptime = useStoreState((state: ApplicationStore) => state.helionix.data!.uptime_nodes_status);
    const [isLoggingOut, setIsLoggingOut] = useState(false);
    const [isNavVisible, setIsNavVisible] = useState(false);
    const navRef = useRef<HTMLDivElement>(null);

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
                    <a>Management</a>
                </CategoryContainer>
                <NavLink to={"/account"} exact onClick={closeNav} css={tw`flex`}>
                    <LcIcon icon={User} size={20} />
                    <NavigationButton>Account</NavigationButton>
                </NavLink>
                <NavLink to={"/account/api"} exact onClick={closeNav} css={tw`flex`}>
                    <LcIcon icon={Lock} size={20} />
                    <NavigationButton>API Key</NavigationButton>
                </NavLink>
                <NavLink to={"/account/ssh"} exact onClick={closeNav} css={tw`flex`}>
                    <LcIcon icon={KeyRound} size={20} />
                    <NavigationButton>SSH Key</NavigationButton>
                </NavLink>
                <NavLink to={"/account/activity"} exact onClick={closeNav} css={tw`flex`}>
                    <LcIcon icon={History} size={20} />
                    <NavigationButton>Activity</NavigationButton>
                </NavLink>
                {userRoleData === true && (
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
