import tw from "twin.macro";
import styled from "styled-components/macro";

const Navigation = styled.div<{ isVisible: boolean }>`
  ${tw`sticky bg-helionix-color2 rounded-r-none rounded-r-2xl shadow-md overflow-y-auto top-0 h-screen md:!w-72 overflow-x-hidden`};
  flex-shrink: 0;
  
  & > div {
    ${tw`flex items-center justify-center`};
  }

  @media (max-width: 1279px) {
    ${tw`block fixed w-3/4 z-[41] overflow-x-hidden`};
    transition: transform 0.5s ease, opacity 0.5s ease;
    transform: ${({ isVisible }) => (isVisible ? 'translateX(0)' : 'translateX(-100vw)')};
  }
`;

export default Navigation;