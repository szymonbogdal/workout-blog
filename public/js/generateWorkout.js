const generateWorkout = (workout, renderDelete = false) => {
  const workoutDaysHTML = workout.workout_days.map((day, index) => `
  <div class="workout__day">
    <h4 class="workout__day-title">${`Day ${day.day_order}`}</h4>
    <ul class="workout__day-list">
      ${day.body.split('\r\n').map(exercise => `
        ${exercise && '<li class="workout__day-exercise">' + exercise + '</li>'}
      `).join('')}
    </ul>
  </div>
`).join('');

return `
    <div class="workout">
      <div class="workout__header">
        <div>
          <h2 class="header__tilte">${workout.title}</h2>
          <div class="header__description">
            <p class="header__description-item">by ${workout.author}</p>
            <p class="header__description-item">&#x2022;</p>
            <p class="header__description-item">${workout.difficulty} level</p>
          </div>
        </div>
        <div class="header__options">
          <button 
            class="options__like" 
            data-liked=${workout.is_liked_by_user ? 'true' : 'false'}
            data-workout=${workout.id}
            ${window.userId !== 0 ? '' : 'disabled'}
          >
            &#x2764;
          </button>
          <p class="options__count">${workout.like_count}</p>
          ${renderDelete ? '<button class="options__delete" data-workout='+workout.id+'>Delete</button>' : ''}
        </div>  
      </div>
      <div class="workout__container">
        ${workoutDaysHTML}
      </div>
    </div>
  `;
}

export default generateWorkout;
