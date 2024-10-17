import apiCall from "../apiCall.js";
import generateWorkout from "../generateWorkout.js";

document.addEventListener("DOMContentLoaded", () => {
  const workoutContainer = document.getElementById('workoutContainer');
  const logoutBtn = document.getElementById('logoutButton');
  
  const openModal = document.getElementById('openModal');
  const closeModal = document.getElementById('closeModal');
  const modal = document.getElementById('modal');
  const modalContent = document.getElementById('modalContent');
  const form = document.getElementById("addWorkoutForm");
  const responseMsg = document.getElementById("responseMessage");



  const getWokrouts = async () => {
    const url = "http://localhost/workout_blog/api/workouts";
    const result = await apiCall(url, "GET");
    result.forEach(workout => {
      const workoutHtml = generateWorkout(workout);
      workoutContainer.insertAdjacentHTML('beforeend', workoutHtml);
    });
  }

  getWokrouts();

  logoutBtn.addEventListener('click', async() => {
    const result = await apiCall("http://localhost/workout_blog/api/logout", "GET");
    if(result.status == 'success'){
      window.location.href = '/workout_blog/login';
    }
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

