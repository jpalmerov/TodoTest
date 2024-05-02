import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { TodoService } from 'src/app/services/todo.service';

@Component({
  selector: 'app-todo-editor',
  templateUrl: './todo-editor.component.html',
  styleUrls: ['./todo-editor.component.css']
})
export class TodoEditorComponent {

  public id: number = -1
  public title: string = 'Todo maker'
  public todoForm = new FormGroup({
    name: new FormControl('', [Validators.required]),
    description: new FormControl('', [Validators.required]),
    finishDate: new FormControl('', [Validators.required]),
    finishTime: new FormControl('00:00:00', [Validators.required]),
  })
  constructor(private router: Router, private route: ActivatedRoute, private todoService: TodoService) {
    this.loadParams()
  }

  loadParams() {
    this.route.params.subscribe(params => {
      let id = params['todo_id']
      if (id > 0) {
        this.id = id
        this.todoService.getTodo(id).then((todo) => {
          this.todoForm.get('name')?.setValue(todo.name)
          this.todoForm.get('description')?.setValue(todo.description)
          const finishDateData = todo.finishDate.toString().split(' ')
          this.todoForm.get('finishDate')?.setValue(finishDateData[0])
          this.todoForm.get('finishTime')?.setValue(finishDateData[1])
          this.title = 'Edit todo'
        })
      }
    })
  }

  save() {
    const name = this.todoForm.get('name')?.value
    const description = this.todoForm.get('description')?.value
    const finishDate = this.todoForm.get('finishDate')?.value
    const finishTime = this.todoForm.get('finishTime')?.value
    if (name == null ||
      finishDate == null ||
      finishTime == null ||
      name.toString() === '') {
      return
    }
    if (this.id > 0) {
      this.todoService.updateTodo(this.id, name!.toString(), description!.toString(), finishDate!+' '+finishTime!).then(() => {
        this.router.navigate(['/dashboard', 'todo_list'])
        alert('Todo updated :)')
      }).catch((err) => {
        alert('There was an error: ' + err)
      })
    } else {
      this.todoService.createTodo(name!.toString(), description!.toString(), finishDate!).then(() => {
        this.router.navigate(['/dashboard', 'todo_list'])
        alert('Todo created :)')
      }).catch((err) => {
        alert('There was an error: ' + err)
      })
    }
  }

  cancel() {
    this.router.navigate(['/dashboard', 'todo_list'])
  }

}
