<?php 

class ManageGallery
{
	public function GetRecipesBasedonWeek($posttype,$w,$searchkey=null)
    {
	 //echo $int =  intval($w);
//echo "Week". date('W');
		$no_of_post=9;
		$paged=get_query_var('paged') ? get_query_var('paged') : 1;
		$args=array(
		'post_type' => $posttype,
		'post_status' => 'publish,future',
		'order'=>'DESC',
		'posts_per_page' => $no_of_post,
		's'=> $searchkey,
		'paged' => $paged,
		'date_query' => array( // in the last week
            array( 
                'year' => 2018,
                'week' => $w,
            ),
        'fields' => 'ids' // only return an array of post IDs
   	 )
		);
		return $wp_query = new WP_Query($args);
	} 
	public function GetRecipesCountBasedonWeek($posttype,$w,$searchkey=null)
    {
	 //echo $int =  intval($w);
//echo "Week". date('W');
		$paged=get_query_var('paged') ? get_query_var('paged') : 1;
		$args=array(
		'post_type' => $posttype,
		'post_status' => 'publish,future',
		's'=> $searchkey,
		'order'=>'DESC',
		'date_query' => array( // in the last week
            array( 
                'year' => 2018,
                'week' => $w,
            ),
        'fields' => 'ids' // only return an array of post IDs
    )
		);
		 $wp_query = new WP_Query($args);
		return $wp_query->post_count;
	} 
}

add_action( 'wp_ajax_load_search_results', 'load_search_results_callback' );
add_action( 'wp_ajax_nopriv_load_search_results', 'load_search_results_callback' );
//ajax paginattion results
function load_search_results_callback() {
	global $wpdb;
	$num_pages=$_POST['num_pages'];
	$week_num=$_POST['week_num'];
	$total_pages=$_POST['total_pages'];
	$search_exists=$_POST['search_exists'];
    $no_of_post=9;
	$paged=$num_pages;
	$pushfiles=array();
	if($num_pages<=$total_pages)
	{
			$args1=array(
			'post_type' =>'recipe',
			'post_status' => 'publish,future',
			'order'=>'DESC',
			'posts_per_page' => $no_of_post,
			'paged' => $num_pages,
			's'=> $search_exists,
			'date_query' => array( // in the last week
				array( 
					'year' => 2018,
					'week' => $week_num,
				)
		)
			);
	
		$wp_query1 = new WP_Query($args1);
		$k=0;
		while ( $wp_query1->have_posts() ) : $wp_query1->the_post(); 
			$loginstatus=''; 
			$voted_status='';
			$k++;
			global $current_user;
			//print_r($wp_query1);
	    	get_currentuserinfo();
			if (!is_user_logged_in()) 
			{
				$loginstatus='not_logged_in';
			}
			else
			{
				$loginstatus='logged_in';
				$myrows = $wpdb->get_row( "SELECT * FROM lny_votes where uid='".$current_user->ID."' AND author_id='".get_the_author_ID()."'" );
				if($myrows==0)
				{
					$voted_status='not_voted';
				}
				else
				{
					$voted_status='voted';
					$res = array();
					$res[]= count($myrows);
					$res[] = $myrows->pid;	
					$res[] = get_the_title($myrows->pid);
					$post_author_id=get_post_field( 'post_author', $myrows->pid );
					$at= get_the_author_meta('display_name',$post_author_id );
					$res[] = $at;
				}
			}
			$attachment_id1 = get_post_thumbnail_id($post->ID);
			$voteresults= $wpdb->get_results("SELECT * FROM lny_votes where pid='".get_the_ID()."'");
			$rowcount = count($voteresults);
			
			$size1 = "gallery-small-img"; // (thumbnail, people-img, large, full or custom size)
			$image1 = wp_get_attachment_image_src( $attachment_id1, $size1 );
			$finalarray=array(
				'title'=>get_the_title($post->ID),
				'author'=>get_the_author(),
				'image'=> $image1[0],
				'link'=>get_permalink($post->ID),
				'logged_in_status'=>$loginstatus,
				'vote_status'=>$voted_status,
				'rpost_id'=>get_the_ID(),
				'user_id'=>$current_user->ID,
				'votecount'=>$rowcount,
				'recipe_publish_date'=>get_the_date( "Y-m-d H:i:s",$post->ID ),
				'aid'=>get_the_author_ID(),
				'voted_results'=>$res
			
			); 
			array_push($pushfiles,$finalarray);
		endwhile;
		echo json_encode($pushfiles);
	}
	else
	{
		echo 'no_paginate';
	}
	die();
	
}
?>