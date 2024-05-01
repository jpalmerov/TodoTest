import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-signup',
  templateUrl: './signup.component.html',
  styleUrls: ['./signup.component.css']
})
export class SignupComponent {
  errorMessage: string = ''
  profileForm: FormGroup = new FormGroup({
    username: new FormControl('', [Validators.required, Validators.minLength(3)]),
    password1: new FormControl('', [Validators.required]),
    password2: new FormControl('', [Validators.required]),
  })

  constructor(private auth: AuthService, private router: Router) { }

  signup() {
    const component = this;
    const usernameVal = this.profileForm.get('username')?.value
    const passwordVal1 = this.profileForm.get('password1')?.value
    const passwordVal2= this.profileForm.get('password2')?.value

    if(passwordVal1!=passwordVal2){
      this.errorMessage="The passwords do not match"
    }

    const result = this.auth.signup(usernameVal, passwordVal1).then((value) => {
      if (typeof (value) === typeof (1)) {
        this.router.navigate(['/login'])
      }
    }).catch((err) => {
      component.errorMessage = (typeof (err) === typeof ("")) ? err : "Unknown error. Please try again."
    })
  }
}
