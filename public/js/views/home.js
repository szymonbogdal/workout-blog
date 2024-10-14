import apiCall from "../apiCall.js";

document.addEventListener("DOMContentLoaded", () => {
  const logoutBtn = document.getElementById('logoutButton');
  const openModal = document.getElementById('openModal');
  const closeModal = document.getElementById('closeModal');
  const modal = document.getElementById('modal');
  const modalContent = document.getElementById('modalContent');
  
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
    console.log("object");
    if (e.target === modal) {
      modal.style.display = "none";
    }
  });
  modalContent.addEventListener('click', (e) => {
    e.stopPropagation();
  });
});