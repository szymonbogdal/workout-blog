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
  
  const openModal = document.getElementById('openModal');
  const closeModal = document.getElementById('closeModal');
  const modal = document.getElementById('modal');
  const modalContent = document.getElementById('modalContent');
  const form = document.getElementById("addWorkoutForm");
  const responseMsg = document.getElementById("responseMessage");

  let state = {page: 1};
  let currentAction = "author";
  
  let activeBtnIndex = 0;
  actionBtns.forEach((btn, index) => {
    btn.addEventListener("click", () => {
      if(activeBtnIndex !== index){
        actionBtns[activeBtnIndex].classList.remove("action-button--active");
        btn.classList.add("action-button--active");
        activeBtnIndex = index;
        if(index === 1 && currentAction === "author"){
          currentAction = "liked";
          getWorkouts();
          return;
        }

        if(index === 0 && currentAction === "liked"){
          currentAction = "author";
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

  openModal && openModal.addEventListener('click', () => {
    modal.style.display = "block";
  })
  closeModal.addEventListener('click', () => {
    modal.style.display = "none";
  })
  modal.addEventListener('click', (e) => {
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
  modalContent.addEventListener('click', (e) => {
    e.stopPropagation();
  });

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(form);
    const url = "http://localhost/workout_blog/api/workouts/new";
    const result = await apiCall(url, "POST", formData);
    if(result?.status == 'success'){
      modal.style.display = "none";
    }else{
      responseMsg.innerHTML = result.message ? result.message : "Something went wrong. Try again later";
    }
  })
})