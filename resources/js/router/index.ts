import {createRouter, createWebHistory, RouteRecordRaw} from "vue-router";
import CategoryList from "../views/CategoryList.vue";
import CategoryForm from "../views/CategoryForm.vue";
import ProductList from "../views/ProductList.vue";
import ProductForm from "../views/ProductForm.vue";
import LoginForm from "../views/LoginForm.vue";
import RegisterForm from "../views/RegisterForm.vue";
import NotAllowed from "../views/NotAllowed.vue";

const routes: Readonly<RouteRecordRaw[]> = [
    { path: "/login", component: LoginForm },
    { path: "/register", component: RegisterForm },

    { path: "/", redirect: "/categories" },
    { path: "/categories", component: CategoryList, meta: { requiresAuth: true } },
    { path: "/categories/new", component: CategoryForm, meta: { requiresAuth: true } },
    { path: "/categories/:id/edit", component: CategoryForm, props: true, meta: { requiresAuth: true } },
    { path: "/products", component: ProductList, meta: { requiresAuth: true } },
    { path: "/products/new", component: ProductForm, meta: { requiresAuth: true } },
    { path: "/products/:id/edit", component: ProductForm, props: true, meta: { requiresAuth: true } },

    { path: "/not-allowed", component: NotAllowed },
    { path: "/:pathMatch(.*)*", component: { template: "<div>Not Found</div>" } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

router.beforeEach((to, from, next) => {
    const token = localStorage.getItem("api_token");

    if (to.meta.requiresAuth && !token) {
        next("/not-allowed");
    } else {
        next();
    }
});

export default router;
