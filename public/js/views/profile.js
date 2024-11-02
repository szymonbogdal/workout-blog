import apiCall from "../apiCall.js";
import generateWorkout from "../generateWorkout.js";
import generatePagination from "../generatePagination.js";
import debounce from "../debounce.js";

document.addEventListener(("DOMContentLoaded"), () => {
  const workoutContainer = document.getElementById('workoutContainer');
  const paginationContainer = document.getElementById("paginationContainer");
  const loaderContainer = document.getElementById('loaderContainer');

  const actionBtns = document.querySelectorAll('.action-button');

  const tileFilter = document.getElementById("titleFilter");
  const sortFilter = document.getElementById("sortFilter");

  const responseError = `<p class="workout__response">There was some error. Please try again later.</p>`;
  const responseEmpty = `<p class="workout__response">No workouts found.</p>`;
  
  let state = {page: 1};
  let currentAction = "author";
  
  let activeBtnIndex = 0;
  actionBtns.forEach((btn, index) => {
    btn.addEventListener("click", () => {
      if(activeBtnIndex !== index){
        actionBtns[activeBtnIndex].classList.remove("action-button--active");
        btn.classList.add("action-button--active");
        activeBtnIndex = index;
        if(index === 1 && state.option === "author"){
          state.option = "liked";
          getWorkouts();
          return;
        }

        if(index === 0 && state.option === "liked"){
          state.option = "author";
          getWorkouts();
          return;
        }
      }
    })
  })

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
    const result = await apiCall(url, "GET", {...state, [currentAction]: window.userId});

    loaderContainer.style.display = "none";
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
  }
  
  getWorkouts();

  const filterWorkouts = (updates) => {
    for(const [key, val] of Object.entries(updates)){
      if(val === ""){
        delete state[key];
      }else{
        state = { ...state, [key]: val };
      }
    }
    getWorkouts();
  }
  const debouncedHandler = debounce((e) => {
    filterWorkouts({[e.target.name]: e.target.value})
  }, 300);

  tileFilter.addEventListener('input', debouncedHandler);

  sortFilter.addEventListener('change', (e)=>{
    const selectedOption = e.target.options[e.target.selectedIndex];
    filterWorkouts({
      sort: selectedOption.value,
      order: selectedOption.dataset.order
    })
  })
})