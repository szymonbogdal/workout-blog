<div class="loader__container" id="loaderContainer">
    <div class="loader"></div>
</div>
<div class="container">
  <div class="user-info">
    <h2 class="user-name">tymoteusz</h2>
    <div class="user-stats">
      <p>2 posts</p>
      <p>&#x2022;</p>
      <p>100 total likes</p>
    </div>
    <button class="user-stats__button" id='modalNewOpen'>Create new workout plan</button>
    <a class="user-stats__return" href="/workout_blog">Go back to main page</a>
  </div>
  <div class="content">
    <div class="filters">
      <div class="button-group">
        <button class="action-button action-button--active">Your posts</button>
        <button class="action-button">Liked posts</button>
      </div>
      <div class="filters__group">
        <label for="titleFilter" class="filters__label">Search title</label>
        <input type="text" name="title" class="filters__input" id="titleFilter">
      </div>
      <div class="filters__group">
        <label for="sortFilter" class="filters__label">Sort</label>
        <select class="filters__input" id="sortFilter">
          <option value="like_count" data-order="DESC">Most popular</option>
          <option value="created_at" data-order="DESC">Newest</option>
          <option value="created_at" data-order="ASC">Oldest</option>
          <option value="week_days" data-order="DESC">Longest</option>
          <option value="week_days" data-order="ASC">Shortest</option>
        </select>
      </div>
    </div>
    <div class="workout-container" id="workoutContainer">

    </div>
    <div class="pagination-container" id="paginationContainer">

    </div>
  </div>

  <div class="modal" id="modalNew">
    <div class="modal-content" id="modalNewContent">
      <form class="modal-form" id="addWorkoutForm">
        <h2 class="form-title">Create new workout plan</h2>
        <span class="modal-close" id="modalNewClose">&times;</span>
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

  <div class="modal" id="modalDelete">
    <div class="modal-content modal-content--delete" id="modalDeleteContent">
      <h2 class="modal-title">Delete workout</h2>
      <p class="modal-desc">This action cannot be undone!</p>
      <button id="modalDeleteCancel" class="modal-button">Cancel</button>
      <button id="modalDeleteApprove" class="modal-button modal-button--delete">Delete</button>
    </div>
  </div>
</div>