/**
 * @license
 * Copyright Akveo. All Rights Reserved.
 * Licensed under the MIT License. See License.txt in the project root for license information.
 */
import { Component, ChangeDetectorRef, Inject } from "@angular/core";
import {
  NbLoginComponent,
  NbAuthService,
  NB_AUTH_OPTIONS,
} from "@nebular/auth";
import { Router } from "@angular/router";
import { SigninService } from "../signin/signin.service";

@Component({
  selector: "ngx-login",
  templateUrl: "./login.component.html",
})
export class LoginComponent extends NbLoginComponent {
  constructor(
    service: NbAuthService,
    @Inject(NB_AUTH_OPTIONS) options: {},
    cd: ChangeDetectorRef,
    router: Router,
    private signinService: SigninService
  ) {
    super(service, options, cd, router);
  }

  login(): void {
    this.signinService
      .checkToken(this.user.email, this.user.password)
      // .then((token: string) => {
      //   // Save the token in localStorage
      //   localStorage.setItem('auth_token', token);
      //   // Redirect or perform other actions after successful login
      //   this.router.navigate(["/dashboard"]);
      // })
      // .catch((error) => {
      //   console.error("Login failed:", error);
      //   // Handle the error (e.g., show an error message to the user)
      //   this.errors = [error.message || "Login failed"];
      // });
  }
}

