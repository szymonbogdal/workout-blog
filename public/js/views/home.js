import apiCall from "../apiCall.js";

document.addEventListener("DOMContentLoaded", () => {
  const logoutBtn = document.getElementById('logoutButton');
  logoutBtn.addEventListener('click', async() => {
    const result = await apiCall("http://localhost/workout_blog/api/logout", "GET");
    if(result.status == 'success'){
      window.location.href = '/workout_blog/login';
    }
  })
});