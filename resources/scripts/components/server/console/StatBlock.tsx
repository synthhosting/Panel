import React from 'react';
import Icon from '@/components/elements/Icon';
import { LucideProps } from 'lucide-react';
import classNames from 'classnames';
import styles from './style.module.css';
import useFitText from 'use-fit-text';
import CopyOnClick from '@/components/elements/CopyOnClick';
import LcIcon from '@/components/elements/LcIcon';

interface StatBlockProps {
    title: string;
    copyOnClick?: string;
    color?: string | undefined;
    icon: React.ComponentType<LucideProps>;
    children: React.ReactNode;
    className?: string;
}

export default ({ title, copyOnClick, icon, color, className, children }: StatBlockProps) => {
    const { fontSize, ref } = useFitText({ minFontSize: 8, maxFontSize: 500 });

    return (
        <CopyOnClick text={copyOnClick}>
            <div className={classNames(styles.stat_block, 'bg-helionix-color2', className)}>
                <div className={classNames(styles.status_bar, color || 'bg-helionix-btnPrimary')} />
                <div className={classNames(styles.icon, color || 'bg-helionix-btnPrimary')}>
                    <LcIcon icon={icon} size={24}/>
                </div>
                <div className={'flex flex-col justify-center overflow-hidden w-full'}>
                    <p className={'text-xs md:text-sm'}>{title}</p>
                    <div
                        ref={ref}
                        className={'h-[1.75rem] w-full font-semibold truncate'}
                        style={{ fontSize }}
                    >
                        {children}
                    </div>
                </div>
            </div>
        </CopyOnClick>
    );
};
