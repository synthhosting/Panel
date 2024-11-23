import React, { useEffect, useState } from 'react';
import PageContentBlock from '@/components/elements/PageContentBlock';
import tw from 'twin.macro';
import { httpErrorToHuman } from '@/api/http';
import useFlash from '@/plugins/useFlash';
import Fade from '@/components/elements/Fade';
import Spinner from '@/components/elements/Spinner';
import getTopic, { KnowledgebaseTopic } from '@/api/knowledgebase/getTopic';
import { Button } from '@/components/elements/button/index';
import ReactHtmlParser from 'react-html-parser';
import { useHistory, useParams } from 'react-router-dom';
import { useStoreState } from 'easy-peasy';

interface RouteParams {
    id: string;
}

export default () => {
    const { addError, clearFlashes } = useFlash();
    const [loading, setLoading] = useState(true);
    const [topic, setTopic] = useState<KnowledgebaseTopic>();
    const rootAdmin = useStoreState((state) => state.user.data!.rootAdmin);

    const params = useParams<RouteParams>();
    const history = useHistory();

    useEffect(() => {
        setLoading(!topic);
        clearFlashes('topic');

        getTopic(params.id)
            .then((topicc) => {
                setTopic(topicc);
            })
            .catch((error) => {
                console.error(error);
                addError({ key: 'topic', message: httpErrorToHuman(error) });
            })
            .then(() => setLoading(false));
    }, []);

    return (
        <PageContentBlock title={'Knowledgebase'}>
            {!topic && loading ? (
                <Spinner size={'large'} centered />
            ) : (
                <Fade timeout={150}>
                    <>
                        <p css={tw`text-xl font-bold`}>{topic!.subject}</p>
                        <div css={tw`mt-2 p-4 bg-gray-700 rounded`}>{ReactHtmlParser(topic!.information)}</div>
                        <div css={tw`mt-4 p-4 bg-gray-700 rounded`}>
                            <p>Written by {topic!.author}</p>
                            <p>Last updated on {topic!.updated_at}</p>
                        </div>
                        <div css={tw`mt-4`}>
                            <Button
                                onClick={() => history.push(`/knowledgebase/category/${topic!.category}`)}
                                css={tw`float-right`}
                            >
                                Go back
                            </Button>
                            {rootAdmin && (
                                <a
                                    href={'/admin/knowledgebase/topics/edit/' + topic!.id}
                                    target={'_blank'}
                                    rel={'noreferrer'}
                                    css={tw`mr-4 float-right`}
                                >
                                    <Button.Text>Edit</Button.Text>
                                </a>
                            )}
                        </div>
                    </>
                </Fade>
            )}
        </PageContentBlock>
    );
};
