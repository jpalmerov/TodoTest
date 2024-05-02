import { TodoItemStatus } from "./todo_item_status";

export class TodoItem {
    constructor(
        public id: number,
        public todoId: number,
        public name: string,
        public status: TodoItemStatus,
    ) { }

    public static empty(): TodoItem {
        return new TodoItem(0, 0, '', TodoItemStatus.todo)
    }

    public static fromJson(json: any): TodoItem {
        return new TodoItem(json.id, json.todo_id, json.name, json.status)
    }

    public toJson(): any {
        return {
            'id': this.id,
            'todo_id': this.todoId,
            'name': this.name,
            'status': this.status
        }
    }

}