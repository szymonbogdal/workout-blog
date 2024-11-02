import apiCall from "../apiCall.js";
import generateWorkout from "../generateWorkout.js";
import generatePagination from "../generatePagination.js";

document.addEventListener(("DOMContentLoaded"), () => {
  const workoutContainer = document.getElementById('workoutContainer');
  const paginationContainer = document.getElementById("paginationContainer");
  const loaderContainer = document.getElementById('loaderContainer');

  const responseError = `<p class="workout__response">There was some error. Please try again later.</p>`;
  const responseEmpty = `<p class="workout__response">No workouts found.</p>`;
  
  let state = {page: 1};
  
  const renderPagination = (totalPages) => {
    const oldButtons = paginationContainer.querySelectorAll('.page-button');
    oldButtons.forEach(button => {
      button.replaceWith(button.cloneNode(true));
    });
    
    paginationContainer.innerHTML = generatePagination(totalPages, state.page);

    const newButtons = paginationContainer.querySelectorAll('.page-button');
    newButtons.forEach(button => {
      button.addEventListener('click', () => {
        const page = parseInt(button.dataset.page);
        state.page = page;
        getWorkouts();
      });
    });
  }

  const getWorkouts = async () => {
    workoutContainer.innerHTML = null;
    loaderContainer.style.display = "flex";

    const url = "http://localhost/workout_blog/api/workouts";
    const result = await apiCall(url, "GET");

    if(result?.status == 'error'){
      workoutContainer.insertAdjacentHTML('beforeend', responseError);  
      return;
    }

    if(result.data.length == 0){
      workoutContainer.insertAdjacentHTML('beforeend', responseEmpty);
      return;
    }

    result.data.forEach(workout => {
      const workoutHtml = generateWorkout(workout);
      workoutContainer.insertAdjacentHTML('beforeend', workoutHtml);
    });
    renderPagination(result.total_pages);
    loaderContainer.style.display = "none";
  }
  
  getWorkouts();
})