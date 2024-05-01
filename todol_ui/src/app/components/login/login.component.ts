import { Component } from '@angular/core';
import { FormControl, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';
import { catchError } from 'rxjs';
import { AuthService } from 'src/app/services/auth.service';

@Component({
    selector: 'app-login',
    templateUrl: './login.component.html',
    styleUrls: ['./login.component.css']
})
export class LoginComponent {
    errorMessage: string = ''
    profileForm: FormGroup = new FormGroup({
        username: new FormControl('', [Validators.required, Validators.minLength(3)]),
        password: new FormControl('', [Validators.required]),
    })

    constructor(private auth: AuthService, private router: Router) { }

    login() {
        const component = this;
        const usernameVal = this.profileForm.get('username')?.value
        const passwordVal = this.profileForm.get('password')?.value
        console.log(`formValues: ${usernameVal}, ${passwordVal}`)
        const result = this.auth.login(usernameVal, passwordVal).then((value) => {
            if (typeof (value) === typeof(1)) {
                this.router.navigate(['/dashboard'])
            }
        }).catch((err) => {
            component.errorMessage = (typeof (err) === typeof ("")) ? err : "Unknown error. Please try again."
        })
    }

}
