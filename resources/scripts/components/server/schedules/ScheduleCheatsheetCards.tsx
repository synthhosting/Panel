import React from 'react';
import tw from 'twin.macro';

export default () => {
    return (
        <>
            <div css={tw`md:w-1/2 h-full bg-helionix-color4`}>
                <div css={tw`flex flex-col`}>
                    <h2 css={tw`py-4 px-6 font-bold`}>Examples</h2>
                    <div css={tw`flex py-4 px-6 bg-helionix-color5`}>
                        <p css={tw`w-1/2`}>*/5</p>
                        <span css={tw`w-1/2`}>every 5 minutes</span>
                    </div>
                    <div css={tw`flex py-4 px-6`}>
                        <p css={tw`w-1/2`}>0 */1 * * *</p>
                        <span css={tw`w-1/2`}>every hour</span>
                    </div>
                    <div css={tw`flex py-4 px-6 bg-helionix-color5`}>
                        <p css={tw`w-1/2`}>0 8-12 * * *</p>
                        <span css={tw`w-1/2`}>hour range</span>
                    </div>
                    <div css={tw`flex py-4 px-6`}>
                        <p css={tw`w-1/2`}>0 0 * * *</p>
                        <span css={tw`w-1/2`}>once a day</span>
                    </div>
                    <div css={tw`flex py-4 px-6 bg-helionix-color5`}>
                        <p css={tw`w-1/2`}>0 0 * * MON</p>
                        <span css={tw`w-1/2`}>every Monday</span>
                    </div>
                </div>
            </div>
            <div css={tw`md:w-1/2 h-full bg-helionix-color4`}>
                <h2 css={tw`py-4 px-6 font-bold`}>Special Characters</h2>
                <div css={tw`flex flex-col`}>
                    <div css={tw`flex py-4 px-6 bg-helionix-color5`}>
                        <p css={tw`w-1/2`}>*</p>
                        <span css={tw`w-1/2`}>any value</span>
                    </div>
                    <div css={tw`flex py-4 px-6`}>
                        <p css={tw`w-1/2`}>,</p>
                        <span css={tw`w-1/2`}>value list separator</span>
                    </div>
                    <div css={tw`flex py-4 px-6 bg-helionix-color5`}>
                        <p css={tw`w-1/2`}>-</p>
                        <span css={tw`w-1/2`}>range values</span>
                    </div>
                    <div css={tw`flex py-4 px-6`}>
                        <p css={tw`w-1/2`}>/</p>
                        <span css={tw`w-1/2`}>step values</span>
                    </div>
                </div>
            </div>
        </>
    );
};
