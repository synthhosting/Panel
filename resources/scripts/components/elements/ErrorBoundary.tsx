import React from 'react';
import tw from 'twin.macro';
import LcIcon from '@/components/elements/LcIcon';
import { TriangleAlert } from 'lucide-react';

interface State {
    hasError: boolean;
}

// eslint-disable-next-line @typescript-eslint/ban-types
class ErrorBoundary extends React.Component<{}, State> {
    state: State = {
        hasError: false,
    };

    static getDerivedStateFromError() {
        return { hasError: true };
    }

    componentDidCatch(error: Error) {
        console.error(error);
    }

    render() {
        return this.state.hasError ? (
            <div css={tw`flex items-center justify-center w-full my-4`}>
                <div css={tw`flex items-center bg-helionix-color2 rounded-2xl p-3`}>
                    <LcIcon icon={TriangleAlert} css={tw`h-4 w-auto mr-2`} />
                    <p css={tw`text-sm`}>
                        An error was encountered by the application while rendering this view. Try refreshing the page.
                    </p>
                </div>
            </div>
        ) : (
            this.props.children
        );
    }
}

export default ErrorBoundary;
