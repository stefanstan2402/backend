export function buildQueryParams(filters: Record<string, any>, config: Record<string, string>) {
    const params: Record<string, any> = {};
    Object.entries(filters).forEach(([key, value]) => {
        if (value !== null && value !== "" && config[key]) {
            params[config[key]] = value;
        }
    });
    return params;
}
