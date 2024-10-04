<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script type="module" src="/workout_blog/public/js/views/login.js"></script>
</head>
<body>
  <h1 id="formTitle">Log in</h1>
  <form id="authForm">
    <label for="username">Username</label>
    <input name="username" id="username" type="text" required>
    <label for="password">Password</label>
    <input name="password" id="password" type="password" required>
    <button type="submit" id="submitButton">Log in</button>
  </form>
  <p id="responseMessage"></p>
  <p id="swapMessage">Don't have account?</p>
  <p id="swapAction">Register</p>
</body>
</html>