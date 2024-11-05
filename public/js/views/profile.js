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

  let state = {page: 1};
  let currentAction = "author";
  let deleteWorkoutId = 0;
  
  let activeBtnIndex = 0;
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

    const likeBtns = document.querySelectorAll(".options__like");
    likeBtns.forEach(btn => {
      btn.addEventListener('click', async (e) => {
        const likeCountElement = e.target.closest(".header__options").querySelector(".options__count");
    
        const initialLiked = e.target.dataset.liked === "true";
        const initialLikeCount = parseInt(likeCountElement.textContent);
    
        e.target.dataset.liked = initialLiked ? "false" : "true";
        likeCountElement.textContent = initialLiked ? initialLikeCount - 1 : initialLikeCount + 1;
    
        const url = "http://localhost/workout_blog/api/workouts/like";
        const result = await apiCall(url, "POST", { workout_id: e.target.dataset.workout });
        
        if(result?.status === 'error'){
          e.target.dataset.liked = initialLiked ? "true" : "false";
          likeCountElement.textContent = initialLikeCount;
        }
      });
    });

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

  modalDeleteCancel.addEventListener('click', () => {
    modalDelete.style.display = "none";
    deleteWorkoutId = 0;
  })

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
      const url = "http://localhost/workout_blog/api/workouts/delete";
      const result = await apiCall(url, "POST", { workout_id: deleteWorkoutId });
      deleteWorkoutId = 0;
      if(result?.status === "success"){
        state.page = 1;
        getWorkouts();
      }else{
        loaderContainer.style.display = "none";
      }
    }
  })

  modalNewOpen && modalNewOpen.addEventListener('click', () => {
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
    const url = "http://localhost/workout_blog/api/workouts/new";
    const result = await apiCall(url, "POST", formData);
    if(result?.status == 'success'){
      modalNew.style.display = "none";
      getWorkouts();
    }else{
      responseMsg.innerHTML = result.message ? result.message : "Something went wrong. Try again later";
    }
  })
})