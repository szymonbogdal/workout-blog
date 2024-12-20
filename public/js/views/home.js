import apiCall from "../apiCall.js";
import generateWorkout from "../generateWorkout.js";
import debounce from "../debounce.js";
import generatePagination from "../generatePagination.js";

const workoutContainer = document.getElementById('workoutContainer');
const loader = document.getElementById('loader');
const paginationContainer = document.getElementById("paginationContainer")
const textInputFilters = document.querySelectorAll(".input");
const btnFilters = document.querySelectorAll(".btn-difficulty")
const sortFilter = document.getElementById('sortFilter');
const logoutBtn = document.getElementById('logoutButton');
const loginBtn = document.getElementById('loginButton');
const sidebar = document.getElementById('sidebar');
const sidebarOpenBtn = document.getElementById('sidebarOpenBtn');
const sidebarCloseBtn = document.getElementById('sidebarCloseBtn');
const seedDbBtn = document.getElementById("seedDb");

const responseError = `<p class="workout__response">There was some error. Please try again later.</p>`
const responseEmpty = `<p class="workout__response">No workouts found.</p>`

let state = {page: 1}; 
let activeButton = null;

//Render pagination buttons and add event listener to change page
const renderPagination = (totalPages) => {
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

//Add event listener to like button on every workout
const setupLikeBtns = () => {
  const likeBtns = document.querySelectorAll(".options__like");
  likeBtns.forEach(btn => {
    btn.addEventListener('click', async (e) => {
      const likeCountElement = e.target.closest(".header__options").querySelector(".options__count");
  
      const initialLiked = e.target.dataset.liked === "true";
      const initialLikeCount = parseInt(likeCountElement.textContent);
  
      e.target.dataset.liked = initialLiked ? "false" : "true";
      likeCountElement.textContent = initialLiked ? initialLikeCount - 1 : initialLikeCount + 1;
  
      const url = `http://localhost/workout-blog/api/workouts/${e.target.dataset.workout}/like`;
      const result = await apiCall(url, "POST");
      
      if(!result || result.status === 'error'){
        e.target.dataset.liked = initialLiked ? "true" : "false";
        likeCountElement.textContent = initialLikeCount;
      }
    });
  });
}

//Get all workouts, re-apply pagination and like buttons listeners
const getWorkouts = async () => {
  workoutContainer.innerHTML = null;
  paginationContainer.style.display = "none";
  loader.style.display = "block";

  const url = "http://localhost/workout-blog/api/workouts";
  const result = await apiCall(url, "GET", state);

  loader.style.display = "none";
  paginationContainer.style.display = "flex";
  
  if(!result || result.status === 'error'){
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
  setupLikeBtns();
}

//Update state object
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

//Debounce for text inputs
const debouncedHandler = debounce((e) => {
  filterWorkouts({[e.target.name]: e.target.value})
}, 300);

//Filtering and sorting event listeners
textInputFilters.forEach((input)=>{
  input.addEventListener('input', debouncedHandler);
})
sortFilter.addEventListener('change', (e)=>{
  const selectedOption = e.target.options[e.target.selectedIndex];
  filterWorkouts({
    sort: selectedOption.value,
    order: selectedOption.dataset.order
  })
})
btnFilters.forEach((btn)=>{
  btn.addEventListener("click", (e) => {
    if(activeButton === e.target){
      activeButton.classList.remove("btn-difficulty--active");
      activeButton = null;
      filterWorkouts({difficulty: ""})
    }else{
      if(activeButton){
        activeButton.classList.remove("btn-difficulty--active");
      }
      e.target.classList.add("btn-difficulty--active");
      activeButton = e.target;
      filterWorkouts({difficulty: e.target.value})
    }
  })
})

//Login/logout event listeners
logoutBtn && logoutBtn.addEventListener('click', async() => {
  workoutContainer.style.display = "none";
  paginationContainer.style.display = "none";
  loader.style.display = "block";
  
  const result = await apiCall("http://localhost/workout-blog/api/logout", "POST");
  if(result?.status === 'success'){
    window.location.reload();
  }

  workoutContainer.style.display = "block";
  paginationContainer.style.display = "flex";
  loader.style.display = "none";
})

loginBtn && loginBtn.addEventListener('click', () => {
  window.location.href = '/workout-blog/login';
})

//Display/hide sidebar on smaller screens
sidebarOpenBtn.addEventListener('click', () => {
  sidebar.classList.toggle('open');
})
sidebarCloseBtn.addEventListener('click', () => {
  sidebar.classList.toggle('open');
})

//Popualte database button listener
seedDbBtn && seedDbBtn.addEventListener('click', async () => {
  workoutContainer.style.display = "none";
  paginationContainer.style.display = "none";
  loader.style.display = "block";

  await apiCall("http://localhost/workout-blog/api/seed-db", "GET");
  window.location.reload();
})

//Initialize application
getWorkouts();

