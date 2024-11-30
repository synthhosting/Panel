import React from 'react';
import { NavLink } from 'react-router-dom';
import tw from 'twin.macro';

const UserProfile: React.FC<{ email: string, userName: string, rootAdmin: boolean, onTriggerLogout: () => void, closeNav: () => void }> = ({ email, userName, rootAdmin, onTriggerLogout, closeNav }) => {
    const [menuVisible, setMenuVisible] = React.useState(false);

    const toggleMenu = () => {
        setMenuVisible(!menuVisible);
    };

    return (
        <div css={tw`flex items-center p-4 relative`}>
            <div css={tw`rounded-full bg-gray-300`} style={{ width: 40, height: 40 }} />
            <div css={tw`ml-4`}>
                <span>{userName}</span>
                <svg css={tw`ml-2 cursor-pointer`} onClick={toggleMenu} width="16" height="16" viewBox="0 0 24 24">
                    <path d="M12 16.5l-6-6h12z"/>
                </svg>
                {menuVisible && (
                    <div css={tw`absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg`}>
                        <div css={tw`p-2`}>
                            <NavLink to={"/account"} exact onClick={closeNav} css={tw`flex items-center p-2 hover:bg-gray-100`}>
                                <svg css={tw`mr-2`} width="16" height="16" viewBox="0 0 24 24">
                                    <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                                </svg>
                                Account
                            </NavLink>
                            {rootAdmin && (
                                <a href={"/admin"} rel={"noreferrer"} onClick={closeNav} css={tw`flex items-center p-2 hover:bg-gray-100`}>
                                    <svg css={tw`mr-2`} width="16" height="16" viewBox="0 0 24 24">
                                        <path d="M12 2l-5.5 9h11zM12 22c-1.1 0-2-.9-2-2h4c0 1.1-.9 2-2 2zm-6-4h12v-2h-12v2zm0-4h12v-2h-12v2z"/>
                                    </svg>
                                    Admin
                                </a>
                            )}
                            <div onClick={onTriggerLogout} css={tw`flex items-center p-2 hover:bg-gray-100 cursor-pointer`}>
                                <svg css={tw`mr-2`} width="16" height="16" viewBox="0 0 24 24">
                                    <path d="M10 17l5-5-5-5v10zm-7-5c0 5.52 4.48 10 10 10s10-4.48 10-10-4.48-10-10-10-10 4.48-10 10z"/>
                                </svg>
                                Sign Out
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </div>
    );
};

export default UserProfile;