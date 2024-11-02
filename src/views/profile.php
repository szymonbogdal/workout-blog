<div class="container">
  <div class="user-info">
    <h2 class="user-name">tymoteusz</h2>
    <div class="user-stats">
      <p>2 posts</p>
      <p>&#x2022;</p>
      <p>100 total likes</p>
    </div>
    <button class="user-stats__button">Create new workout plan</button>
    <a class="user-stats__return" href="/workout_blog">Go back to main page</a>
  </div>
  <div class="content">
    <div class="filters">
      <div class="button-group">
        <button class="action-button action-button--active">Your posts</button>
        <button class="action-button">Liked posts</button>
      </div>
      <div class="filters__group">
        <label for="title" class="filters__label">Search title</label>
        <input type="text" class="filters__input" id="title">
      </div>
      <div class="filters__group">
        <label for="sort" class="filters__label">Sort</label>
        <select class="filters__input" id="sort">
          <option value="like_count" data-order="DESC">Most popular</option>
          <option value="created_at" data-order="ASC">Newest</option>
          <option value="created_at" data-order="DESC">Oldest</option>
          <option value="week_days" data-order="DESC">Longest</option>
          <option value="week_days" data-order="ASC">Shortest</option>
        </select>
      </div>
    </div>
    <div class="workout-container" id="workoutContainer">

    </div>
  </div>
</div>