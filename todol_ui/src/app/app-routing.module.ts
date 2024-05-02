import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './components/login/login.component';
import { SignupComponent } from './components/signup/signup.component';
import { BrowserModule } from '@angular/platform-browser';
import { TodoEditorComponent } from './components/dashboard/todo-editor/todo-editor.component';
import { ItemsComponent } from './components/dashboard/items/items.component';
import { TodoListComponent } from './components/dashboard/todo-list/todo-list.component';

const routes: Routes = [
  { path: 'login', component: LoginComponent },
  { path: 'signup', component: SignupComponent },
  {
    path: 'dashboard', children: [
      { path: 'todo_list', component: TodoListComponent },
      { path: 'todo', component: TodoEditorComponent },
      { path: 'items', component: ItemsComponent }
    ]
  },
  { path: '', redirectTo: '/login', pathMatch: 'full' },
];

@NgModule({
  imports: [
    BrowserModule,
    RouterModule.forRoot(routes),
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }
