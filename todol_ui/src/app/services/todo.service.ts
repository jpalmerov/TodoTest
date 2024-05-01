import { HttpClient } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Todo } from '../models/todo';

@Injectable({
  providedIn: 'root'
})
export class TodoService {

  constructor(private http: HttpClient) { }

  public getTodos(): Promise<[Todo]> {
    return new Promise((resolve, reject) => {
      this.http.get('https://jsonplaceholder.typicode.com/todos').subscribe({
        next: (response: any) => {
          resolve(response)
        },
        error: (err) => {
          reject(err)
        }
      })
    })
  }

}
