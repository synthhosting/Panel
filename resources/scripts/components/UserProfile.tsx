import React from 'react';
import { NavLink } from 'react-router-dom';
import { UserOutlined, LogoutOutlined, SettingOutlined, DownOutlined } from '@ant-design/icons';
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
                <DownOutlined css={tw`ml-2 cursor-pointer`} onClick={toggleMenu} />
                {menuVisible && (
                    <div css={tw`absolute right-0 mt-2 w-48 bg-white border rounded shadow-lg`}>
                        <div css={tw`p-2`}>
                            <NavLink to={"/account"} exact onClick={closeNav} css={tw`flex items-center p-2 hover:bg-gray-100`}>
                                <UserOutlined css={tw`mr-2`} />
                                Account
                            </NavLink>
                            {rootAdmin && (
                                <a href={"/admin"} rel={"noreferrer"} onClick={closeNav} css={tw`flex items-center p-2 hover:bg-gray-100`}>
                                    <SettingOutlined css={tw`mr-2`} />
                                    Admin
                                </a>
                            )}
                            <div onClick={onTriggerLogout} css={tw`flex items-center p-2 hover:bg-gray-100 cursor-pointer`}>
                                <LogoutOutlined css={tw`mr-2`} />
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