import apiCall from "../apiCall.js";

document.addEventListener("DOMContentLoaded", ()=>{
  const form = document.getElementById("authForm");
  const title = document.getElementById("formTitle");
  const swapMsg = document.getElementById("swapMessage");
  const swapAction = document.getElementById("swapAction");
  const submitBtn = document.getElementById("submitButton");
  const responseMsg = document.getElementById("responseMessage");

  let isLogin = true;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const url = `http://localhost/workout_blog/api/${isLogin ? "login" : "register"}`;
    const result = await apiCall(url, "POST", formData);
    if(isLogin && result?.status == 'success'){
      window.location.href = '/workout_blog';
    }else{
      responseMsg.innerHTML = result.message ? result.message : "Something went wrong. Try again later";
    }
  });

  swapAction.addEventListener('click', () => {
    title.innerHTML = isLogin ? "Register" : "Login";
    swapMsg.innerHTML = isLogin ? "Already have account?" : "Don't have account?";
    swapAction.innerHTML = isLogin ? "Login" : "Register";
    submitBtn.innerHTML = isLogin ? "Register" : "Login";
    responseMsg.innerHTML = "";
    isLogin = !isLogin;
  })
})