export function fetchJson<T>(input: RequestInfo | URL, init?: RequestInit): Promise<{ data: T }> {
    return fetch(input, { ...init, headers: { Accept: 'application/json', ...init?.headers } })
        .then((response: Response) => {
            if (!response.ok) {
                return Promise.reject(new Error(`HTTP status ${response.status}`));
            }
            return response;
        })
        .then((response: Response) => response.json() as Promise<{ data: T }>);
}
