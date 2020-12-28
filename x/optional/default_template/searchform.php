<form id="searchform" role="search" method="get" action="<?php bloginfo("siteurl")?>">
    <div class="form-group">
      <input type="text" class="form-control" placeholder="keywords" name="s" value="<?php echo esc_html( get_search_query() );?>">
    </div>
	<input type="submit" class="btn btn-success" id="searchsubmit" value="Search">
</form>