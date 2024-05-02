import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';
import { LoginComponent } from './components/login/login.component';
import { SignupComponent } from './components/signup/signup.component';
import { RouterModule } from '@angular/router';
import { AppComponent } from './app.component';
import { AppRoutingModule } from './app-routing.module';
import { AuthService } from './services/auth.service';
import { TodoService } from './services/todo.service';
import { HttpClientModule } from '@angular/common/http';
import { ReactiveFormsModule } from '@angular/forms';
import { TodoEditorComponent } from './components/dashboard/todo-editor/todo-editor.component';
import { TodoListComponent } from './components/dashboard/todo-list/todo-list.component';
import { ItemsComponent } from './components/dashboard/items/items.component';

@NgModule({
  imports: [
    BrowserModule,
    RouterModule,
    AppRoutingModule,
    HttpClientModule,
    ReactiveFormsModule,
  ],
  declarations: [
    AppComponent,
    LoginComponent,
    SignupComponent,
    TodoListComponent,
    TodoEditorComponent,
    ItemsComponent,
  ],
  providers: [AuthService, TodoService],
  bootstrap: [AppComponent]
})
export class AppModule { }
