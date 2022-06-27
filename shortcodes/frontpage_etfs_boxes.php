<?php ?>
<div class="trueshare-main-box-container">
    <div class="trueshare-box-first-row">
        <div class="trueshare-item-box">
            <div class="trueshare-box-titles">
                <div class="trueshare-box-title-one">
                    TRUEPURPOSE
                </div>
                <div class="trueshare-box-title-two">
                    FLDZ
                </div>
                <div class="trueshare-box-title-three">
                    <?php 
                        $post_fldz = get_page_by_title( 'FLDZ', OBJECT, 'etfs' );
                        echo get_post_meta( $post_fldz->ID, 'ETF-Pre-etf-full-name', true ) . ' ETF';
                    ?>
                </div>
            </div>
            <div class="trueshare-box-body">
                <p>
                    <?php  echo $post_fldz->post_content; ?>
                </p>
            </div>
            <div>
                <p class="trueshare-box-subadv"> SUBADVISOR: </p>
                <p class="trueshare-box-subadvname"> 
                    <?php 
                        $sub_fldz = get_post_meta( $post_fldz->ID, 'ETF-Pre-sub-advisor-name', true );
                        echo get_the_title( $sub_fldz );
                    ?>
                </p>
            </div>
            <div class="trueshare-learn-more" onclick="location.href='/etfs/fldz'">
                <p> LEARN MORE </p>
            </div>
        </div>

        <div class="trueshare-item-box">
            <div class="trueshare-box-titles">
                <div class="trueshare-box-title-one">
                    TRUEOUTCOME
                </div>
                <div class="trueshare-box-title-two">
                    Structured Outcome
                </div>
                <div class="trueshare-box-title-three">
                    STRUCTURED OUTCOME SERIES
                </div>
            </div>
            <div class="trueshare-box-body">
                <p>
                    TrueShares Structured Outcome ETFs are designed for
                    investors targeting uncapped growth from largecapitalization equities (tracks the S&P 500 Price Index) while
                    simultaneously seeking to mitigate a specified amount of
                    downside exposure. 
                </p>
            </div>
            <div>
                <p class="trueshare-box-subadv"> SUBADVISOR: </p>
                <p class="trueshare-box-subadvname"> SPIDERROCK ADVISORS </p>
            </div>
            <div class="trueshare-learn-more" onclick="location.href='/product'">
                <p> LEARN MORE </p>
            </div>
        </div>
    </div>

    <div class="trueshare-box-sec-row">
        <div class="trueshare-item-box">
            <div class="trueshare-box-titles">
                <div class="trueshare-box-title-one">
                    TRUEAPLHA
                </div>
                <div class="trueshare-box-title-two">
                    LRNZ
                </div>
                <div class="trueshare-box-title-three">
                    <?php 
                        $post_lrnz = get_page_by_title( 'LRNZ', OBJECT, 'etfs' );
                        echo get_post_meta( $post_lrnz->ID, 'ETF-Pre-etf-full-name', true ) . ' ETF';
                    ?>
                </div>
            </div>
            <div class="trueshare-box-body">
                <p>
                    <?php echo $post_lrnz->post_content; ?>
                </p>
            </div>
            <div>
                <p class="trueshare-box-subadv"> SUBADVISOR: </p>
                <p class="trueshare-box-subadvname"> 
                    <?php  
                        $sub_lrnz = get_post_meta( $post_lrnz->ID, 'ETF-Pre-sub-advisor-name', true );
                        echo get_the_title( $sub_lrnz );
                    ?> 
                </p>
            </div>
            <div class="trueshare-learn-more" onclick="location.href='/etfs/lrnz'">
                <p> LEARN MORE </p>
            </div>
        </div>

        <div class="trueshare-item-box">
            <div class="trueshare-box-titles">
                <div class="trueshare-box-title-one">
                    TRUEINCOME
                </div>
                <div class="trueshare-box-title-two">
                    DIVZ
                </div>
                <div class="trueshare-box-title-three">
                    <?php 
                        $post_divz = get_page_by_title( 'DIVZ', OBJECT, 'etfs' );
                        echo get_post_meta( $post_divz->ID, 'ETF-Pre-etf-full-name', true ) . ' ETF';
                    ?>
                </div>
            </div>
            <div class="trueshare-box-body">
                <p>
                    <?php echo $post_divz->post_content; ?>
                </p>
            </div>
            <div>
                <p class="trueshare-box-subadv"> SUBADVISOR: </p>
                <p class="trueshare-box-subadvname"> 
                    <?php 
                        $sub_divz = get_post_meta( $post_divz->ID, 'ETF-Pre-sub-advisor-name', true );
                        echo get_the_title( $sub_divz );
                    ?> 
                </p>
            </div>
            <div class="trueshare-learn-more" onclick="location.href='/etfs/divz'">
                <p> LEARN MORE </p>
            </div>
        </div>
    </div>

    <div class="trueshare-box-third-row">
        <div class="trueshare-item-box">
            <div class="trueshare-box-titles">
                <div class="trueshare-box-title-one">
                    TRUEPURPOSE
                </div>
                <div class="trueshare-box-title-two">
                    ECOZ
                </div>
            </div>
            <div class="trueshare-box-body">
                <p>
                    <?php 
                        $post_ecoz = get_page_by_title( 'ECOZ', OBJECT, 'etfs' );
                        echo $post_ecoz->post_content;
                    ?>
                </p>
            </div>
            <div>
                <p class="trueshare-box-subadv"> SUBADVISOR: </p>
                <p class="trueshare-box-subadvname"> 
                    <?php 
                        $sub_ecoz = get_post_meta( $post_ecoz->ID, 'ETF-Pre-sub-advisor-name', true ); 
                        echo get_the_title( $sub_ecoz );
                    ?> 
                </p>
            </div>
            <div class="trueshare-learn-more" onclick="location.href='/etfs/ecoz'">
                <p> LEARN MORE </p>
            </div>
        </div>
    </div>
</div>