import React, { useEffect, useState } from 'react';
import PageContentBlock from '@/components/elements/PageContentBlock';
import tw from 'twin.macro';
import { httpErrorToHuman } from '@/api/http';
import useFlash from '@/plugins/useFlash';
import Fade from '@/components/elements/Fade';
import Spinner from '@/components/elements/Spinner';
import getAllTopicsFromCategory from '@/api/knowledgebase/getAllTopicsFromCategory';
import { Button } from '@/components/elements/button/index';
import styled from 'styled-components/macro';
import { breakpoint } from '@/theme';
import { Link, useHistory, useParams } from 'react-router-dom';

interface RouteParams {
    id: string;
}

const Container = styled.div`
    ${tw`flex flex-wrap`};
    & > div {
        ${tw`w-full`};
        ${breakpoint('md')`
            width: calc(50% - 1rem);
        `}
        ${breakpoint('xl')`
            ${tw`w-auto flex-1`};
        `}
    }
`;

export default () => {
    const { addError, clearFlashes } = useFlash();
    const [loading, setLoading] = useState(true);
    const [topics, setTopics] = useState<any[]>([]);

    const params = useParams<RouteParams>();
    const history = useHistory();

    useEffect(() => {
        setLoading(!topics);
        clearFlashes('knowledgebase');

        getAllTopicsFromCategory(params.id)
            .then((topic) => setTopics(topic))
            .catch((error) => {
                console.error(error);
                addError({ key: 'knowledgebase', message: httpErrorToHuman(error) });
            })
            .then(() => setLoading(false));
    }, []);

    return (
        <PageContentBlock title={'Knowledgebase'}>
            {!topics && loading ? (
                <Spinner size={'large'} centered />
            ) : (
                <Fade timeout={150}>
                    <>
                        <Container css={[tw`mb-10 grid gap-4`, topics.length > 0 ? tw`grid-cols-3` : tw`grid-cols-1`]}>
                            {topics.length > 0 ? (
                                topics.map((topic, index) => (
                                    <Link key={index} to={'/knowledgebase/topic/read/' + topic.id}>
                                        <div
                                            css={tw`p-4 rounded bg-gray-700 border border-gray-700 hover:border-gray-500`}
                                        >
                                            <div css={tw`text-lg font-bold`}>{topic.subject}</div>
                                            <div css={tw`italic`}>By {topic.author}</div>
                                            <div css={tw`mt-2`}>Last updated on {topic.updated_at}</div>
                                        </div>
                                    </Link>
                                ))
                            ) : (
                                <p css={tw`text-center text-sm text-neutral-400`}>There are no topics to list.</p>
                            )}
                        </Container>
                        <Button onClick={() => history.push('/knowledgebase')} css={tw`float-right`}>
                            Go back
                        </Button>
                    </>
                </Fade>
            )}
        </PageContentBlock>
    );
};
