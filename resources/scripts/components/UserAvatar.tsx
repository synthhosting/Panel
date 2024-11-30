import * as React from 'react';
import { useStoreState } from 'easy-peasy';
import { ApplicationStore } from '@/state';

interface Props {
    email?: string;
    width?: string;
    rounded?: string;
}

export default ({ email, width, rounded }: Props) => {
    const useremail = useStoreState((state: ApplicationStore) => state.user.data?.email);

    return (
        <img
            src={`https://www.gravatar.com/avatar/${email ? email : useremail}`}
            width={width ? width : '32px'}
            className={rounded ? rounded : 'rounded-full'}
            alt='Gravatar'
        />
    );
};