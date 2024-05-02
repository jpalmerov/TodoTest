import { Time } from "@angular/common";

export class Todo {
    constructor(
        public id: number,
        public name: string,
        public description: string,
        public finishDate: Time,
        public itemCount: number,
    ) { }

    public static fromJson(json: any): Todo {
        return new Todo(json.id, json.name, json.description, json.finish_date, json.item_count)
    }

    public toJson(): any {
        return {
            'id': this.id,
            'name': this.name,
            'description': this.description,
            'finish_date': this.finishDate,
            'item_count': this.itemCount
        }
    }
}