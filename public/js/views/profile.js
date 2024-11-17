import apiCall from "../apiCall.js";
import generateWorkout from "../generateWorkout.js";
import generatePagination from "../generatePagination.js";
import debounce from "../debounce.js";
import formatNumber from "../formatNumber.js";

const workoutContainer = document.getElementById('workoutContainer');
const paginationContainer = document.getElementById("paginationContainer");
const loaderContainer = document.getElementById('loaderContainer');

const actionBtns = document.querySelectorAll('.action-button');
const tileFilter = document.getElementById("titleFilter");
const sortFilter = document.getElementById("sortFilter");

const modalNewOpen = document.getElementById('modalNewOpen');
const modalNewClose = document.getElementById('modalNewClose');
const modalNew = document.getElementById('modalNew');
const modalNewContent = document.getElementById('modalNewContent');
const form = document.getElementById("addWorkoutForm");
const responseMsg = document.getElementById("responseMessage");

const modalDelete = document.getElementById("modalDelete");
const modalDeleteContent = document.getElementById('modalDeleteContent');
const modalDeleteCancel = document.getElementById("modalDeleteCancel");
const modalDeleteApprove = document.getElementById('modalDeleteApprove');

const postCount = document.getElementById("postCount");
const likeCount = document.getElementById("likeCount");
const userNameField = document.getElementById("userName");

const responseError = `<p class="workout__response">There was some error. Please try again later.</p>`;
const responseEmpty = `<p class="workout__response">No workouts found.</p>`;

let state = {page: 1};
let currentAction = "author";
let deleteWorkoutId = 0;
let activeBtnIndex = 0;

//Display user statistics
const getStatistics = async () => {
  const url = `http://localhost/workout_blog/api/users/${window.userId}/statistics`;
  const result = await apiCall(url, "GET");
  userNameField.innerHTML = result.username;
  postCount.innerHTML = `${formatNumber(result.workout_count)} posts`;
  likeCount.innerHTML = `${formatNumber(result.workout_likes)} likes`;
}

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
  
      const url = `http://localhost/workout_blog/api/workouts/${e.target.dataset.workout}/like`;
      const result = await apiCall(url, "POST");
      
      if(result?.status === 'error'){
        e.target.dataset.liked = initialLiked ? "true" : "false";
        likeCountElement.textContent = initialLikeCount;
      }
    });
  });
}

//Add event listener to delete button on every workout
const setupDeleteBtns = () => {
  if(currentAction === "author"){
    const deleteBtns = document.querySelectorAll(".options__delete");
    deleteBtns.forEach((btn)=>{
      btn.addEventListener("click", (e)=>{
        modalDelete.style.display = "block";
        deleteWorkoutId = e.target.dataset.workout;
      })
    })
  }
}

//Get all workouts, re-aply pagination, like and delete buttons listeners
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
    const workoutHtml = generateWorkout(workout, currentAction === "author");
    workoutContainer.insertAdjacentHTML('beforeend', workoutHtml);
  });

  renderPagination(result.total_pages);
  setupLikeBtns();
  setupDeleteBtns();
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
tileFilter.addEventListener('input', debouncedHandler);
sortFilter.addEventListener('change', (e)=>{
  const selectedOption = e.target.options[e.target.selectedIndex];
  filterWorkouts({
    sort: selectedOption.value,
    order: selectedOption.dataset.order
  })
})

//Listener to swap between Your posts and Liked posts section
actionBtns.forEach((btn, index) => {
  btn.addEventListener("click", () => {
    if(activeBtnIndex !== index){
      actionBtns[activeBtnIndex].classList.remove("action-button--active");
      btn.classList.add("action-button--active");
      activeBtnIndex = index;
      if(index === 1 && currentAction === "author"){
        currentAction = "liked";
        state.page = 1;
        getWorkouts();
        return;
      }

      if(index === 0 && currentAction === "liked"){
        currentAction = "author";
        state.page = 1;
        getWorkouts();
        return;
      }
    }
  })
})

//Event listeners for delete workout modal
modalDeleteCancel.addEventListener('click', () => {
  modalDelete.style.display = "none";
  deleteWorkoutId = 0;
});
modalDelete.addEventListener('click', (e) => {
  if (e.target === modalDelete) {
    modalDelete.style.display = "none";
  }
});
modalDeleteContent.addEventListener('click', (e) => {
  e.stopPropagation();
});
modalDeleteApprove.addEventListener('click', async () => {
  if(deleteWorkoutId != 0){
    loaderContainer.style.display = "flex";
    modalDelete.style.display = "none";
    const url = `http://localhost/workout_blog/api/workouts/${deleteWorkoutId}`;
    const result = await apiCall(url, "DELETE");
    deleteWorkoutId = 0;
    if(result?.status === "success"){
      state.page = 1;
      getWorkouts();
    }else{
      loaderContainer.style.display = "none";
    }
  }
})

//Event listeners for new workout modal
modalNewOpen.addEventListener('click', () => {
  modalNew.style.display = "block";
})
modalNewClose.addEventListener('click', () => {
  modalNew.style.display = "none";
})
modalNew.addEventListener('click', (e) => {
  if (e.target === modal) {
    modalNew.style.display = "none";
  }
});
modalNewContent.addEventListener('click', (e) => {
  e.stopPropagation();
});
form.addEventListener('submit', async (e) => {
  e.preventDefault();
  const formData = new FormData(form);
  const url = "http://localhost/workout_blog/api/workouts";
  const result = await apiCall(url, "POST", formData);
  if(result?.status == 'success'){
    modalNew.style.display = "none";
    getWorkouts();
  }else{
    responseMsg.innerHTML = result.message ? result.message : "Something went wrong. Try again later";
  }
});

//Initialize application
getStatistics();
getWorkouts();