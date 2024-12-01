import React, { memo } from 'react';
import { LucideProps } from 'lucide-react';
import tw from 'twin.macro';
import isEqual from 'react-fast-compare';
import LcIcon from './LcIcon';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faExternalLinkAlt } from '@fortawesome/free-solid-svg-icons';

interface Props {
    icon?: React.ComponentType<LucideProps>;
    title: string | React.ReactNode;
    link?: string;
    className?: string;
    children: React.ReactNode;
}

const TitledGreyBox = ({ icon, title, link, children, className }: Props) => (
    <div css={tw`rounded-xl shadow-md bg-helionix-color2`} className={className}>
        <div css={tw`bg-helionix-color2 rounded-xl p-4`}>
            {typeof title === 'string' ? (
                <p css={tw`text-sm uppercase flex items-center text-white`}>
                    {icon && <LcIcon icon={icon} css={tw`mr-2 text-neutral-300`} />}
                    {title}
                    {link && (
                        <span css={tw`ml-auto`}>
                            <a href={link} target='_blank' rel='noreferrer'>
                                <FontAwesomeIcon icon={faExternalLinkAlt} css={tw`mr-2 text-neutral-300`} />
                            </a>
                        </span>
                    )}
                </p>
            ) : (
                <span css={tw`text-white`}>{title}</span>
            )}
        </div>
        <div css={tw`p-4 text-white`}>{children}</div>
    </div>
);

export default memo(TitledGreyBox, isEqual);