import React, { useState } from 'react';
import LcIcon from '@/components/elements/LcIcon';
import { 
    Search,
} from "lucide-react"
import useEventListener from '@/plugins/useEventListener';
import SearchModal from '@/components/dashboard/search/SearchModal';
import tw from "twin.macro";
import styled from "styled-components/macro";

const SearchContainer = styled.button`
  ${tw`flex w-full py-2 px-3 my-1 items-center no-underline cursor-pointer transition-all duration-150`};

  &:active,
  &:hover {
    ${tw`rounded-lg bg-helionix-btnPrimary`};
  }
`;

const Button = styled.button`
  ${tw`text-left ml-2`};
`;

export default () => {
    const [visible, setVisible] = useState(false);

    useEventListener('keydown', (e: KeyboardEvent) => {
        if (['input', 'textarea'].indexOf(((e.target as HTMLElement).tagName || 'input').toLowerCase()) < 0) {
            if (!visible && e.metaKey && e.key.toLowerCase() === '/') {
                setVisible(true);
            }
        }
    });

    return (
        <>
            {visible && <SearchModal appear visible={visible} onDismissed={() => setVisible(false)} />}
            <SearchContainer onClick={() => setVisible(true)}>
                <LcIcon icon={Search} size={20} css={tw`flex`}/>
                <Button>Search...</Button>
            </SearchContainer>
        </>
    );
};
