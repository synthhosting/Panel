import React, { useEffect, useRef, useState } from 'react';
import { Link, RouteComponentProps } from 'react-router-dom';
import login from '@/api/auth/login';
import LoginFormContainer from '@/components/auth/LoginFormContainer';
import { useStoreState } from 'easy-peasy';
import { Formik, FormikHelpers } from 'formik';
import { object, string } from 'yup';
import Field from '@/components/elements/Field';
import tw from 'twin.macro';
import { Button } from '@/components/elements/button/index';
import Reaptcha from 'reaptcha';
import useFlash from '@/plugins/useFlash';
import { ApplicationStore } from '@/state';

interface Values {
    username: string;
    password: string;
}

const LoginContainer = ({ history }: RouteComponentProps) => {
    const auth_register_status = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_register_status);
    const auth_google_status = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_google_status);
    const auth_discord_status = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_discord_status);
    const auth_github_status = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_github_status);

    const ref = useRef<Reaptcha>(null);
    const [token, setToken] = useState('');

    const { clearFlashes, clearAndAddHttpError } = useFlash();
    const { enabled: recaptchaEnabled, siteKey } = useStoreState((state) => state.settings.data!.recaptcha);
    const [passwordShown, setPasswordShown] = useState(false);
    const togglePassword = () => {
        setPasswordShown(!passwordShown);
    };

    useEffect(() => {
        clearFlashes();
    }, []);

    const onSubmit = (values: Values, { setSubmitting }: FormikHelpers<Values>) => {
        clearFlashes();

        if (recaptchaEnabled && !token) {
            ref.current!.execute().catch((error) => {
                console.error(error);

                setSubmitting(false);
                clearAndAddHttpError({ error });
            });

            return;
        }

        login({ ...values, recaptchaData: token })
            .then((response) => {
                if (response.complete) {
                    // @ts-expect-error this is valid
                    window.location = response.intended || '/';
                    return;
                }

                history.replace('/auth/login/checkpoint', { token: response.confirmationToken });
            })
            .catch((error) => {
                console.error(error);

                setToken('');
                if (ref.current) ref.current.reset();

                setSubmitting(false);
                clearAndAddHttpError({ error });
            });
    };

    return (
        <Formik
            onSubmit={onSubmit}
            initialValues={{ username: '', password: '' }}
            validationSchema={object().shape({
                username: string().required('A username or email must be provided.'),
                password: string().required('Please enter your account password.'),
            })}
        >
            {({ isSubmitting, setSubmitting, submitForm }) => (
                <LoginFormContainer title={'Login to Continue'} css={tw`w-full flex`}>
                    <Field type={'text'} label={'Username or Email'} name={'username'} disabled={isSubmitting} />
                    <div css={tw`mt-6 flex justify-between items-center`}>
                        <Field light type={passwordShown ? "text" : "password"} label={'Password'} name={'password'} disabled={isSubmitting} />
                        <button type="button" onClick={togglePassword} css={tw`text-xs uppercase mb-1 sm:mb-2`}>
                            {passwordShown ? 'Hide' : 'Show'} Password
                        </button>
                        <Link
                            to={'/auth/password'}
                            css={tw`text-xs tracking-wide no-underline uppercase hover:opacity-80 mb-1 sm:mb-2`}
                        >
                            Forgot password?
                        </Link>
                    </div>
                    <div css={tw`mt-6`}>
                        <Button
                            css={tw`w-full my-2 overflow-hidden whitespace-nowrap`}
                            type={"submit"}
                            disabled={isSubmitting}
                        >
                            Login
                        </Button>
                    </div>
                    {recaptchaEnabled && (
                        <Reaptcha
                            ref={ref}
                            size={'invisible'}
                            sitekey={siteKey || '_invalid_key'}
                            onVerify={(response) => {
                                setToken(response);
                                submitForm();
                            }}
                            onExpire={() => {
                                setSubmitting(false);
                                setToken('');
                            }}
                        />
                    )}
                    {(auth_google_status == true || auth_discord_status == true || auth_github_status == true) &&
                        <div css={tw`mt-2 text-center`}>
                            {auth_google_status == true &&
                                <a href={'/auth/login/google'}>
                                    <i className="bi bi-google text-xl p-2 mx-2 rounded-full bg-[#4e8df5]"></i>
                                </a>
                            }
                            {auth_discord_status == true &&
                                <a href={'/auth/login/discord'}>
                                    <i className="bi bi-discord text-xl p-2 mx-2 rounded-full bg-[#5865F2]"></i>
                                </a>
                            }
                            {auth_github_status == true &&
                                <a href={'/auth/login/github'}>
                                    <i className="bi bi-github text-xl p-2 mx-2 rounded-full bg-[#4078c0]"></i>
                                </a>
                            }
                        </div>
                    }
                    {auth_register_status == true &&
                        <div css={tw`mt-4 text-center`}>
                            <Link
                                to={'/auth/register'}
                                css={tw`text-xs tracking-wide no-underline uppercase hover:opacity-80`}
                            >
                                Not registered?
                            </Link>
                        </div>
                    }
                </LoginFormContainer>
            )}
        </Formik>
    );
};

export default LoginContainer;