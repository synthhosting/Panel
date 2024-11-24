import React, { useEffect, useState } from 'react';
import { Button } from '@/components/elements/button/index';
import Modal from '@/components/elements/Modal';
import { Formik, FormikHelpers, Form } from 'formik';
import { useFlashKey } from '@/plugins/useFlash';
import smartSearch from '@/api/server/files/smartSearch';
import { ServerContext } from '@/state/server';
import tw from 'twin.macro';
import Field from '@/components/elements/Field';
import FlashMessageRender from '@/components/FlashMessageRender';
import { object, string } from 'yup';
import GreyRowBox from '@/components/elements/GreyRowBox';
import SpinnerOverlay from '@/components/elements/SpinnerOverlay';
import getFileContents from '@/api/server/files/getFileContents';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faArrowDown, faArrowUp } from '@fortawesome/free-solid-svg-icons';
import SmartSearchCodemirrorEditor from '@/components/server/files/SmartSearchCodemirrorEditor';

interface SearchValues {
    query: string;
}

export default () => {
    const uuid = ServerContext.useStoreState((state) => state.server.data!.uuid);
    const directory = ServerContext.useStoreState((state) => state.files.directory);

    const [open, setOpen] = useState(false);
    const [spinner, showSpinner] = useState(false);
    const [output, setOutput] = useState<any[]>([]);
    const [showFileSnippet, setFileSnippet] = useState('');
    const [fileLoading, setFileLoading] = useState(false);
    const [searchText, setSearch] = useState('');

    const [content, setContent] = useState('');
    const [mode, setMode] = useState('text/plain');

    const { clearFlashes, clearAndAddHttpError } = useFlashKey('files:smart-search');

    const search = ({ query }: SearchValues, { setSubmitting }: FormikHelpers<SearchValues>) => {
        clearFlashes();
        showSpinner(true);
        setSearch(query);

        smartSearch(uuid, directory, query)
            .then((data) => {
                setOutput(data.results);
            })
            .catch((error) => clearAndAddHttpError(error))
            .finally(() => {
                setSubmitting(false);
                showSpinner(false);
            });
    };

    const showFile = (file: string, key: number) => {
        if (file === showFileSnippet) {
            setFileSnippet('');
            return;
        }

        setContent('');
        setFileLoading(true);
        setFileSnippet(file);

        getFileContents(uuid, file)
            .then(setContent)
            .then(() => {
                setTimeout(() => {
                    // eslint-disable-next-line @typescript-eslint/ban-ts-comment
                    // @ts-ignore
                    document.getElementById(`file-${key}`).scrollIntoView({ behavior: 'smooth' });
                }, 200);
            })
            .catch((error) => {
                clearAndAddHttpError(error);
                setFileSnippet('');
            })
            .finally(() => setFileLoading(false));
    };

    useEffect(() => {
        return () => {
            setOutput([]);
            setFileSnippet('');
            clearFlashes();
        };
    }, []);

    return (
        <>
            <Modal
                visible={open}
                onDismissed={() => {
                    setOpen(false);
                    setOutput([]);
                    setFileSnippet('');
                    clearFlashes();
                }}
                showSpinnerOverlay={spinner}
            >
                <Formik
                    onSubmit={search}
                    initialValues={{
                        query: '',
                    }}
                    validationSchema={object().shape({
                        query: string().required().min(3),
                    })}
                >
                    {({ isSubmitting }) => (
                        <Form>
                            <div css={tw`flex flex-wrap`}>
                                <div css={tw`w-full lg:w-10/12 xl:w-11/12 pr-4`}>
                                    <Field name={'query'} placeholder={'Enter query to start searching...'} autoFocus />
                                </div>
                                <div css={tw`w-full lg:w-2/12 xl:w-1/12 text-center`}>
                                    <Button disabled={isSubmitting} css={tw`mt-1 w-full`}>
                                        Search
                                    </Button>
                                </div>
                            </div>
                        </Form>
                    )}
                </Formik>
                <FlashMessageRender byKey={'files:smart-search'} css={tw`mt-4`} />
                {output.length > 0 ? (
                    <div css={tw`mt-4 overflow-y-auto`} style={{ maxHeight: '40rem' }}>
                        {output.map((item, key) => (
                            <React.Fragment key={key}>
                                <GreyRowBox css={tw`mt-2`} id={`file-${key}`}>
                                    <div css={tw`w-full lg:w-8/12`}>
                                        <span css={tw`text-neutral-300`}>{item.file}</span>
                                    </div>
                                    <div css={tw`w-full lg:w-4/12 text-right`}>
                                        <span
                                            css={tw`bg-blue-100 text-blue-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300`}
                                        >
                                            {item.lines.length} result(s)
                                        </span>
                                        <span onClick={() => showFile(item.file, key)}>
                                            {item.file === showFileSnippet ? (
                                                <FontAwesomeIcon icon={faArrowUp} />
                                            ) : (
                                                <FontAwesomeIcon icon={faArrowDown} />
                                            )}
                                        </span>
                                    </div>
                                </GreyRowBox>
                                {showFileSnippet === item.file && (
                                    <div css={tw`relative`}>
                                        <SpinnerOverlay visible={fileLoading} />
                                        <SmartSearchCodemirrorEditor
                                            mode={mode}
                                            filename={item.file}
                                            onModeChanged={setMode}
                                            initialContent={content}
                                            lines={item.lines}
                                            query={searchText}
                                        />
                                    </div>
                                )}
                            </React.Fragment>
                        ))}
                    </div>
                ) : (
                    <p css={tw`mt-4 text-center text-sm text-neutral-400`}>
                        Looks like there are no file matching to your search...
                    </p>
                )}
            </Modal>
            <Button.Text onClick={() => setOpen(true)}>Smart Search</Button.Text>
        </>
    );
};
