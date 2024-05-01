import { Time } from "@angular/common";

export class Todo {
    constructor(
        public id: number,
        public name: string,
        public description: string,
        public finishDate: Time,
        public itemCount: number,
    ) { }
}