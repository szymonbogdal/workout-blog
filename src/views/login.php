<div class="loader__container" id="loaderContainer">
    <div class="loader"></div>
</div>
<div class="auth__container">
  <h2 class="auth__title" id="formTitle">Log in</h2>
  <form class="auth__form" id="authForm">
  <label class="form__label" for="username">Username</label>
  <input class="form__input" name="username" id="username" type="text" required>
  <label class="form__label" for="password">Password</label>
  <input class="form__input" name="password" id="password" type="password" required>
  <button class="form__submit" type="submit" id="submitButton">Login</button>
  </form>
  <p class="auth__resposne" id="responseMessage"></p>
  <div class="auth__footer">
    <p class="footer__msg" id="swapMessage">Don't have account?</p>
    <p class="footer__action" id="swapAction">Register</p>
    <a class="footer__return" href="/workout_blog">Go back to main page</a>
  </div>
</div>