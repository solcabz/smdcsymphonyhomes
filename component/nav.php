<header class="site-header">
  <div class="container">
    <div class="logo">
      <a href="<?php echo esc_url(home_url('/')); ?>">
        <img src="<?php echo esc_url(get_theme_file_uri('/assets/images/symphony-logo.png')); ?>" alt="symphony homes logo" />
      </a>
    </div>

    <div class="wrapper-nav">
      <div class="hide-mobile">
        <div class="search-container">
          <form class="search-box" role="search" method="get" action="<?php echo esc_url(home_url('/')); ?>">
              <input id="search" class="search-field" type="text" placeholder="What are you looking for?" value="<?php echo get_search_query(); ?>" name="s" autocomplete="off" />
              <button type="submit" class="search-btn" aria-label="Search">
                <!-- <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#002349" viewBox="0 0 256 256"><path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path></svg> -->
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="#002349" viewBox="0 0 256 256"><path d="M232.49,215.51,185,168a92.12,92.12,0,1,0-17,17l47.53,47.54a12,12,0,0,0,17-17ZM44,112a68,68,0,1,1,68,68A68.07,68.07,0,0,1,44,112Z"></path></svg>
              </button>
          </form>
          <div class="search-results-dropdown" id="search-suggestions" style="display: none;">
            <div class="search-results-content"></div>
          </div>
        </div>

        <a class="btn button-text">Get a Quote</a>
      </div>
      <button class="menu-toggle" aria-label="Toggle Menu" aria-expanded="false">
        <span></span>
        <span></span>
        <span></span>
      </button>
    </div>

    <!-- Unified mobile menu -->
    <nav class="menu-wrap" id="mobile-menu">
      <a href="#">Home</a>
      <a href="#">About</a>
      <a href="#">Projects</a>
      <a href="#">Contact</a>
    </nav>
  </div>
</header>
