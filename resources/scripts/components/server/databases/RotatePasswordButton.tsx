import React, { useState } from 'react';
import rotateDatabasePassword from '@/api/server/databases/rotateDatabasePassword';
import { Actions, useStoreActions } from 'easy-peasy';
import { ApplicationStore } from '@/state';
import { ServerContext } from '@/state/server';
import { ServerDatabase } from '@/api/server/databases/getServerDatabases';
import { httpErrorToHuman } from '@/api/http';
import { Button } from '@/components/elements/button/index';
import tw from 'twin.macro';

export default ({ databaseId, onUpdate }: { databaseId: string; onUpdate: (database: ServerDatabase) => void }) => {
    const [loading, setLoading] = useState(false);
    const { addFlash, clearFlashes } = useStoreActions((actions: Actions<ApplicationStore>) => actions.flashes);
    const server = ServerContext.useStoreState((state) => state.server.data!);

    if (!databaseId) {
        return null;
    }

    const rotate = () => {
        setLoading(true);
        clearFlashes();

        rotateDatabasePassword(server.uuid, databaseId)
            .then((database) => onUpdate(database))
            .catch((error) => {
                console.error(error);
                addFlash({
                    type: 'error',
                    title: 'Error',
                    message: httpErrorToHuman(error),
                    key: 'database-connection-modal',
                });
            })
            .then(() => setLoading(false));
    };

    return (
        <Button.Danger css={tw`mr-2`} onClick={rotate} variant={Button.Variants.Secondary}>
            Rotate Password
        </Button.Danger>
    );
};
