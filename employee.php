<?php get_header(); ?>

<div class="container" id="page">
    <div class="container-inner">			
        <div class="main">
            <div class="content">
                    <?php 

                        $name     = isset( $_GET['employee-name'] ) ? $_GET['employee-name'] : ' ' ;
                        $sscyear  = isset( $_GET['sscyear'] ) ? $_GET['sscyear'] : ' ';
                        $skills   = isset( $_GET['skills'] ) ? $_GET['skills'] : ' ';

                        $employee = new WP_Query( [
                            'post_type'         => 'employee_list',
                            'posts_per_page'    => -1,
                            's'                 => $name,
                            'meta_query'        => [
                                'relation'          => 'AND',
                                [
                                    'key'       => 'ei_sscyear',
                                    'value'     => $sscyear,
                                    'compare'   => 'LIKE',
                                ],
                                [
                                    'key'       => 'ei_skills',
                                    'value'     => $skills,
                                    'compare'   => 'LIKE',
                                ],
                            ]
                        ] );

                        if ( $employee->have_posts() ) {

                            while( $employee->have_posts() ) : $employee->the_post();
                            
                            $designation = get_post_meta( get_the_id(), 'ei_designation', true );
                            $experience  = get_post_meta( get_the_id(), 'ei_skills', true );
                            $sscyear     = get_post_meta( get_the_id(), 'ei_sscyear', true );
                    
                        ?>
                        
                            <div class="blog-card-outer" style="margin-bottom: 20px; background: #ffffff; padding: 20px;">
                                <h2 style="margin-bottom: 10px; font-size: 20px;"><?php the_title(); ?></h2>
                                <p>
                                    <strong>Designation:</strong> <?php echo $designation; ?> 
                                    &nbsp; 
                                    <strong>Experience:</strong> <?php echo $experience; ?> 
                                    &nbsp; 
                                    <strong>SSC Year:</strong> <?php echo $sscyear; ?>
                                </p>
                            </div>
                        
                    <?php endwhile;
                        } else { ?>
                            <h2 style="font-size: 20px;">No match employe found!</h2>
                        <?php }
                    ?>
            </div>
        </div>
    </div>
</div>

<?php get_footer(); ?>