import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { Todo } from 'src/app/models/todo';
import { AuthService } from 'src/app/services/auth.service';
import { TodoService } from 'src/app/services/todo.service';

@Component({
  selector: 'app-todo-list',
  templateUrl: './todo-list.component.html',
  styleUrls: ['./todo-list.component.css'],
})
export class TodoListComponent {
  public todoList: Todo[] = [];

  constructor(private router: Router, private auth: AuthService, private todoService: TodoService) {
    if (!this.auth.isLoggedIn()) {
      this.router.navigate(['/login'])
      return;
    }
    todoService.getTodos().then((todos => {
      this.todoList = todos
    })).catch((err) => {
      console.log(err)
    })
  }

  logout() {
    this.auth.logout().then(() => {
      if (!this.auth.isLoggedIn()) {
        this.router.navigate(['/login'])
      }
    }).catch((err) => {

    })
  }

  deleteTodo(id: number) {
    const confirm = window.confirm('Are you sure you want to delete this item?')
    if (confirm) {
      this.todoService.deleteTodo(id).then(() => {
        this.todoList.splice(this.todoList.findIndex(todo => todo.id == id), 1)
      }).catch((err) => {
        console.log(err)
      })
    }
  }

  newTodo() {
    this.router.navigate(['/dashboard', 'todo', { 'todo_id': -1 }])
  }

  editTodo(todo: Todo) {
    this.router.navigate(['/dashboard', 'todo', { 'todo_id': todo.id }])
  }

  goTodoItemList(todo: Todo){
    this.router.navigate(['/dashboard', 'items', { 'todo_id': todo.id }])
  }

}
