import React, { forwardRef } from 'react';
import { Form } from 'formik';
import styled from 'styled-components/macro';
import { breakpoint } from '@/theme';
import FlashMessageRender from '@/components/FlashMessageRender';
import tw from 'twin.macro';
import { useStoreState } from 'easy-peasy';
import { ApplicationStore } from '@/state';

type Props = React.DetailedHTMLProps<React.FormHTMLAttributes<HTMLFormElement>, HTMLFormElement> & {
    title?: string;
};

const Container = styled.div`
    ${breakpoint('sm')`
        ${tw`w-4/5 mx-auto p-[10px]`}
    `};

    ${breakpoint('md')`
        ${tw`w-3/5 p-[10px]`}
    `};

    ${breakpoint('lg')`
        ${tw`w-3/5 p-[10px]`}
    `};

    ${breakpoint('xl')`
        ${tw`w-full p-[10px]`}
        max-width: 700px;
    `};
`;


export default forwardRef<HTMLFormElement, Props>(({ title, ...props }, ref) => {
    const logo = useStoreState((state: ApplicationStore) => state.helionix.data!.logo);
    const layout = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_layout);

    return (
        <>
        {
            layout == 1 ?
            <Container className="authentication-container">
                <FlashMessageRender css={tw`mb-2 px-1`} />
                <Form {...props} ref={ref}>
                    <div css={tw`md:flex w-full bg-helionix-color2 shadow-lg rounded-[20px] p-6 mx-1`}>
                        <div css={tw`flex-1`}>
                            {title && <h2 css={tw`text-2xl font-medium pb-4`}>{title}</h2>}
                            {props.children}
                        </div>
                    </div>
                </Form>
                <p css={tw`text-center text-xs mt-4`}>
                    <a
                        rel={'noopener nofollow noreferrer'}
                        href={'https://pterodactyl.io'}
                        target={'_blank'}
                        css={tw`no-underline`}
                    >
                        Pterodactyl&reg;
                    </a>
                    &nbsp;&copy; 2015 - {new Date().getFullYear()}
                </p>
                <p css={tw`text-center text-xs`}>
                    <a
                        rel={'noopener nofollow noreferrer'}
                        href={'https://synthhosting.com/'}
                        target={'_blank'}
                        css={tw`no-underline`}
                    >
                        Designed by SynthHosting
                    </a>
                </p>
            </Container>
            : layout == 2 ?
            <div css={tw`w-full mx-auto p-[10px] md:max-w-[550px] xl:max-w-[550px]`}>
                <FlashMessageRender css={tw`mb-2 px-1`} />
                <Form {...props} ref={ref}>
                    <div css={tw`md:flex w-full bg-helionix-color2 shadow-lg rounded-[20px] p-6 mx-1`}>
                        <div css={tw`flex-1`}>
                            {title && <h2 css={tw`text-2xl font-medium pb-4`}>{title}</h2>}
                            {props.children}
                        </div>
                    </div>
                </Form>
                <p css={tw`text-center text-xs mt-4`}>
                    <a
                        rel={'noopener nofollow noreferrer'}
                        href={'https://pterodactyl.io'}
                        target={'_blank'}
                        css={tw`no-underline`}
                    >
                        Pterodactyl&reg;
                    </a>
                    &nbsp;&copy; 2015 - {new Date().getFullYear()}
                </p>
                <p css={tw`text-center text-xs`}>
                    <a
                        rel={'noopener nofollow noreferrer'}
                        href={'https://flydev.one'}
                        target={'_blank'}
                        css={tw`no-underline`}
                    >
                        Designed by Flydev
                    </a>
                </p>
            </div>
            : 
            <div css={tw`w-full mx-auto p-[10px] md:max-w-[700px] xl:max-w-[700px]`}>
                <FlashMessageRender css={tw`mb-2 px-1`} />
                <div css={tw`flex flex-col md:flex-row items-center bg-helionix-color2 rounded-[20px] shadow-lg p-8 md:mb-8 mb-4 ease-in-out duration-300`}>
                    <img css={tw`w-48 md:mr-8`} src={logo} alt="brand logo" />
                    <div css={tw`w-full flex flex-col`}>
                        {title && <h2 css={tw`text-2xl font-medium pb-4`}>{title}</h2>}
                        <div css={tw`block`}>
                            <Form css={tw`flex flex-col`} {...props} ref={ref}>
                                {props.children}
                            </Form>
                        </div>
                    </div>
                </div>
                <p css={tw`text-center text-xs mt-4`}>
                    <a
                        rel={'noopener nofollow noreferrer'}
                        href={'https://pterodactyl.io'}
                        target={'_blank'}
                        css={tw`no-underline`}
                    >
                        Pterodactyl&reg;
                    </a>
                    &nbsp;&copy; 2015 - {new Date().getFullYear()}
                </p>
                <p css={tw`text-center text-xs`}>
                    <a
                        rel={'noopener nofollow noreferrer'}
                        href={'https://flydev.one'}
                        target={'_blank'}
                        css={tw`no-underline`}
                    >
                        Designed by Flydev
                    </a>
                </p>
            </div>
            }
        </>
    );
});