<div class="container">
  <div class="sidebar">
    <h2 class="sidebar__title">Workout Planner</h2>
    <input class="input input--main" placeholder="Search workouts..." id="titleFilter" name="title">
    <section class="filters">
      <h3 class="filters__header">Filters</h3>
      <h4 class="filters__subheader">Difficulty</h4>
      <div class="btn-group" id="btnGroup">
        <button class="btn-difficulty" value="beginner">Beginner</button>
        <button class="btn-difficulty" value="intermediate">Intermediate</button>
        <button class="btn-difficulty" value="advanced">Advanced</button>
      </div>
      <h4 class="filters__subheader">Days a week</h4>
      <input class="input input--secondary" placeholder="Search days a week..." id="weekDaysFilter" name="week_days">
      <h4 class="filters__subheader">Sort</h4>
      <select class="sort-select" id="sortFilter">
        <option value="like_count" data-order="DESC">Most popular</option>
        <option value="created_at" data-order="ASC">Newest</option>
        <option value="created_at" data-order="DESC">Oldest</option>
        <option value="week_days" data-order="DESC">Longest</option>
        <option value="week_days" data-order="ASC">Shortest</option>
      </select>
    </section>
    <section class="user-data">
      <hr class="separator">
      <p class="profile-link" id="openModal">Create workout</p>
      <p class="profile-link">Profile</p>
      <p class="profile-link" id="logoutButton">Logout</p>   
    </section>
  </div>

  <div class="workout-container" id="workoutContainer">
    
  </div>

  <div class="modal" id="modal">
    <div class="modal-content" id="modalContent">
      <form class="modal-form" id="addWorkoutForm">
        <h2 class="form-title">Create new workout plan</h2>
        <span class="modal-close" id="closeModal">&times;</span>
        <div class="input-container">
          <label class="form-label" for="title">Title</label>
          <input class="form-input" name="title" id="title" type="text" required>
        </div>
        <div class="select-container">
          <label class="form-label" for="difficulty">Difficulty</label>
          <select class="form-input form-input--select" name="difficulty" id="difficulty">
            <option value="beginner">Beginner</option>
            <option value="intermediate">Intermediate</option>
            <option value="advanced">Advanced</option>
          </select>
        </div>
        <div class="form-subtitle">
          <h3 class="form-subtitle__header">Working days</h3>
          <p class="form-subtitle__description">Leave blank unused working days</p>
        </div>
        <div class="textarea-container">
          <label class="form-label" for="day1">Day 1</label>
          <textarea class="form-input form-input--textarea" id="day1" name="workoutDays[]" required></textarea>
        </div>
        <div class="textarea-container">
          <label class="form-label" for="day2">Day 2</label>
          <textarea class="form-input form-input--textarea" id="day2" name="workoutDays[]"></textarea>
        </div>
        <div class="textarea-container">
          <label class="form-label" for="day3">Day 3</label>
          <textarea class="form-input form-input--textarea" id="day3" name="workoutDays[]"></textarea>
        </div>
        <div class="textarea-container">
          <label class="form-label" for="day4">Day 4</label>
          <textarea class="form-input form-input--textarea" id="day4" name="workoutDays[]"></textarea>
        </div>
        <div class="textarea-container">
          <label class="form-label" for="day5">Day 5</label>
          <textarea class="form-input form-input--textarea" id="day5" name="workoutDays[]"></textarea>
        </div>
        <div class="textarea-container">
          <label class="form-label" for="day6">Day 6</label>
          <textarea class="form-input form-input--textarea" id="day6" name="workoutDays[]"></textarea>
        </div>
        <div class="textarea-container">
          <label class="form-label" for="day7">Day 7</label>
          <textarea class="form-input form-input--textarea" id="day7" name="workoutDays[]"></textarea>
        </div>
        <button class="form-button" type="submit">Add workout</button>
        <div class="response-container">
          <p class="response-message" id="responseMessage"></p>
        </div>
      </form>
    </div>
  </div>
</div>