import React, { memo } from 'react';
import { LucideProps } from 'lucide-react';
import tw from 'twin.macro';
import isEqual from 'react-fast-compare';
import LcIcon from './LcIcon';

interface Props {
    icon?: React.ComponentType<LucideProps>;
    title: string | React.ReactNode;
    className?: string;
    children: React.ReactNode;
}

const TitledGreyBox = ({ icon, title, children, className }: Props) => (
    <div css={tw`rounded-xl shadow-md bg-helionix-color2`} className={className}>
        <div css={tw`bg-helionix-color2 rounded-xl p-4`}>
            {typeof title === 'string' ? (
                <p css={tw`flex text-lg font-semibold uppercase items-center`}>
                    {icon && <LcIcon icon={icon} css={tw`mr-2`} size={22}/>}
                    {title}
                </p>
            ) : (
                title
            )}
        </div>
        <div css={tw`p-4`}>{children}</div>
    </div>
);

export default memo(TitledGreyBox, isEqual);
