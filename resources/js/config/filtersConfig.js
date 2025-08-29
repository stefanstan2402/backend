export const filtersConfig = {
    products: [
        { field: "name", type: "text", label: "Name" },
        { field: "price", type: "number", label: "Price" },
        { field: "category_id", type: "options", label: "Category", options: [] },
        { field: "stock", type: "number", label: "Stock" },
    ],
    categories: [
        { field: "name", type: "text", label: "Name" },
        { field: "description", type: "text", label: "Description" }
    ]
};
