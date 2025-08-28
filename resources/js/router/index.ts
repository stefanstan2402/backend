import {createRouter, createWebHistory, RouteRecordRaw} from "vue-router";
import CategoryList from "../views/CategoryList.vue";
import CategoryForm from "../views/CategoryForm.vue";
import ProductList from "../views/ProductList.vue";
import ProductForm from "../views/ProductForm.vue";

const routes: Readonly<RouteRecordRaw[]> = [
    { path: "/", redirect: "/categories" },
    { path: "/categories", component: CategoryList },
    { path: "/categories/new", component: CategoryForm },
    { path: "/categories/:id/edit", component: CategoryForm, props: true },
    { path: "/products", component: ProductList },
    { path: "/products/new", component: ProductForm },
    { path: "/products/:id/edit", component: ProductForm, props: true },
    { path: "/:pathMatch(.*)*", component: { template: "<div>Not Found</div>" } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
