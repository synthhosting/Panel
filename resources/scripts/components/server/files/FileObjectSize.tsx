import React, { useState } from 'react';
import tw from 'twin.macro';
import { bytesToString } from '@/lib/formatters';
import { faCalculator } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { ServerContext } from '@/state/server';
import getFolderSize from '@/api/server/files/getFolderSize';
import { join } from 'path';
import useFlash from '@/plugins/useFlash';
import Spinner from '@/components/elements/Spinner';

interface Props {
    file: any;
}

export default ({ file }: Props) => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const directory = ServerContext.useStoreState((state) => state.files.directory);

    const { clearAndAddHttpError } = useFlash();

    const [size, setSize] = useState(-1);

    const calculateSize = (e: React.MouseEvent<HTMLDivElement>) => {
        e.preventDefault();
        setSize(-2);

        getFolderSize(uuid, join(directory, file.name))
            .then((data) => {
                setSize(data.size);
            })
            .catch((error) => {
                clearAndAddHttpError({ key: 'files', error });
                setSize(-1); 
            });
    };

    return (
        <>
            {file.isFile ? (
                <div css={tw`w-1/6 text-right mr-4 block`}>{bytesToString(file.size)}</div>
            ) : (
                <>
                    {size === -1 ? (
                        <div css={tw`text-right mr-4 sm:block`} onClick={calculateSize}>
                            <FontAwesomeIcon icon={faCalculator} />
                        </div>
                    ) : (
                        <>
                            {size === -2 ? (
                                <Spinner size={'small'} />
                            ) : (
                                <div css={tw`w-1/6 text-right mr-4`}>{bytesToString(size)}</div>
                            )}
                        </>
                    )}
                </>
            )}
        </>
    );
};
