import {default as PageLogin} from "@/views/pages/auth/login/Main";
import {default as PageRegister} from "@/views/pages/auth/register/Main";
import {default as PageResetPassword} from "@/views/pages/auth/reset-password/Main";
import {default as PageForgotPassword} from "@/views/pages/auth/forgot-password/Main";
import {default as PageNotFound} from "@/views/pages/shared/404/Main";

import {default as PageDashboard} from "@/views/pages/private/dashboard/Main";
import {default as PageFilter} from "@/views/pages/private/dashboard/filter-page";
import {default as Results} from "@/views/pages/private/dashboard/results";
import {default as AccountComponent} from "@/views/pages/private/dashboard/account";
import {default as policyComponent} from "@/views/pages/private/dashboard/privacy-policy";
import {default as termsComponent} from "@/views/pages/private/dashboard/terms-and-conditions";
import {default as PageProfile} from "@/views/pages/private/profile/Main";

import {default as PageUsers} from "@/views/pages/private/users/Index";
import {default as PageUsersCreate} from "@/views/pages/private/users/Create";
import {default as PageUsersEdit} from "@/views/pages/private/users/Edit";

import abilities from "@/stub/abilities";

const routes = [
    // Update or remove routes that are no longer needed
    {
        path: "/",
        name: "home",
        // Directly route to what used to be authenticated pages, like dashboard
        component: PageDashboard,
    },
    {
        path: "/callback",
        name: "Filters",
        // Directly route to what used to be authenticated pages, like dashboard
        component: PageFilter,
    },
    {
        path: "/generate-songs",
        name: "Generate Songs",
        // Directly route to what used to be authenticated pages, like dashboard
        component: Results,
    },
    // Remove authentication pages like login, register, etc.
    {
        path: "/:catchAll(.*)",
        name: "notFound",
        component: PageNotFound,
    },
    {
        path: '/account',
        name: 'Account',
        component: AccountComponent, // Make sure to import your actual account component
    },
    {
        path: '/privacy-policy',
        name: 'Privacy Policy',
        component: policyComponent, // Make sure to import your actual account component
    },
    {
        path: '/terms-and-conditions',
        name: 'Terms and conditions',
        component: termsComponent, // Make sure to import your actual account component
    },
];

export default routes;
