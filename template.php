<?php
$date_string = "2018-01-08";
$week = date("W", strtotime($date_string));
$Getfirstweekrecipe=new ManageGallery();
$new_date= date("W", strtotime("$date_string"));
if(isset($_GET['search_key']))
{
	$wp_query=$Getfirstweekrecipe->GetRecipesBasedonWeek('recipe',$new_date,$_GET['search_key']);
	$total_count = $Getfirstweekrecipe->GetRecipesCountBasedonWeek('recipe',$new_date,$_GET['search_key']);

}
else
{
	$wp_query=$Getfirstweekrecipe->GetRecipesBasedonWeek('recipe',$new_date);
	$total_count = $Getfirstweekrecipe->GetRecipesCountBasedonWeek('recipe',$new_date);
}

$k=0;
$page_limit=9;
$number_of_pages=ceil( $total_count / $page_limit );
//print_r($wp_query);
$today = date("Y-m-d");
?>
<div id="week1" class="tab-pane in <?php if($today_date_week==2) { ?>   active <?php } ?>">
	<?php  if (have_posts()) : ?>
    <div id="galery_entries_1" class="related_recipes row">
<!--    <span class="loader"><img src="<?php bloginfo('template_directory') ?>/img/ajax-loader.gif" /></span>
-->		<?php while ( $wp_query->have_posts() ) : $wp_query->the_post();
        $k++;
	  $checkuservoted=new ManageVotes;
	  $getuservotedcount=$checkuservoted->GetPostCount($post->ID);
	 // print_r($getuservotedcount);
        ?>
        
         <?php 
			$attachment_id1 = get_post_thumbnail_id($post->ID);
			$size1 = "gallery-small-img"; // (thumbnail, people-img, large, full or custom size)
			$image1 = wp_get_attachment_image_src( $attachment_id1, $size1 );
			$recipe_name = '';
			$recipe_name = $post->post_title;
				
			$author_name = '';
			$author_name = get_the_author($post->ID);
		?>
        <div class="col-md-4 col-sm-6">
        	<a href="<?php the_permalink();?>">
                <img src="<?php echo get_the_post_thumbnail_url($post); ?>" title="<?php echo $post->post_title;?>" alt="<?php echo $post->post_title;?>" class="img-responsive related_recipe_image" >
                <?php if($post->ID==5517){ ?>
                    <div class="weekly_winner"><img src="<?php bloginfo('template_directory') ?>/images/winner.png" /></div>
				<?php } ?>
                <div class="vote-img">
                    <i class="fa fa-heart" aria-hidden="true"></i>
                    <span class="abs1 number"><span id="vt_count_<?php echo $post->ID; ?>" class="vtcount"><?php echo $getuservotedcount; ?></span></span>
                </div>
            </a>
            
            <div class="title_n_author clearfix">
                <div class="title inline-block"><a href="<?php the_permalink();?>"><?php echo $recipe_name;?></a></div>
                <div class="auhtor_name inline-block">By <?php echo $author_name; ?></div>
            </div>
            <div class="view_recipe clearfix">
                <div class="col-md-6 col-xs-6 no-padding">
                    <a class="link" href="<?php the_permalink();?>">
                        <img src="<?php bloginfo('template_directory') ?>/images/view_recipe.png"> View Recipe
                    </a>
                </div>
        
            </div>
            
            
        </div>
        
        <?php endwhile; wp_reset_query(); ?>
        
      </div> 
      <?php if($total_count > 9) { ?>
      	<div class="height20 clearfix"></div>
        <div class="height20 clearfix"></div>
        <div class="col-md-12 text-center">
            <a title="Load More Entries"  id="load_entries_first_week" class="load_entries_first_week loader shadow_button blue_shadow" data-week="<?php echo $new_date; ?>" data-page="2" data-total="<?php echo $number_of_pages; ?>"><i id="spinner1" class="fa fa-spinner fa-spin" aria-hidden="true" style="display:none"></i> Load More Entries</a>
        </div>
    	<?php } ?>
    
        <div class="clearfix"></div>
    <?php else: ?>
    <?php if(!isset($_GET['search_key']))
		{
	 ?>
     <div class="text-center white uppercase font20" style="margin-top:50px;"><strong>No Recipes just yet.</strong></div>
     <?php } ?>
     <?php if(isset($_GET['search_key']))
		{
	 ?>
     <div class="text-center white uppercase font20" style="margin-top:50px;"><strong>No results found</strong></div>
     <?php } ?>
    <?php endif; ?>
 </div>
 <script>
unction Paginate(num_pages,week_num,total_pages,paginateclass,containerId,index,todays_date,week_end_date,voted_week)
{
		var tableRow=''
		//var search_exists='';
		
		console.log("search"+search_exists);
		jQuery.ajax({
			type : 'post',
			url : '<?php echo admin_url( 'admin-ajax.php' ); ?>',
			data : {
			action : 'load_search_results',
			num_pages: num_pages,
			week_num: week_num,
			total_pages: total_pages,
			search_exists:search_exists
			
			},
			beforeSend: function() {
			},
			success : function(response) {
				$('#spinner'+index).hide();
				$('#nonspinner'+index).show();
			if(response!='no_paginate')
			{
				var data = JSON.parse(response);
				console.log('votedwekk'+voted_week);
				console.log(data);
				for (var i = 0; i < data.length; i++) 
				{
					var votedata='';
					if (todays_date < week_end_date)
					{ 
						if(data[i]['logged_in_status']=='not_logged_in')
						{
							console.log('in');
							votedata='<a style="cursor:pointer" title="Vote" data-toggle="modal" data-target="#vote"><i class="fa fa-heart-o" aria-hidden="true" style="color:#868686;padding-right:5px;"></i> <span class="white">Vote</span></a>';
						}
						
					}
					tableRow +='<div class="col-md-4 col-sm-6"><a href="'+data[i]['link']+'"><img src="'+data[i]['image']+'" alt="'+data[i]['title']+'" class="img-responsive related_recipe_image">';
					if(data[i]['rpost_id']==5517 || data[i]['rpost_id']==5909 || data[i]['rpost_id']==7065 || data[i]['rpost_id']==7429 || data[i]['rpost_id']==9278 || data[i]['rpost_id']==10850 || data[i]['rpost_id']==12552 )
					{
						tableRow +='<div class="weekly_winner"><img src="<?php bloginfo('template_directory') ?>/images/winner.png" /></div>';
					}
					tableRow +='<div class="vote-img"><i class="fa fa-heart" aria-hidden="true"></i><span class="abs1 number"><span id="vt_count_'+data[i]['rpost_id']+'" class="vtcount">'+data[i]['votecount']+'</span></span></div></a><div class="title_n_author clearfix"><div class="title inline-block"><a href="'+data[i]['link']+'">'+data[i]['title']+'</a></div><div class="auhtor_name inline-block">By '+data[i]['author'] +'</div></div><div class="view_recipe clearfix"><div class="col-md-6 col-xs-6 no-padding"><a class="link" href="'+data[i]['link']+'"><img src="<?php echo get_stylesheet_directory_uri()?>/images/view_recipe.png"> View Recipe</a></div><div class="col-md-6 col-xs-6 no-padding">'+votedata+'</div></div></div>';
				}
				$('#'+containerId).append(tableRow);
				$('.'+paginateclass).attr("data-page",parseInt(num_pages)+1)
				var updated_page_num=$('.'+paginateclass).attr("data-page")
				console.log(updated_page_num);
				console.log(total_pages);
				if(parseInt(updated_page_num)>parseInt(total_pages))
				{
					$('.'+paginateclass).hide()
				}
			  }
				else
				{
					$('.'+paginateclass).hide()
				}
			}
		});
		
		return false;
}
//First week load more entries
jQuery(document).on( 'click', '.load_entries_first_week', function() {
   var num_pages=$(this).attr("data-page") ;
   var week_num=$(this).attr("data-week");
   var total_pages=$(this).attr("data-total");
   $('#spinner1').show();
   $('#nonspinner1').hide();
   var todays_date='<?php echo $today;  ?>';
   var week_end_date='<?php echo $week1_end;  ?>';
   
   setTimeout(function(){Paginate(num_pages,week_num,total_pages,'load_entries_first_week','galery_entries_1','1',todays_date,week_end_date,1) }, 2000);
   
})
 </script>
 