import apiCall from "../apiCall.js";
import generateWorkout from "../generateWorkout.js";
import debounce from "../debounce.js";

document.addEventListener("DOMContentLoaded", () => {
  const workoutContainer = document.getElementById('workoutContainer');
  const loaderContainer = document.getElementById('loaderContainer');
  const responseError = `<p class="workout__response">There was some error. Please try again later.</p>`
  const responseEmpty = `<p class="workout__response">No workouts found.</p>`

  const textInputFilters = document.querySelectorAll(".input");
  const btnFilters = document.querySelectorAll(".btn-difficulty")
  const sortFilter = document.getElementById('sortFilter');
  
  const logoutBtn = document.getElementById('logoutButton');
  
  const openModal = document.getElementById('openModal');
  const closeModal = document.getElementById('closeModal');
  const modal = document.getElementById('modal');
  const modalContent = document.getElementById('modalContent');
  const form = document.getElementById("addWorkoutForm");
  const responseMsg = document.getElementById("responseMessage");

  const getWokrouts = async (params = {}) => {
    workoutContainer.innerHTML = null;
    workoutContainer.style.display = "none";
    loaderContainer.style.display = "block";
    const url = "http://localhost/workout_blog/api/workouts";
    const result = await apiCall(url, "GET", params);
    loaderContainer.style.display = "none";
    workoutContainer.style.display = "block";

    if(result?.status == 'failed'){
      workoutContainer.insertAdjacentHTML('beforeend', responseError);
      return;
    }

    if(result.length == 0){
      workoutContainer.insertAdjacentHTML('beforeend', responseEmpty);
      return;
    }

    result.forEach(workout => {
      const workoutHtml = generateWorkout(workout);
      workoutContainer.insertAdjacentHTML('beforeend', workoutHtml);
    });
    const likeBtns = document.querySelectorAll(".header__likes-button");
    likeBtns.forEach(btn => {
      btn.addEventListener('click', async (e) => {
        const likeCountElement = e.target.closest(".header__likes").querySelector(".header__likes-count");
    
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
    
  }
  getWokrouts();

  let filters = {};
  const filterWorkouts = (updates) => {
    for(const [key, val] of Object.entries(updates)){
      if(val === ""){
        delete filters[key];
      }else{
        filters = { ...filters, [key]: val };
      }
    }
    getWokrouts(filters);
  }
  const debouncedHandler = debounce((e) => {
    filterWorkouts({[e.target.name]: e.target.value})
  }, 300);

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

  let activeButton = null;
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

  logoutBtn.addEventListener('click', async() => {
    workoutContainer.style.display = "none";
    loaderContainer.style.display = "block";
    const result = await apiCall("http://localhost/workout_blog/api/logout", "GET");
    if(result.status == 'success'){
      window.location.href = '/workout_blog/login';
    }
    workoutContainer.style.display = "block";
    loaderContainer.style.display = "none";
  })

  openModal.addEventListener('click', () => {
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
});

