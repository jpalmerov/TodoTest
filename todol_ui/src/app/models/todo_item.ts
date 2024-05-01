import { TodoItemStatus } from "./todo_item_status";

export class TodoItem {
    constructor(
        public id: number,
        public todo_id: number,
        public name: string,
        public status: TodoItemStatus
    ) { }

}