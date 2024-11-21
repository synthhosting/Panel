import tw from "twin.macro";
import styled from "styled-components/macro";

const NavigationBar = styled.div`
  ${tw`flex-col items-center justify-center mx-3 mb-4`};
  & > a,
  & > button,
  & > .navigation-link {
    ${tw`w-full py-2 px-3 my-1 items-center no-underline cursor-pointer transition-all duration-150 rounded-lg`};

    &:active,
    &:hover {
      ${tw`rounded-lg`};
      background: var(--button-primary);
      background: linear-gradient(90deg, rgba(111,88,255,0) 0%, var(--button-primary) 35%);
    }

    &:active,
    &:hover,
    &.active {
      ${tw`rounded-lg`};
      background: var(--button-primary);
      background: linear-gradient(90deg, rgba(111,88,255,0) 0%, var(--button-primary) 35%);
    }
  }
`;

export default NavigationBar