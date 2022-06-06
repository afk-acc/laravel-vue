import { createRouter, createWebHistory } from "vue-router";

import Container from "./views/layout/Container";

const routes = [
    {
        path:"/",
        name: Container,
        component : Container
    }
]
const router = createRouter({
    routes,
    history: createWebHistory(process.env.APP_URL)
})
export default router
