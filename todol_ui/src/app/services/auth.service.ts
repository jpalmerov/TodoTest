import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { HttpStatus, environment } from 'src/environment';
import * as CryptoJS from 'crypto-js';
import { Subject } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(
    private http: HttpClient
  ) { }

  private userId?: number = undefined
  private username?: string = undefined
  private password?: string = undefined

  public login(username: string, password: string): Promise<any> {
    const shaPassword = CryptoJS.SHA256(password).toString()
    const prom: Promise<any> = new Promise((resolve, reject) => {
      const subscription = this.http.post(environment.urls.user.login, {
        'username': username,
        'password': shaPassword
      }).subscribe({
        next: (response: any) => {
          if (response.status == HttpStatus.success) {
            const data = response.data
            this.userId = data.id
            this.username = data.username
            this.password = shaPassword
            resolve(this.userId)
          } else {
            reject(response.message)
          }
          subscription.unsubscribe()
        },
        error: (err) => {
          reject(err)
          subscription.unsubscribe()
        }
      })
    })

    return prom
  }

  public signup(username: string, password: string): Promise<any> {
    const prom: Promise<any> = new Promise((resolve, reject) => {
      const subscription = this.http.post(environment.urls.user.create, {
        'username': username,
        'password': CryptoJS.SHA256(password).toString()
      }).subscribe({
        next: (response: any) => {
          if (response.status == HttpStatus.success) {
            const data = response.data
            this.userId = data.id
            this.username = data.username
            this.password = CryptoJS.SHA256(password).toString()
            resolve(this.userId)
          } else {
            reject(response.message)
          }
          subscription.unsubscribe()
        },
        error: (err) => {
          reject(err)
          subscription.unsubscribe()
        }
      })
    })
    return prom
  }

  public logout(): Promise<any> {
    return new Promise((resolve, reject) => {
      this.http.post(environment.urls.user.logout, {
        'user_id': this.userId,
      }).subscribe({
        next: (response: any) => {
          if (response.status == HttpStatus.success) {
            this.userId = undefined
            this.username = undefined
            this.password = undefined
          } else {
            reject(response.message)
          }
        },
        error: (err) => {

        }
      })
    })
  }

  public isLoggedIn(): boolean {
    return (this.userId != undefined) && this.userId >= 0
  }

  public getUserId(): number | undefined {
    return this.userId
  }

}
