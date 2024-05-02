import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Todo } from '../models/todo';
import { AuthService } from './auth.service';
import { HttpStatus, environment } from 'src/environment';
import { TodoItem } from '../models/todo_item';
import { TodoItemStatus } from '../models/todo_item_status';

@Injectable({
  providedIn: 'root'
})
export class TodoService {

  constructor(private http: HttpClient, private auth: AuthService) { }

  public getTodos(): Promise<Todo[]> {
    return new Promise((resolve, reject) => {
      this.http.post(environment.urls.todo.list, {
        'user_id': this.auth.getUserId()
      }).subscribe({
        next: (response: any) => {
          const todos: Todo[] = response.data.map((todo: any) => {
            return Todo.fromJson(todo)
          })
          resolve(todos)
        },
        error: (err) => {
          reject(err)
        }
      })
    })
  }

  public deleteTodo(id: number) {
    return new Promise((resolve, reject) => {
      this.http.post(environment.urls.todo.delete, {
        'id': id
      }).subscribe({
        next(value: any) {
          if (value.status == HttpStatus.success) {
            resolve(id)
          } else {
            reject(value.message)
          }
        },
        error(err) {
          reject(err)
        },
      })
    })
  }

  public createTodo(name: string, description: string, finishDate: string): Promise<any> {
    return new Promise((resolve, reject) => {
      this.http.post(environment.urls.todo.create, {
        'user_id': this.auth.getUserId(),
        'name': name,
        'description': description,
        'finish_date': finishDate
      }).subscribe({
        next: (response: any) => {
          if (response.status == HttpStatus.success) {
            resolve(Todo.fromJson(response.data))
          } else {
            reject(response.message)
          }
        },
        error: (err) => {
          reject(err)
        }
      })
    })
  }

  public updateTodo(id: number, name: string, description: string, finishDate: string): Promise<any> {
    return new Promise((resolve, reject) => {
      this.http.post(environment.urls.todo.update, {
        'id': id,
        'name': name,
        'description': description,
        'finish_date': finishDate
      }).subscribe({
        next: (response: any) => {
          if (response.status == HttpStatus.success) {
            resolve(Todo.fromJson(response.data))
          } else {
            reject(response.message)
          }
        },
        error: (err) => {
          reject(err)
        }
      })
    })
  }

  public getTodo(id: number): Promise<Todo> {
    return new Promise((resolve, reject) => {
      this.http.post(environment.urls.todo.get, {
        'id': id
      }).subscribe({
        next: (response: any) => {
          resolve(Todo.fromJson(response.data))
        },
        error: (err) => {
          reject(err)
        }
      })
    })
  }

  public getItems(todoId: number): Promise<any> {
    return new Promise((resolve, reject) => {
      this.http.post(environment.urls.todoItem.list, {
        'todo_id': todoId
      }).subscribe({
        next: (response: any) => {
          if (response.status == HttpStatus.success) {
            const todoItemList = response.data.map((todoItem: any) => {
              return TodoItem.fromJson(todoItem)
            })
            resolve(todoItemList)
          } else {
            reject(response.message)
          }
        },
        error: (err) => {
          reject(err)
        }
      })
    })
  }

  public deleteItem(id: number) {
    return new Promise((resolve, reject) => {
      this.http.post(environment.urls.todoItem.delete, {
        'id': id
      }).subscribe({
        next(value: any) {
          if (value.status == HttpStatus.success) {
            resolve(id)
          } else {
            reject(value.message)
          }
        },
        error(err) {
          reject(err)
        },
      })
    })
  }

  public createItem(todoId: number, name: string, status: TodoItemStatus): Promise<any> {
    return new Promise((resolve, reject) => {
      this.http.post(environment.urls.todoItem.create, {
        'todo_id': todoId,
        'name': name,
        'status': status
      }).subscribe({
        next: (response: any) => {
          if (response.status == HttpStatus.success) {
            resolve(TodoItem.fromJson(response.data))
          } else {
            reject(response.message)
          }
        },
        error: (err) => {
          reject(err)
        }
      })
    })
  }

  public updateItems(updateArray: { id: number, todo_id: number, name: string, status: TodoItemStatus }[]): Promise<any> {
    return new Promise((resolve, reject) => {
      this.http.post(environment.urls.todoItem.update, {
        'items': updateArray,
      }).subscribe({
        next: (response: any) => {
          if (response.status == HttpStatus.success) {
            resolve(TodoItem.fromJson(response.data))
          } else {
            reject(response.message)
          }
        },
        error: (err) => {
          reject(err)
        }
      })
    })
  }

  public updateItemStatus(id: number, status: TodoItemStatus){
    return new Promise((resolve, reject) => {
      this.http.post(environment.urls.todoItem.updateStatus, {
        'id': id,
        'status': status
      }).subscribe({
        next: (response: any) => {
          if (response.status == HttpStatus.success) {
            resolve('success')
          } else {
            reject(response.message)
          }
        },
        error: (err) => {
          reject(err)
        }
      })
    })
  }

}
