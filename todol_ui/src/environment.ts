
const port = 8000
const apiBaseUrl: string = `http://127.0.0.1:${port}/index.php`
const apiUserBaseUrl: string = `${apiBaseUrl}/user`
const apiTodoBaseUrl: string = `${apiBaseUrl}/todo`
const apiTodoItemBaseUrl: string = `${apiBaseUrl}/todo_item`

export const environment = {
    urls: {
        user: {
            login: `${apiUserBaseUrl}/login`,
            logout: `${apiUserBaseUrl}/logout`,
            create: `${apiUserBaseUrl}/create`,
        },
        todo: {
            create: `${apiTodoBaseUrl}/create`,
            delete: `${apiTodoBaseUrl}/delete`,
            update: `${apiTodoBaseUrl}/update`,
            list: `${apiTodoBaseUrl}/list`,
            get: `${apiTodoBaseUrl}/get`,
        },
        todoItem: {
            create: `${apiTodoItemBaseUrl}/create`,
            delete: `${apiTodoItemBaseUrl}/delete`,
            update: `${apiTodoItemBaseUrl}/update_various`,
            list: `${apiTodoItemBaseUrl}/list`,
            updateStatus: `${apiTodoItemBaseUrl}/update_status`,
        },
    },
};

export enum HttpStatus {
    success = 'success', error = 'error'
}