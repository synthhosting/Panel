import React, { useEffect, useRef, useState } from "react";
import { Link } from "react-router-dom";
import register from "@/api/auth/register";
import RegisterFormContainer from "@/components/auth/LoginFormContainer";
import { useStoreState } from "easy-peasy";
import { Formik, FormikHelpers } from "formik";
import { object, string } from "yup";
import Field from "@/components/elements/Field";
import tw from "twin.macro";
import { Button } from "@/components/elements/button/index";
import Reaptcha from "reaptcha";
import useFlash from "@/plugins/useFlash";
import { ApplicationStore } from '@/state';

interface Values {
  email: string;
  username: string;
  firstname: string;
  lastname: string;
}

const RegisterContainer = () => {
  const auth_google_status = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_google_status);
  const auth_discord_status = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_discord_status);
  const auth_github_status = useStoreState((state: ApplicationStore) => state.helionix.data!.auth_github_status);

  const ref = useRef<Reaptcha>(null);
  const [token, setToken] = useState("");

  const { clearFlashes, clearAndAddHttpError, addFlash } = useFlash();
  const { enabled: recaptchaEnabled, siteKey } = useStoreState(
    (state) => state.settings.data!.recaptcha
  );

  useEffect(() => {
    clearFlashes();
  }, []);

  const onSubmit = (
    values: Values,
    { setSubmitting }: FormikHelpers<Values>
  ) => {
    clearFlashes();

    // If there is no token in the state yet, request the token and then abort this submit request
    // since it will be re-submitted when the recaptcha data is returned by the component.
    if (recaptchaEnabled && !token) {
      ref.current!.execute().catch((error) => {
        console.error(error);

        setSubmitting(false);
        clearAndAddHttpError({ error });
      });

      return;
    }

    register({ ...values, recaptchaData: token })
      .then((response) => {
        if (response.complete) {
          addFlash({
            type: "success",
            title: "Success",
            message: "You have successfully registered, check your email",
          });

          setSubmitting(false);
        }
      })
      .catch((error) => {
        console.error(error);

        setToken("");
        if (ref.current) ref.current.reset();

        const data = JSON.parse(error.config.data);

        if (!/^[a-zA-Z0-9][a-zA-Z0-9_.-]*[a-zA-Z0-9]$/.test(data.username))
          error =
            "The username must start and end with alpha-numeric characters and contain only letters, numbers, dashes, underscores, and periods.";
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email))
          error = "The email must be a valid email address.";

        setSubmitting(false);
        if (typeof error === "string") {
          addFlash({
            type: "error",
            title: "Error",
            message: error || "",
          });
        } else {
          clearAndAddHttpError({ error });
        }
      });
  };

  return (
    <Formik
      onSubmit={onSubmit}
      initialValues={{ email: "", username: "", firstname: "", lastname: "" }}
      validationSchema={object().shape({
        email: string().required("An email must be provided."),
        username: string().required("An username must be provided."),
        firstname: string().required("A first name must be provided."),
        lastname: string().required("A last name must be provided."),
      })}
    >
      {({ isSubmitting, setSubmitting, submitForm }) => (
        <RegisterFormContainer
          title={"Registration Form"}
          css={tw`w-full flex`}
        >
          <Field
            type={"email"}
            label={"Email"}
            name={"email"}
            disabled={isSubmitting}
          />
          <div css={tw`mt-6`}>
            <Field
              type={"text"}
              label={"Username"}
              name={"username"}
              disabled={isSubmitting}
            />
          </div>
          <div css={tw`mt-6`}>
            <Field
              type={"text"}
              label={"First Name"}
              name={"firstname"}
              disabled={isSubmitting}
            />
          </div>
          <div css={tw`mt-6`}>
            <Field
              type={"text"}
              label={"Last Name"}
              name={"lastname"}
              disabled={isSubmitting}
            />
          </div>
          <div css={tw`mt-6`}>
            <Button
              type={"submit"}
              disabled={isSubmitting}
              css={tw`w-full my-2 overflow-hidden whitespace-nowrap`}
            >
              Register
            </Button>
          </div>

          {recaptchaEnabled && (
            <Reaptcha
              ref={ref}
              size={"invisible"}
              sitekey={siteKey || "_invalid_key"}
              onVerify={(response) => {
                setToken(response);
                submitForm();
              }}
              onExpire={() => {
                setSubmitting(false);
                setToken("");
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
          <div css={tw`mt-4 text-center`}>
            <Link
              to={"/auth/login"}
              css={tw`text-xs tracking-wide no-underline uppercase`}
            >
              Already registered?
            </Link>
          </div>
        </RegisterFormContainer>
      )}
    </Formik>
  );
};

export default RegisterContainer;
