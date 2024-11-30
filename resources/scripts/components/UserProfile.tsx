import React from 'react';
import { NavLink } from 'react-router-dom';
import { Menu, Dropdown, Avatar } from 'antd';
import { UserOutlined, LogoutOutlined, SettingOutlined, DownOutlined } from '@ant-design/icons';
import Gravatar from 'react-gravatar';
import tw from 'twin.macro';

const UserProfile: React.FC<{ email: string, userName: string, rootAdmin: boolean, onTriggerLogout: () => void, closeNav: () => void }> = ({ email, userName, rootAdmin, onTriggerLogout, closeNav }) => {
    const menu = (
        <Menu>
            <Menu.Item key="1" icon={<UserOutlined />}>
                <NavLink to={"/account"} exact onClick={closeNav}>
                    Account
                </NavLink>
            </Menu.Item>
            {rootAdmin && (
                <Menu.Item key="2" icon={<SettingOutlined />}>
                    <a href={"/admin"} rel={"noreferrer"} onClick={closeNav}>
                        Admin
                    </a>
                </Menu.Item>
            )}
            <Menu.Item key="3" icon={<LogoutOutlined />} onClick={onTriggerLogout}>
                Sign Out
            </Menu.Item>
        </Menu>
    );

    return (
        <div css={tw`flex items-center p-4`}>
            <Gravatar email={email} size={40} css={tw`rounded-full`} />
            <div css={tw`ml-4`}>
                <span>{userName}</span>
                <Dropdown overlay={menu} trigger={['click']}>
                    <DownOutlined css={tw`ml-2 cursor-pointer`} />
                </Dropdown>
            </div>
        </div>
    );
};

export default UserProfile;