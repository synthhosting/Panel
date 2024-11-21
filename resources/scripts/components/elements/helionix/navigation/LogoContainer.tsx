import tw from "twin.macro";
import styled from "styled-components/macro";

const LogoContainer = styled.div`
  ${tw`w-auto flex justify-start items-center my-2 mx-2`};

  & > img {
    ${tw`max-w-full`};
  }

  & > a {
    ${tw`items-center text-3xl font-semibold ml-2 whitespace-nowrap`};
  }
`;

export default LogoContainer