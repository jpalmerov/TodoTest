import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';
import { TodoService } from 'src/app/services/todo.service';

@Component({
  selector: 'app-dashboard',
  templateUrl: './dashboard.component.html',
  styleUrls: ['./dashboard.component.css']
})
export class DashboardComponent {

  constructor(private router: Router, private auth: AuthService, private todoService: TodoService) {
    if(!this.auth.isLoggedIn()) {
      this.router.navigate(['/login'])
    }
  }

  logout() {
    this.auth.logout().then(() => {
      if(!this.auth.isLoggedIn()) {
        this.router.navigate(['/login'])
      }
    }).catch((err) => {
      
    })
  }

  loadTodos() {
    this.router.navigate(['/todo'])
  }

}
