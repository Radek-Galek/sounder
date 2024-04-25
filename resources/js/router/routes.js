import {default as PageNotFound} from "@/views/pages/shared/404/Main";

import {default as PageDashboard} from "@/views/pages/Main";
import {default as PageFilter} from "@/views/pages/filter-page";
import {default as Results} from "@/views/pages/results";
import {default as AccountComponent} from "@/views/pages/account";
import {default as policyComponent} from "@/views/pages/privacy-policy";
import {default as termsComponent} from "@/views/pages/terms-and-conditions";

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
