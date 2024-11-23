import React, { useEffect, useState } from 'react';
import PageContentBlock from '@/components/elements/PageContentBlock';
import tw from 'twin.macro';
import { httpErrorToHuman } from '@/api/http';
import useFlash from '@/plugins/useFlash';
import Fade from '@/components/elements/Fade';
import Spinner from '@/components/elements/Spinner';
import getAllCategories from '@/api/knowledgebase/getAllCategories';
import styled from 'styled-components/macro';
import { breakpoint } from '@/theme';
import { Link } from 'react-router-dom';

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
    const [categories, setCategories] = useState<any[]>([]);

    useEffect(() => {
        setLoading(!categories);
        clearFlashes('knowledgebase');

        getAllCategories()
            .then((categorylist) => setCategories(categorylist))
            .catch((error) => {
                console.error(error);
                addError({ key: 'knowledgebase', message: httpErrorToHuman(error) });
            })
            .then(() => setLoading(false));
    }, []);

    return (
        <PageContentBlock title={'Knowledgebase'}>
            {!categories && loading ? (
                <Spinner size={'large'} centered />
            ) : (
                <Fade timeout={150}>
                    <>
                        <Container
                            css={[tw`mb-10 grid gap-4`, categories.length > 0 ? tw`grid-cols-3` : tw`grid-cols-1`]}
                        >
                            {categories.length > 0 ? (
                                categories.map((category, index) => (
                                    <Link key={index} to={'/knowledgebase/category/' + category.id}>
                                        <div
                                            css={tw`p-4 rounded bg-gray-700 border border-gray-700 hover:border-gray-500`}
                                        >
                                            <div css={tw`text-lg font-bold`}>{category.name}</div>
                                            <div css={tw`mt-2`}>{category.description}</div>
                                        </div>
                                    </Link>
                                ))
                            ) : (
                                <p css={tw`text-center text-sm text-neutral-400`}>There are no categories to list.</p>
                            )}
                        </Container>
                    </>
                </Fade>
            )}
        </PageContentBlock>
    );
};
