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
      <?php
        if(isset($_SESSION['user_id'])){
          echo "<a href='profile' class='profile-link'>Profile</a>";
          echo "<p class='profile-link' id='logoutButton'>Logout</p>";
        }else{
          echo "<p class='profile-link' id='loginButton'>Login</p>";
        }
      ?>
    </section>
  </div>

  
  <div class="content">
    <div class="loader--workout" id="loader"></div>  
    <div class="workout-container" id="workoutContainer">
    
    </div>
    <div class="pagination-container" id="paginationContainer">

    </div>
  </div>
</div>