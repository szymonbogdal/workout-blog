<div class="container">
  <div class="sidebar">
    <h2 class="sidebar__title">Workout Planner</h2>
    <input class="input input--main" placeholder="Search workouts...">
    <section class="filters">
      <h3 class="filters__header">Filters</h3>
      <h4 class="filters__subheader">Difficulty</h4>
      <div class="btn-group">
        <button class="btn-difficulty">Beginner</button>
        <button class="btn-difficulty">Intermediate</button>
        <button class="btn-difficulty">Advanced</button>
      </div>
      <h4 class="filters__subheader">Days a week</h4>
      <input class="input input--secondary" placeholder="Search days a week...">
      <h4 class="filters__subheader">Sort by</h4>
      <select class="sort-select">
        <option value="popularity">Popularity</option>
        <option value="duration">Duration</option>
        <option value="date">Date</option>
      </select>
    </section>
    <section class="user-data">
      <hr class="separator">
      <p class="profile-link">Profile</p>
      <p class="profile-link" id="logoutButton">Logout</p>   
    </section>
  </div>
  <div class="body">
    <div class="workout">
      <div class="workout__header">
        <div>
          <h2 class="header__tilte">Push Pull Legs Training</h2>
          <div class="header__description">
            <p class="header__description-item">by Szymon</p>
            <p class="header__description-item">&#x2022;</p>
            <p class="header__description-item">Intermediate level</p>
          </div>
        </div>
        <div class="header__likes">
          <button class="header__likes-button">&#x2764;</button>
          <p class="header__likes-count">123</p>
        </div>  
      </div>
      <div class="workout__container">
        <div class="workout__day">
          <h4 class="workout__day-title">Push day</h4>
          <ul class="workout__day-list">
            <li class="workout__day-exercise">Flat Bench press</li>
            <li class="workout__day-exercise">Incline Bench press</li>
            <li class="workout__day-exercise">Rope pulldown</li>
            <li class="workout__day-exercise">Lateral rises</li>
          </ul>
        </div>
        <div class="workout__day">
          <h4 class="workout__day-title">Pull day</h4>
          <ul>
            <li class="workout__day-exercise">Lat pulldown</li>
            <li class="workout__day-exercise">T-bar row</li>
            <li class="workout__day-exercise">Biceps curls</li>
          </ul>
        </div>
        <div class="workout__day">
          <h4 class="workout__day-title">Leg day</h4>
          <ul>
            <li class="workout__day-exercise">Squat</li>
            <li class="workout__day-exercise">Lep extensions</li>
            <li class="workout__day-exercise">Leg curls</li>
            <li class="workout__day-exercise">Calf raises</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>