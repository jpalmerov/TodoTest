import { TodoItemStatus } from './../../../models/todo_item_status';
import { TodoItem } from './../../../models/todo_item';
import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { Todo } from 'src/app/models/todo';
import { AuthService } from 'src/app/services/auth.service';
import { TodoService } from 'src/app/services/todo.service';

@Component({
  selector: 'app-items',
  templateUrl: './items.component.html',
  styleUrls: ['./items.component.css']
})
export class ItemsComponent {

  public todo?: Todo
  public todoItemList: TodoItem[] = []
  public todoItemForm!: FormGroup[]
  public updatedName: boolean = false
  public expandedStatusIndex: number = -1
  public todoItemAddFormControl: FormControl = new FormControl('', Validators.required)

  constructor(private router: Router, private route: ActivatedRoute, private auth: AuthService, private todoService: TodoService) {
    if (!this.auth.isLoggedIn()) {
      this.router.navigate(['/login'])
      return
    }
    this.route.params.subscribe(params => {
      let id = params['todo_id']
      if (id > 0) {
        this.todoService.getTodo(id).then((todo) => {
          this.todo = todo
          this.loadItems()
        }).catch((err) => {
          console.log(err)
          alert('There was an error: ' + err)
          this.router.navigate(['/login'])
        })
      }
    })
  }

  loadItems() {
    this.todoService.getItems(this.todo!.id).then((items) => {
      this.todoItemForm = items.map((item: TodoItem) => new FormGroup({
        name: new FormControl(item.name, [Validators.required]),
      }))
      this.todoItemList = items
    })
  }

  saveAll() {
    const itemsToUpdate: { id: number, todo_id: number, name: string, status: TodoItemStatus }[] = []
    for (let i = 0; i < this.todoItemForm.length; i++) {
      const itemForm = this.todoItemForm[i]
      const name = itemForm.get('name')!.value!.toString()
      if (name == '') {
        alert('Name cannot be empty')
        return
      } else {
        if (name != this.todoItemList[i].name) {
          itemsToUpdate.push({
            id: this.todoItemList[i].id,
            todo_id: this.todoItemList[i].todoId,
            name: name,
            status: this.todoItemList[i].status
          })
        }
      }
    }
    if(itemsToUpdate.length == 0) {
      alert('Nothing to update')
      return
    }
    this.todoService.updateItems(itemsToUpdate).then(() => {
      alert('Items updated')
    }).catch((err) => {
      alert('There was an error: ' + err.message)
      this.loadItems()
    })
  }

  cancel() {
    this.router.navigate(['/dashboard', 'todo_list'])
  }

  addItem() {
    const name = this.todoItemAddFormControl.value
    if (name != '') {
      this.todoService.createItem(this.todo!.id, name, TodoItemStatus.todo)
        .then((item) => {
          this.todoItemList.push(item)
          this.todoItemForm.push(new FormGroup({
            name: new FormControl(name, [Validators.required]),
          }))
          this.todoItemAddFormControl.setValue('')
        }).catch((err) => {
          alert('There was an error: ' + err)
        })
    }
  }

  deleteItem(index: number) {
    const confirmation = confirm('Are you sure you want to delete this item?')
    if (!confirmation) return
    this.todoService.deleteItem(this.todoItemList[index].id).then(() => {
      this.todoItemList.splice(index, 1)
    }).catch((err) => {
      alert('There was an error: ' + err)
    })
  }

  selectStatus(index: number, status: any) {
    this.todoService.updateItemStatus(this.todoItemList[index].id, status).then(() => {
      this.todoItemList[index].status=status
      this.expandedStatusIndex = -1
    }).catch((err) => {
      alert('There was an error: ' + err)
    })
  }

  getStatusBtnClass(status: TodoItemStatus) {
    switch(status) {
      case TodoItemStatus.todo:
        return 'btn-primary'
      case TodoItemStatus.in_progress:
        return 'btn-warning'
      case TodoItemStatus.done:
        return 'btn-success'
    }
  }

  getStatusText(status: TodoItemStatus) {
    switch(status) {
      case TodoItemStatus.todo:
        return '📌 Todo'
      case TodoItemStatus.in_progress:
        return '🔨 In Progress'
      case TodoItemStatus.done:
        return '✅ Done'
    }
  }

}
