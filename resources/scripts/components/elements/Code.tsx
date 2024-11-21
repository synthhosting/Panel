import React from 'react';
import classNames from 'classnames';

interface CodeProps {
    dark?: boolean | undefined;
    className?: string;
    children: React.ReactChild | React.ReactFragment | React.ReactPortal;
}

export default ({ dark, className, children }: CodeProps) => (
    <code
        className={classNames('text-sm px-2 py-1 inline-block rounded-[10px]', className, {
            'bg-helionix-color4': !dark,
            'bg-helionix-color3': dark,
        })}
    >
        {children}
    </code>
);
