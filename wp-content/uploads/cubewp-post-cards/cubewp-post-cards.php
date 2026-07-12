<?php
return array (
  'product' => 
  array (
    'default_style' => 
    array (
      'loop-layout-html' => '<div  [loop_post_class{cwp-col-12__cwp-col-md-4}] >
	<div class="cwp-post">
		<div class="cwp-post-thumbnail">
			<a href=" [loop_post_link] ">
				<img src=" [loop_featured_image] " alt="">
			</a>
		
			<div class="cwp-archive-save">
				<div class="cwp-single-save-btns cwp-single-widget">
				[loop_post_save] 
				</div>
			</div>
		</div>
		<div class="cwp-post-content-container">
			<div class="cwp-post-content">
				<h4><a href=" [loop_post_link] "> [loop_the_title] </a>
				</h4>
				[loop_the_content] 
			</div>
			<ul class="cwp-post-terms">
				<li>
					<a href=" [loop_property_type_tax_link] "> [loop_property_type] </a>
				</li>
			</ul>        
		</div>
	</div>
</div>',
      'loop-layout-css' => '/*----Grid View-----*/
.cwp-post {
	background: #ffffff;
	border: 1px solid #e0e0e0;
	border-radius: 5px;
	filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.102218));
	margin: 10px 0px;
	overflow: hidden;
	transition: 300ms;
}

.cwp-post:hover {
	filter: none;
}

.cwp-post-thumbnail {
	height: 220px;
	width: 100%;
	position: relative;
}

.cwp-post .cwp-post-thumbnail img {
	height: 100%;
	object-fit: cover;
	transition: 300ms;
	width: 100%;
}

/*-------List View------*/
.list-view .cwp-col-12 {
	width: 100% !important;
}

.list-view .cwp-post {
	align-items: flex-start;
	display: flex;
	flex-wrap: wrap;
	justify-content: flex-start;
	position: relative;
}

.list-view .cwp-post-thumbnail {
	width: 30%;
	min-height: 160px;
	height: 185px;
}

.list-view .cwp-post-content-container {
	width: 70%;
}

.list-view .cwp-post-content {
	padding: 30px 20px;
}

.list-view .cwp-post-content h4 {
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	width: 100%;
}

.list-view ul.cwp-post-terms {
	padding: 20px 20px 0 20px;
}

.cwp-promoted-post {
	border: 1px solid #ddbb2a;
	border-radius: 4px;
	color: #ddbb2a;
	cursor: default;
	display: inline-block;
	font-size: 10px;
	line-height: 1;
	padding: 2px 5px;
	position: relative;
	top: -2px;
}

.cwp-post-content {
	padding: 15px;
}

.cwp-post-content h4 {
	font-size: 20px;
	font-weight: bold;
	line-height: 1.3;
	margin: 0 0 5px 0;
	text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap;
}

.cwp-post-content p {
	font-size: 14px;
	line-height: 1.3;
	margin: 0 0 0 0;
}

.cwp-post-terms {
	align-items: center;
	border-top: 1px solid #e0e0e0;
	display: flex;
	flex-wrap: wrap;
	justify-content: flex-start;
	line-height: 1.5;
	list-style: none;
	margin: 0;
	padding: 15px 15px 10px;
}

.cwp-post-terms li {
	margin: 0 5px 5px 0;
}

.cwp-post-terms li a {
	display: block;
	font-size: 12px;
	background: #f6f6f6;
	max-width: 100px;
	overflow: hidden;
	text-overflow: ellipsis;
	white-space: nowrap;
	padding: 8px 8px;
	font-weight: 500;
	text-transform: capitalize;
	border-radius: 210px;
	line-height: 10px;
	color: #343A40;
}

/*---Save button-------*/
.cwp-archive-save {
	position: absolute;
	background: rgba(0, 0, 0, 0) linear-gradient(0deg, rgba(0, 0, 0, .9) 8%, rgba(0, 0, 0, 0) 94%) repeat scroll 0 0;
	bottom: 0;
	padding: 15px 14px 5px 14px;
	width: 100%;
}

.cwp-archive-save .cwp-single-save-btns.cwp-single-widget {
	float: right;
	color: #fff;
}

span.cwp-main.cwp-save-post svg:nth-child(2) {
	display: none;
}

span.cwp-main.cwp-saved-post svg:first-child {
	display: none;
}

.cwp-single-save-btns.cwp-single-widget span.cwp-main,
.cwp-single-share-btn.cwp-single-widget span.cwp-main {
	cursor: pointer;
}
.cwp-post-boosted {
	padding: 1px 10px;
	position: absolute;
	top: 15px;
	left: 15px;
	background: #FFBB00;
	border-radius: 12px;
	color: #000000;
	font-weight: 500;
	font-size: 13px;
}',
    ),
    'shoesfootwear_Product_Style_1_' => 
    array (
      'loop-layout-html' => '<div [loop_woo_post_class]>
    <div class="woo-style-14-card">
        <div class="woo-style-14-thumbnail position-relative  overflow-hidden">
            <div class="woo-style-14-gallery">
                <div class="woo-overlay-move">
                    <a href="[loop_post_link]">
                       <img src="[loop_featured_image]" alt="featured-image">
                       [loop_gallery_overlay_image]
                    </a>
                </div>
            </div>
            <div class="woo-style-14-tags">
                <span class="woo-style-14-bag women-wc-quick-checkout" data-productID="[loop_woo_product_id]" data-tooltip="Add To Cart" data-flow="left">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                    <path d="M11.8996 15H4.09961C2.44961 15 1.09961 13.65 1.09961 12V11.9L1.39961 3.9C1.44961 2.25 2.79961 1 4.39961 1H11.5996C13.1996 1 14.5496 2.25 14.5996 3.9L14.8996 11.9C14.9496 12.7 14.6496 13.45 14.0996 14.05C13.5496 14.65 12.7996 15 11.9996 15C11.9996 15 11.9496 15 11.8996 15ZM4.39961 2C3.29961 2 2.44961 2.85 2.39961 3.9L2.09961 12C2.09961 13.1 2.99961 14 4.09961 14H11.9996C12.5496 14 13.0496 13.75 13.3996 13.35C13.7496 12.95 13.9496 12.45 13.9496 11.9L13.6496 3.9C13.5996 2.8 12.7496 2 11.6496 2H4.39961Z" fill="#1D1D1D"/>
                    <path d="M8 7C6.05 7 4.5 5.45 4.5 3.5C4.5 3.2 4.7 3 5 3C5.3 3 5.5 3.2 5.5 3.5C5.5 4.9 6.6 6 8 6C9.4 6 10.5 4.9 10.5 3.5C10.5 3.2 10.7 3 11 3C11.3 3 11.5 3.2 11.5 3.5C11.5 5.45 9.95 7 8 7Z" fill="#1D1D1D"/>
                    </svg>
                </span>
                <div class="woo-style-14-save save-tooltip" data-tooltip="Add To Wishlist" data-flow="left">
                    [loop_post_save]
                </div>
                    <span class="woo-style-14-eye women-wc-quick-checkout" data-productID="[loop_woo_product_id]" data-tooltip="View" data-flow="left">
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17" fill="none">
                            <g clip-path="url(#clip0_1421_180)">
                            <path d="M16.3983 8.18882C16.2554 7.99329 12.8496 3.40112 8.49992 3.40112C4.15019 3.40112 0.744313 7.99329 0.601531 8.18863C0.466156 8.37413 0.466156 8.62573 0.601531 8.81123C0.744313 9.00676 4.15019 13.5989 8.49992 13.5989C12.8496 13.5989 16.2554 9.00673 16.3983 8.81138C16.5339 8.62592 16.5339 8.37413 16.3983 8.18882ZM8.49992 12.544C5.29588 12.544 2.52085 9.49607 1.69938 8.49966C2.51979 7.50238 5.28901 4.45606 8.49992 4.45606C11.7038 4.45606 14.4787 7.50344 15.3005 8.50038C14.4801 9.49763 11.7108 12.544 8.49992 12.544Z" fill="#1D1D1D"/>
                            <path d="M8.49993 5.33521C6.75487 5.33521 5.33508 6.75499 5.33508 8.50005C5.33508 10.2451 6.75487 11.6649 8.49993 11.6649C10.245 11.6649 11.6648 10.2451 11.6648 8.50005C11.6648 6.75499 10.245 5.33521 8.49993 5.33521ZM8.49993 10.6099C7.33649 10.6099 6.39005 9.66346 6.39005 8.50005C6.39005 7.33665 7.33652 6.39018 8.49993 6.39018C9.66334 6.39018 10.6098 7.33665 10.6098 8.50005C10.6098 9.66346 9.66337 10.6099 8.49993 10.6099Z" fill="#1D1D1D"/>
                            </g>
                            <defs>
                            <clipPath id="clip0_1421_180">
                            <rect width="16" height="16" fill="white" transform="translate(0.5 0.5)"/>
                            </clipPath>
                            </defs>
                        </svg>
                    </span>
            </div>
        </div>
        <div class="woo-style-14-content woocommerce">
            <a href="[loop_product_cat_tax_link]">
                <span class="woo-style-14-cat">[loop_product_cat]</span>
            </a>
             <a href="[loop_post_link]">
                <span class="woo-style-14-title">[loop_the_title]</span>
            </a>
            <p class="woo-style-14-price">[loop_woo_normal_price]</p>
            <div class="woo-style-14-stars">
                [loop_woo_rating]
            </div>
        </div>
    </div>
</div>',
      'loop-layout-css' => '.woo-style-14-card {
    display: flex;
    flex-direction: column;
    gap: 20px;
    background: #ffffff;
    transition: .3s ease;
    padding: 20px;
}

.woo-style-14-card:hover {
    box-shadow: 0px 15px 24px 0px #0000000D;
}

.woo-style-14-gallery {
    height: 100%;
    display: flex !important;
    align-items: center;
    justify-content: center;
}

.woo-style-14-gallery img {
    height: auto !important;
    object-fit: contain;
    transition: .3s ease;
    width: 100% !important;
    background-color: #F6F6F6;
}

.woo-style-14-tags {
    position: absolute;
    top: 20px;
    right: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
    transition: .3s ease;
    transform: translateY(-200px);
}

.woo-style-14-card:hover .woo-style-14-tags{
    transform: translateY(0px);
}

.woo-style-14-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px !important;
}

.woo-style-14-cat {
    font-family: Roboto;
    font-size: 14px;
    font-weight: 500;
    line-height: 16px;
    color: #1D1D1D;
    transition: .3s ease;
}

.woo-style-14-cat:hover {
    color: #000000;
}

.woo-style-14-content a {
    text-align: center;
}

.woo-style-14-title {
    text-transform: capitalize;
    color: #1D1D1D;
    font-family: Roboto;
    font-size: 14px;
    font-weight: 400;
    line-height: 16px;
    transition: .3s ease;
}


.woo-style-14-title:hover {
    color: #000000;
    text-decoration: underline;
}

.woo-style-14-thumbnail{
    position: relative;
    overflow: hidden;
}

.women-wc-quick-checkout{
    cursor: pointer;
}

.woo-style-14-price {
    display: flex;
    justify-content: center;
    font-family: Roboto;
    font-size: 14px;
    font-weight: 400;
    line-height: 16px;
    color: #1D1D1D;
    margin: 0px;
}

.woo-style-14-stars .star-rating {
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 8px;
}

.woo-style-14-stars .star-rating:before {
    content: "sssss";
    float: left;
    position: absolute;
    color: #3B3738;
    letter-spacing: 3px;
    font-family: WooCommerce;
    line-height: 1;
    font-size: 10px;
}

.woo-style-14-stars .star-rating span {
    display: none;
}

.woo-style-14-gallery.woomen-shop-this-look-slider-4 i {
    position: absolute;
    top: 50%;
    width: 30px;
    height: 30px;
    display: flex;
    cursor: pointer;
    align-items: center;
    transition: 0.3s;
    z-index: 999999;
    justify-content: center;
    border-radius: 100%;
    font-size: 12px;
    transition: .3s ease;
    background: #fff;
    color: #1D1D1D;
}

.woo-style-14-gallery.woomen-shop-this-look-slider-4 i:hover {
    background: #1D1D1D;
    color: #ffffff;
}

.woo-style-14-gallery.woomen-shop-this-look-slider-4 .fa-chevron-left {
    left: -30px;
}

.woo-style-14-gallery.woomen-shop-this-look-slider-4:hover .fa-chevron-left {
    left: 10px;
}

.woo-style-14-gallery.woomen-shop-this-look-slider-4 .fa-chevron-right {
    right: -30px;
}

.woo-style-14-gallery.woomen-shop-this-look-slider-4:hover .fa-chevron-right {
    right: 10px;
}

.woo-style-14-card .woo-style-14-tags .cwp-main,
.woo-style-14-eye,
.woo-style-14-bag {
    width: 35px;
    height: 35px;
    background: #FFFFFF !important;
    color: #1D1D1D;
    border-radius: 100% !important;
    transition: .3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.woo-style-14-card .woo-style-14-tags .cwp-main:hover,
.woo-style-14-eye:hover,
.woo-style-14-bag:hover{
    background: #1D1D1D !important;
    color: #fff;
}

.woo-style-14-card .woo-style-14-tags .cwp-main:hover path,
.woo-style-14-eye:hover path,
.woo-style-14-bag:hover path {
    fill: #fff !important;
}




.woo-style-14-save.save-tooltip {
    position: relative !important;
}

.woo-style-14-stars .star-rating span::before {
    font-size: 10px;
    letter-spacing: 3px;
}
.woo-gallery-overlay-image {
    position: absolute;
    top: 0px;
    width: 100%;
    object-fit: contain;
    opacity: 0;
    visibility: hidden;
    height: 100% !important;
    background: rgb(246, 246, 246);
    transition: 0.3s;
}
.cwp-single-save-btns.cwp-single-widget span.cwp-main, 
.cwp-single-share-btn.cwp-single-widget span.cwp-main {
    color: rgb(0, 0, 0) !important;
    border: unset !important;
}
.cwp-single-save-btns.cwp-single-widget span.cwp-main svg, 
.cwp-single-share-btn.cwp-single-widget span.cwp-main svg{
    width:13px;
}
.cwp-single-share-btn.cwp-single-widget, .cwp-single-save-btns.cwp-single-widget .woo-ajax-loader {
    width: 100%;
    justify-content: center;
}
 [data-tooltip][data-flow^="left"]::before {
    border-left-color: #1d1d1d !important;
    color: #FFFFFF !important;
}

@media (max-width: 767px) {
    .woo-style-14-card {
        padding: 10px 0px;
    }
}



',
    ),
    '_shoesfootwear_Product_Style_2' => 
    array (
      'loop-layout-html' => '<div class="woo-sliders">
     <div class="woo-style-26-card">
        <div class="woo-style-26-thumbnail woo-overlay-move">
            <a href="[loop_post_link]">
                <img src="[loop_featured_image]" alt="featured-image">
                [loop_gallery_overlay_image]
            </a>
        </div>
        <div class="woo-style-26-content">
            <a href="[loop_post_link]">
                <span class="woo-style-26-title">[loop_the_title]</span>
            </a>
            <p class="woo-style-26-price">[loop_woo_normal_price]</p>
        </div>
        <div class="woo-style-26-shop-now">
            <div class="women-wc-quick-checkout save-tooltip" data-tooltip="Add To Cart"  data-flow="left" data-productID="[loop_woo_product_id]">
                <svg data-flow="left" width="35" height="36" viewBox="0 0 35 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="0.5" y="0.72998" width="0" height="0" rx="17" stroke="#1D1D1D" />
                    <path
                        d="M21.4522 24.8287H13.5423C11.869 24.8287 10.5 23.4597 10.5 21.7864V21.685L10.8042 13.5722C10.8549 11.899 12.224 10.6313 13.8465 10.6313H21.148C22.7706 10.6313 24.1396 11.899 24.1903 13.5722L24.4945 21.685C24.5452 22.4963 24.241 23.2568 23.6833 23.8653C23.1255 24.4738 22.3649 24.8287 21.5536 24.8287C21.5536 24.8287 21.5029 24.8287 21.4522 24.8287ZM13.8465 11.6454C12.731 11.6454 11.869 12.5074 11.8183 13.5722L11.5141 21.7864C11.5141 22.9019 12.4268 23.8146 13.5423 23.8146H21.5536C22.1114 23.8146 22.6185 23.5611 22.9734 23.1554C23.3283 22.7498 23.5311 22.2428 23.5311 21.685L23.2269 13.5722C23.1762 12.4567 22.3142 11.6454 21.1987 11.6454H13.8465Z"
                        fill="#1D1D1D" />
                    <path
                        d="M17.4966 16.7161C15.5191 16.7161 13.9473 15.1442 13.9473 13.1667C13.9473 12.8625 14.1501 12.6597 14.4543 12.6597C14.7585 12.6597 14.9614 12.8625 14.9614 13.1667C14.9614 14.5865 16.0769 15.702 17.4966 15.702C18.9163 15.702 20.0318 14.5865 20.0318 13.1667C20.0318 12.8625 20.2347 12.6597 20.5389 12.6597C20.8431 12.6597 21.0459 12.8625 21.0459 13.1667C21.0459 15.1442 19.4741 16.7161 17.4966 16.7161Z"
                        fill="#1D1D1D" />
                </svg>
            </div>
        </div>
    </div>
</div>',
      'loop-layout-css' => '.woo-style-26-card {
    display: flex !important;
    flex-direction: row;
    gap: 20px;
    padding-left: 0;
    position: relative;
}

.woo-style-26-thumbnail {
    background: #F7F7F7;
    overflow: hidden;
    width: 80px;
    align-items: center;
    display: flex;
}
.woo-style-26-thumbnail img {
    object-fit: contain;
    height: auto !important;
    width: 100%;
    transition: .3s;
}
.woo-style-26-card:hover .woo-style-26-thumbnail img {
    transform: scale(1.1);
}
.woo-style-26-content {
    display: flex;
    flex-direction: column;
    gap: 2px;
    width: calc(100% - 155px);
    padding: 15px 0;
    justify-content: center;
}
.woo-style-26-title {
    color: #1d1d1d;
    font-size: 13px;
    font-weight: 500;
    line-height: 24px;
    letter-spacing: 0.02em;
    display: block;
    transition: .3s;
    text-overflow: ellipsis;
    white-space: nowrap;
    width: 100%;
    overflow: hidden;
}

.woo-style-26-title:hover {
    color: #141414;
    text-decoration: underline;
}

.woo-style-26-price {
    color: #1d1d1d;
    font-size: 13px;
    font-weight: 500;
    line-height: 24px;
    letter-spacing: 0.02em;
    margin: 0;
}
.woo-overlay-move {
    position: relative;
    height: 100%;
}
.woo-gallery-overlay-image {
    position: absolute;
    top: 0;
    height: 100% !important;
    width: 100%;
    object-fit: contain;
    background: #f6f6f6;
    transition: .3s;
    opacity: 0;
    visibility: hidden;
}
.woo-style-26-card:hover .woo-gallery-overlay-image {
    opacity: 1;
    visibility: visible;
}
.woo-style-26-shop-now {
    display: flex;
    align-items: center;
    justify-content: center;
    min-width: 35px;
}
.woo-style-26-shop-now svg {
    border: 1px solid #1D1D1D;
    border-radius: 50%;
}
 
.woo-style-26-shop-now .women-wc-quick-checkout {
    border-radius: 50%;
}',
    ),
  ),
  'post' => 
  array (
    'default_style' => 
    array (
      'loop-layout-html' => '<div class="card" style="width: 18rem;">
							<img src="..." class="card-img-top" alt="...">
							<div class="card-body">
							<h5 class="card-title"> [loop_the_title] </h5>
							<p class="card-text"> [loop_the_content] </p>
							<a href="#" class="btn btn-primary">Go somewhere</a>
							</div>
						</div>',
      'loop-layout-css' => '/* Custom Card Styles */
					.card {
						width: 18rem;
						border: 1px solid #ddd;
						border-radius: 0.25rem;
						overflow: hidden;
						box-shadow: 0 2px 4px rgba(0,0,0,0.1);
						transition: all 0.3s ease;
					}
					
					.card:hover {
						box-shadow: 0 4px 8px rgba(0,0,0,0.2);
					}
					
					.card-img-top {
						width: 100%;
						height: auto;
						border-bottom: 1px solid #ddd;
					}
					
					.card-body {
						padding: 1rem;
					}
					
					.card-title {
						font-size: 1.25rem;
						font-weight: 500;
						margin-bottom: 0.75rem;
					}
					
					.card-text {
						font-size: 1rem;
						margin-bottom: 1rem;
						color: #555;
					}
					
					.btn-primary {
						background-color: #007bff;
						border: none;
						padding: 0.5rem 1rem;
						font-size: 1rem;
						color: #fff;
						text-align: center;
						cursor: pointer;
						border-radius: 0.25rem;
						transition: background-color 0.3s ease;
					}
					
					.btn-primary:hover {
						background-color: #0056b3;
					}
				 ',
    ),
    'style' => 
    array (
      'loop-layout-html' => '                <div class="col-md-4 mb-3">
                    <div class="woo-card-sec" style=" background-image: url([loop_post_loop_image])">
                        <div class="woo-bars">
                            <div class="first-bar">
                                <div class="post-date">
                                     <h2>[loop_the_date]</h2>
                                </div>
                                <div class="first-bar-button">
                                    <button>Clothing</button>
                                </div>
                            </div>
                            <div class="sec-bar">
                                <div class="tittle-sec-bar">
                                     <a href="[loop_post_link]">
                                        <h2>[loop_the_title]</h2>
                                     </a>
                                </div>
                                <div class="sub-tittle-sec-bar">
                                    <p>By [loop_author_name]</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>',
      'loop-layout-css' => '.woo-card {
    padding: 80px 0;
}

.woo-card-sec {
     height: 700px;
    width:100% !important;
    background-repeat: no-repeat;
    background-size: cover;
    border-radius: 16px;

}

.first-bar {
    display: flex;
    justify-content: space-between;
    padding: 30px 60px;
    align-items: center;
}



.post-date h2 {
    font-size: 14px !important;
    font-weight: 500 !important;
    color: #fff !important;
    text-transform: capitalize !important;
    font-family: \'Plus Jakarta Sans\', sans-serif;

}

.tittle-sec-bar h2 {
    font-weight: 600 !important;
    font-size: 24px !important;
    font-family: \'Plus Jakarta Sans\', sans-serif;
    color: #fff !important;
    line-height: 30px !important;
    text-transform: capitalize !important;
    margin: 0px;
}

.sub-tittle-sec-bar {
    margin-top: 40px !important;
}

.sub-tittle-sec-bar p {
    font-size: 14px;
    font-weight: 500;
    color: #ffff;
    font-family: \'Plus Jakarta Sans\';
    line-height: 28px;
    margin: 0px;
}

.sec-bar {
    padding: 30px 60px 22px 60px;
}
    @media(max-width:768px){
    .woo-card-sec {
        height: auto; 

    }  .sec-bar, 
        .first-bar { 
        padding: 30px 30px; 
    }
     .card-12 .woo-trending-produc-color {
        font-size: 7px !important;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        margin: 13px 9px -16px 0px;
    }
   
}

.woo-bars {
    display: flex;
    flex-direction: column;
    height: 100%;
    justify-content: space-between;
}

.first-bar-button button {
    border: 1px solid white !important;
    padding: 8px 15px 8px 15px !important;
    border-radius: 12px !important;
    background-color: #ffffff63 !important;
    color: white !important;
    text-transform: capitalize !important;
    font-size: 13px !important;
    line-height: 16px !important;
    font-weight: 600;
}

.woo-card-sec {
    position: relative;
    transition: .1s;
}

.woo-card-sec:hover:after {
    opacity: 50%;
}

.woo-card-sec:after {
    content: \'\';
    background: #000;
    width: 100%;
    height: 100%;
    position: absolute;
    top: 0;
    left: 0;
    border-radius: 16px;
    opacity: 20%;
    transition: .1s;
}

.first-bar, .sec-bar {
    z-index: 99;
}

.sub-tittle-sec-bar {
    margin-top: 25px !important;
}

.first-bar-button button:hover {
    background: #1d1d1d !important;
}',
    ),
    'style_2' => 
    array (
      'loop-layout-html' => '<div class="col-lg-4 col-md-4 col-sm-6 col-12">
    <div class="woo-blog-style-2">
        <div class="woo-blog-style-2-tuhmb">
            <a href="[loop_post_link]"><img src="[loop_post_loop_image]"></a>
        </div>
        <div class="woo-blog-style-2-content">
            <a href="[loop_category_tax_link]" class="woo-blog-style-2-cat"> [loop_category]</a>
            <h2 class="woo-blog-style-2-title"><a href="[loop_post_link]">[loop_the_title]</a></h2>
        </div>
    </div>
</div>',
      'loop-layout-css' => '.woo-blog-style-2 {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.woo-blog-style-2-tuhmb {
    overflow: hidden;
}

.woo-blog-style-2-tuhmb img {
    height: auto !important;
    width: 100%;
    object-fit: cover;
    transition: 1.3s;
}

.woo-blog-style-2-tuhmb:hover img {
    transform: scale(1.1);
}

.woo-blog-style-2-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.woo-blog-style-2-cat {
    font-family: Plus Jakarta Sans;
    font-size: 12px;
    font-weight: 400;
    line-height: 15px;
    letter-spacing: 0.02em;
    color: #767676;
    text-transform: uppercase;
    transition: .3s;
    width: fit-content;
}

.woo-blog-style-2-cat:hover {
    color: #0078f9;
}

.woo-blog-style-2-title {
    font-family: Plus Jakarta Sans;
    font-size: 16px;
    font-weight: 600;
    line-height: 28px;
    color: #1D1D1D;
    width: fit-content;
    text-transform: uppercase;
    margin-top: 2px;
}

.woo-blog-style-2-title a {
    color: #1D1D1D;
    transition: .3s;
}

 

.woo-blog-style-2-title a:hover {
    color: #1D1D1D;
}',
    ),
    'style_3' => 
    array (
      'loop-layout-html' => '<div class="cwp-col-12 cwp-5col-per-row">
    <div class="woo-blog-style-3">
        <div class="woo-blog-style-3-tuhmb">
            <a href="[loop_post_link]"><img src="[loop_post_loop_image]"></a>
        </div>
        <div class="woo-blog-style-3-content">
            <h2 class="woo-blog-style-3-title"><a href="[loop_post_link]">[loop_the_title]</a></h2>
        </div>
    </div>
</div>',
      'loop-layout-css' => '  .blog-section .blog-image img{
    width: 100%;
    height:auto;
    object-fit: cover;
}
.blog-section .heading{
    margin-top: 30px;
}
.blog-section .heading a {
    text-decoration: none;
    color: black;
}
.blog-section .heading h2{
    margin: 0px;
    font-family: Marcellus;
font-size: 26px;
font-weight: 400;
line-height: 36px;
text-align: left;
color: black;
transition:0.3s;
}
.blog-section .heading h2:hover {
    color: #1F6BCD;
}
.blog-section .post-date{
    margin-top: 30px;
}
.blog-section .post-date p{
    margin: 0px;
    font-family: DM Sans;
font-size: 13px;
font-weight: 400;
line-height: 16.93px;
letter-spacing: 0.02em;
text-align: left;
color: #7B7369;
}
 .blog-section .heading {
    margin-top: 24px !important;
}
.blog-section .post-date {
    margin-top: 15px !important;
} 

.woo-blog-style-3 {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.woo-blog-style-3-tuhmb {
    overflow: hidden;
}

.woo-blog-style-3-tuhmb img {
   height: auto !important;
    width: 100%;
    object-fit: cover;
    transition: .6s;
}

.woo-blog-style-3-tuhmb:hover img {
    transform: scale(1.1);
}

.woo-blog-style-3-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.woo-blog-style-3-title {
    font-family: Plus Jakarta Sans;
    font-size: 14px;
    font-weight: 600;
    line-height: 28px;
    color: #1D1D1D;
    margin: 0px;
    white-space: nowrap;
    text-overflow: ellipsis;
    width: 100%;
    overflow: hidden;
}

.woo-blog-style-3-title a {
    color: #1D1D1D;
    transition: .3s;
    position: relative;
}

.woo-blog-style-3-title a:hover {
    color: #0078f9;
}
  .woo-blog-style-3-title a:after {
    content:"";
    background: #1d1d1d;
    height: 2px;
    width: 0%;
    display: block;
    transition: .6s all ease;
    left: 0;
    bottom: -3px;
    position: absolute;
}

.woo-blog-style-3-title a:hover:after {
    width: 100%;
}  
',
    ),
    'style_4' => 
    array (
      'loop-layout-html' => '<div class="col-md-4">
    <div class="blog-section">
         <div class="blog-image">
                <a href="[loop_post_link]">
                   <img src="[loop_post_loop_image]" alt="blog-imag">
                </a>
         </div>
         <div class="heading">
                <a href="[loop_post_link]">
                  <h2>[loop_the_title]</h2> 
                </a>
         </div>
         <div class="post-date">
            <p>[loop_the_date]</p>
         </div>
    </div>
  </div>',
      'loop-layout-css' => '.woo-blog-style-3 {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.woo-blog-style-3-tuhmb {
    overflow: hidden;
}

.woo-blog-style-3-tuhmb img {
   height: auto !important;
    width: 100%;
    object-fit: cover;
    transition: .6s;
}

.woo-blog-style-3-tuhmb:hover img {
    transform: scale(1.1);
}

.woo-blog-style-3-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
}  
 ',
    ),
    'style_5' => 
    array (
      'loop-layout-html' => '<div class="col-lg-4 col-md-4 col-sm-6 col-12">
    <div class="woo-blog-style-5">
        <div class="woo-blog-style-5-tuhmb">
            <a href="[loop_post_link]"><img src="[loop_post_loop_image]"></a>
        </div>
        <div class="woo-blog-style-5-content">
            <a href="[loop_category_tax_link]" class="woo-blog-style-2-cat"> [loop_category]</a>
            <h2 class="woo-blog-style-2-title"><a href="[loop_post_link]">[loop_the_title]</a></h2>
        </div>
    </div>
</div> ',
      'loop-layout-css' => '.woo-blog-style-5 {
    display: flex;
    flex-direction: column;
    gap: 26px;
}
.woo-blog-style-5-tuhmb {
    overflow: hidden;
      border-radius: 8px;
}
.woo-blog-style-5-tuhmb img {
    height: auto  !important;
    width: 100%;
    object-fit: cover;
    transition: .6s;
         border-radius: 8px;
}
.woo-blog-style-5-tuhmb:hover img {
    transform: scale(1.1);
}
.woo-blog-style-5-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
}
.woo-blog-style-5-cat {
    font-family: Plus Jakarta Sans;
    font-size: 12px;
    font-weight: 400;
    line-height: 15px;
    letter-spacing: 0.02em;
    color: #767676;
    text-transform: uppercase;
    transition: .3s;
    width: fit-content;
}
.woo-blog-style-5-cat:hover {
    color: #0078F9;
}
.woo-blog-style-5-title {
      font-family: \'Jost\';
    font-size: 18px;
    font-weight: 400;
    line-height: 28px;
    color: #1D1D1D;
    width: fit-content;
    text-transform: uppercase;
    margin-top: 2px;
}
.woo-blog-style-5-title a {
    color: #1D1D1D;
    transition: .3s;
}
.woo-blog-style-5-title a:hover {
    color: #0078F9;
}',
    ),
    'style_6' => 
    array (
      'loop-layout-html' => '<div class="cwp-col-12 cwp-5col-per-row">
    <div class="woo-blog-style-3">
        <div class="woo-blog-style-3-tuhmb">
            <a href="[loop_post_link]"><img src="loop_post_loop_image"></a>
        </div>[]
        <div class="woo-blog-style-3-content">
            <h2 class="woo-blog-style-3-title"><a href="[loop_post_link]">[loop_the_title]</a></h2>
        </div>
    </div>
</div>',
      'loop-layout-css' => '.woo-blog-style-3 {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.woo-blog-style-3-tuhmb {
    overflow: hidden;
}

.woo-blog-style-3-tuhmb img {
   height: auto !important;
    width: 100%;
    object-fit: cover;
    transition: .6s;
}

.woo-blog-style-3-tuhmb:hover img {
    transform: scale(1.1);
}

.woo-blog-style-3-content {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

 ',
    ),
  ),
  'store-locator' => 
  array (
    'default_style' => 
    array (
      'loop-layout-html' => '<div class="woocomerce-location-details">
    <div class="woocomerce-heading-icon">
        <h1>[loop_the_title]</h1>
    </div>
    <div class="woocomerce-location-content">
        <div class="woocomerce-address">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M12.7852 1.98207C11.5071 0.703908 9.80768 0 8.00011 0C6.19255 0 4.4931 0.703908 3.21498 1.98207C1.93682 3.26026 1.23291 4.95963 1.23291 6.76717C1.23291 10.4238 4.69035 13.4652 6.54783 15.0992C6.80595 15.3262 7.02886 15.5223 7.20645 15.6882C7.42895 15.896 7.71455 16 8.00008 16C8.28567 16 8.57121 15.896 8.79374 15.6882C8.97133 15.5223 9.19424 15.3262 9.45236 15.0992C11.3098 13.4652 14.7673 10.4238 14.7673 6.76717C14.7673 4.95963 14.0634 3.26026 12.7852 1.98207ZM8.8333 14.3955C8.56952 14.6275 8.34174 14.8279 8.15392 15.0033C8.06764 15.0838 7.93252 15.0839 7.8462 15.0033C7.65842 14.8278 7.43061 14.6275 7.16683 14.3954C5.42057 12.8593 2.1701 9.99999 2.1701 6.7672C2.1701 3.55257 4.78539 0.937283 8.00005 0.937283C11.2147 0.937283 13.83 3.55257 13.83 6.7672C13.83 9.99999 10.5796 12.8593 8.8333 14.3955Z" fill="#1D1D1D"/>
            <path d="M8.00029 3.5293C6.35588 3.5293 5.01807 4.86708 5.01807 6.51149C5.01807 8.1559 6.35588 9.49368 8.00029 9.49368C9.6447 9.49368 10.9825 8.1559 10.9825 6.51149C10.9825 4.86708 9.6447 3.5293 8.00029 3.5293ZM8.00029 8.5564C6.8727 8.5564 5.95532 7.63902 5.95532 6.51146C5.95532 5.38389 6.8727 4.46652 8.00029 4.46652C9.12789 4.46652 10.0452 5.38389 10.0452 6.51146C10.0452 7.63902 9.12789 8.5564 8.00029 8.5564Z" fill="#1D1D1D"/>
            </svg>
            <p>[loop_store-locator-address]</p>
        </div>
        <div class="woocomerce-address">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="margin-top: 4px;" viewBox="0 0 16 16" fill="none">
            <g clip-path="url(#clip0_431_12378)">
            <path d="M15.0175 11.297C14.991 11.275 11.9965 9.132 11.184 9.2725C10.7935 9.3415 10.57 9.6075 10.1225 10.141C10.0505 10.227 9.877 10.4325 9.743 10.579C9.46016 10.4869 9.18429 10.3746 8.9175 10.243C7.54026 9.5725 6.4275 8.45974 5.757 7.0825C5.62543 6.81571 5.51315 6.53984 5.421 6.257C5.568 6.1225 5.774 5.949 5.862 5.875C6.3925 5.43 6.6585 5.2065 6.7275 4.8155C6.869 4.006 4.725 1.009 4.703 0.982C4.60536 0.843528 4.4782 0.728462 4.33069 0.645108C4.18317 0.561755 4.01899 0.512193 3.85 0.5C2.981 0.5 0.5 3.718 0.5 4.2605C0.5 4.292 0.5455 7.494 4.494 11.5105C8.506 15.4545 11.708 15.5 11.7395 15.5C12.282 15.5 15.5 13.019 15.5 12.15C15.4878 11.9809 15.4382 11.8167 15.3548 11.6692C15.2713 11.5217 15.1561 11.3945 15.0175 11.297ZM11.6845 14.497C11.2475 14.461 8.5605 14.1065 5.201 10.806C1.8835 7.4285 1.538 4.734 1.5035 4.3165C2.15889 3.28782 2.9504 2.35251 3.8565 1.536C3.8765 1.556 3.903 1.586 3.937 1.625C4.63191 2.57361 5.23054 3.58913 5.724 4.6565C5.56353 4.81794 5.39392 4.97002 5.216 5.112C4.9401 5.32223 4.68674 5.5605 4.46 5.823L4.3385 5.993L4.3745 6.1985C4.48031 6.65686 4.64238 7.10039 4.857 7.519C5.62594 9.09801 6.90188 10.3738 8.481 11.1425C8.89952 11.3574 9.34307 11.5197 9.8015 11.6255L10.007 11.6615L10.177 11.54C10.4405 11.3123 10.6798 11.0579 10.891 10.781C11.0475 10.594 11.257 10.3445 11.336 10.274C12.4064 10.767 13.4245 11.3663 14.375 12.063C14.4165 12.098 14.4455 12.125 14.465 12.1425C13.6486 13.0489 12.7133 13.8406 11.6845 14.496V14.497Z" fill="#1D1D1D"/>
            </g>
            <defs>
            <clipPath id="clip0_431_12378">
            <rect width="16" height="16" fill="white"/>
            </clipPath>
            </defs>
            </svg>
            <p>[loop_store-locator-phone]</p>
        </div>
        <div class="woocomerce-address">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M8 1C6.61553 1 5.26216 1.41054 4.11101 2.17971C2.95987 2.94888 2.06266 4.04213 1.53285 5.32122C1.00303 6.6003 0.86441 8.00776 1.13451 9.36563C1.4046 10.7235 2.07129 11.9708 3.05026 12.9497C4.02922 13.9287 5.2765 14.5954 6.63437 14.8655C7.99224 15.1356 9.3997 14.997 10.6788 14.4672C11.9579 13.9373 13.0511 13.0401 13.8203 11.889C14.5895 10.7378 15 9.38447 15 8C15 6.14348 14.2625 4.36301 12.9497 3.05025C11.637 1.7375 9.85652 1 8 1ZM8 14C6.81332 14 5.65328 13.6481 4.66658 12.9888C3.67989 12.3295 2.91085 11.3925 2.45673 10.2961C2.0026 9.19974 1.88378 7.99334 2.11529 6.82946C2.3468 5.66557 2.91825 4.59647 3.75736 3.75736C4.59648 2.91824 5.66558 2.3468 6.82946 2.11529C7.99335 1.88378 9.19975 2.0026 10.2961 2.45672C11.3925 2.91085 12.3295 3.67988 12.9888 4.66658C13.6481 5.65327 14 6.81331 14 8C14 9.5913 13.3679 11.1174 12.2426 12.2426C11.1174 13.3679 9.5913 14 8 14ZM10.355 9.645C10.4019 9.69148 10.4391 9.74678 10.4644 9.80771C10.4898 9.86864 10.5029 9.93399 10.5029 10C10.5029 10.066 10.4898 10.1314 10.4644 10.1923C10.4391 10.2532 10.4019 10.3085 10.355 10.355C10.3085 10.4019 10.2532 10.4391 10.1923 10.4644C10.1314 10.4898 10.066 10.5029 10 10.5029C9.934 10.5029 9.86864 10.4898 9.80771 10.4644C9.74679 10.4391 9.69148 10.4019 9.645 10.355L7.94 8.645C7.65862 8.36397 7.50035 7.98269 7.5 7.585V3.5C7.5 3.36739 7.55268 3.24021 7.64645 3.14645C7.74022 3.05268 7.86739 3 8 3C8.13261 3 8.25979 3.05268 8.35356 3.14645C8.44733 3.24021 8.5 3.36739 8.5 3.5V7.585C8.49962 7.6508 8.51224 7.71603 8.53712 7.77695C8.562 7.83787 8.59866 7.89328 8.645 7.94L10.355 9.645Z" fill="#1D1D1D"/>
            </svg>
            <p>[loop_store-locator-hours]</p>
        </div>
    </div>
    <div class="woocomerce-see-details">
        <button class="location-details-btn">SEE DETAILS
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                <path d="M10.6725 0.691772H2.57657C2.46054 0.691772 2.34926 0.737866 2.26721 0.819913C2.18516 0.90196 2.13907 1.01324 2.13907 1.12927C2.13907 1.2453 2.18516 1.35658 2.26721 1.43863C2.34926 1.52068 2.46054 1.56677 2.57657 1.56677H9.81457L0.837508 10.5443C0.794113 10.5842 0.759241 10.6325 0.734988 10.6863C0.710736 10.74 0.697605 10.7982 0.696383 10.8571C0.695161 10.9161 0.705875 10.9747 0.727879 11.0294C0.749884 11.0841 0.782726 11.1338 0.82443 11.1755C0.866134 11.2172 0.91584 11.2501 0.970561 11.2721C1.02528 11.2941 1.08389 11.3048 1.14285 11.3036C1.20182 11.3024 1.25993 11.2892 1.31369 11.265C1.36745 11.2407 1.41575 11.2059 1.4557 11.1625L10.4332 2.18496V9.4234C10.4332 9.53943 10.4793 9.65071 10.5613 9.73276C10.6434 9.8148 10.7547 9.8609 10.8707 9.8609C10.9867 9.8609 11.098 9.8148 11.1801 9.73276C11.2621 9.65071 11.3082 9.53943 11.3082 9.4234V1.3279C11.308 1.15933 11.2409 0.997733 11.1218 0.878499C11.0026 0.759265 10.8411 0.69212 10.6725 0.691772Z" fill="#1D1D1D"/>
            </svg>
        </button>
    </div>
    <div class="woocomerce-location-details-sidebar">
        <div class="heading">
            <span class="woocomerce-location-back-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M1.32749 0.691772H9.42343C9.53946 0.691772 9.65074 0.737866 9.73279 0.819913C9.81484 0.90196 9.86093 1.01324 9.86093 1.12927C9.86093 1.2453 9.81484 1.35658 9.73279 1.43863C9.65074 1.52068 9.53946 1.56677 9.42343 1.56677H2.18543L11.1625 10.5443C11.2059 10.5842 11.2408 10.6325 11.265 10.6863C11.2893 10.74 11.3024 10.7982 11.3036 10.8571C11.3048 10.9161 11.2941 10.9747 11.2721 11.0294C11.2501 11.0841 11.2173 11.1338 11.1756 11.1755C11.1339 11.2172 11.0842 11.2501 11.0294 11.2721C10.9747 11.2941 10.9161 11.3048 10.8571 11.3036C10.7982 11.3024 10.7401 11.2892 10.6863 11.265C10.6325 11.2407 10.5842 11.2059 10.5443 11.1625L1.5668 2.18496V9.4234C1.5668 9.53943 1.52071 9.65071 1.43866 9.73276C1.35662 9.8148 1.24534 9.8609 1.1293 9.8609C1.01327 9.8609 0.901992 9.8148 0.819944 9.73276C0.737898 9.65071 0.691804 9.53943 0.691804 9.4234V1.3279C0.692036 1.15933 0.759069 0.997733 0.878222 0.878499C0.997373 0.759265 1.15893 0.69212 1.32749 0.691772Z" fill="#1D1D1D"/>
                </svg>
            </span>
            <h2>STORE DETAILS</h2>
        </div>
        <div class="woocomerce-location-content">
            <h2 class="content-heading">[loop_the_title] STORE</h2>
            <div class="woocomerce-address">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M12.7852 1.98207C11.5071 0.703908 9.80768 0 8.00011 0C6.19255 0 4.4931 0.703908 3.21498 1.98207C1.93682 3.26026 1.23291 4.95963 1.23291 6.76717C1.23291 10.4238 4.69035 13.4652 6.54783 15.0992C6.80595 15.3262 7.02886 15.5223 7.20645 15.6882C7.42895 15.896 7.71455 16 8.00008 16C8.28567 16 8.57121 15.896 8.79374 15.6882C8.97133 15.5223 9.19424 15.3262 9.45236 15.0992C11.3098 13.4652 14.7673 10.4238 14.7673 6.76717C14.7673 4.95963 14.0634 3.26026 12.7852 1.98207ZM8.8333 14.3955C8.56952 14.6275 8.34174 14.8279 8.15392 15.0033C8.06764 15.0838 7.93252 15.0839 7.8462 15.0033C7.65842 14.8278 7.43061 14.6275 7.16683 14.3954C5.42057 12.8593 2.1701 9.99999 2.1701 6.7672C2.1701 3.55257 4.78539 0.937283 8.00005 0.937283C11.2147 0.937283 13.83 3.55257 13.83 6.7672C13.83 9.99999 10.5796 12.8593 8.8333 14.3955Z" fill="#1D1D1D"/>
                <path d="M8.00029 3.5293C6.35588 3.5293 5.01807 4.86708 5.01807 6.51149C5.01807 8.1559 6.35588 9.49368 8.00029 9.49368C9.6447 9.49368 10.9825 8.1559 10.9825 6.51149C10.9825 4.86708 9.6447 3.5293 8.00029 3.5293ZM8.00029 8.5564C6.8727 8.5564 5.95532 7.63902 5.95532 6.51146C5.95532 5.38389 6.8727 4.46652 8.00029 4.46652C9.12789 4.46652 10.0452 5.38389 10.0452 6.51146C10.0452 7.63902 9.12789 8.5564 8.00029 8.5564Z" fill="#1D1D1D"/>
                </svg>
                <p>[loop_store-locator-address]</p>
            </div>
            <div class="woocomerce-address">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="margin-top: 4px;" viewBox="0 0 16 16" fill="none">
                <g clip-path="url(#clip0_431_12378)">
                <path d="M15.0175 11.297C14.991 11.275 11.9965 9.132 11.184 9.2725C10.7935 9.3415 10.57 9.6075 10.1225 10.141C10.0505 10.227 9.877 10.4325 9.743 10.579C9.46016 10.4869 9.18429 10.3746 8.9175 10.243C7.54026 9.5725 6.4275 8.45974 5.757 7.0825C5.62543 6.81571 5.51315 6.53984 5.421 6.257C5.568 6.1225 5.774 5.949 5.862 5.875C6.3925 5.43 6.6585 5.2065 6.7275 4.8155C6.869 4.006 4.725 1.009 4.703 0.982C4.60536 0.843528 4.4782 0.728462 4.33069 0.645108C4.18317 0.561755 4.01899 0.512193 3.85 0.5C2.981 0.5 0.5 3.718 0.5 4.2605C0.5 4.292 0.5455 7.494 4.494 11.5105C8.506 15.4545 11.708 15.5 11.7395 15.5C12.282 15.5 15.5 13.019 15.5 12.15C15.4878 11.9809 15.4382 11.8167 15.3548 11.6692C15.2713 11.5217 15.1561 11.3945 15.0175 11.297ZM11.6845 14.497C11.2475 14.461 8.5605 14.1065 5.201 10.806C1.8835 7.4285 1.538 4.734 1.5035 4.3165C2.15889 3.28782 2.9504 2.35251 3.8565 1.536C3.8765 1.556 3.903 1.586 3.937 1.625C4.63191 2.57361 5.23054 3.58913 5.724 4.6565C5.56353 4.81794 5.39392 4.97002 5.216 5.112C4.9401 5.32223 4.68674 5.5605 4.46 5.823L4.3385 5.993L4.3745 6.1985C4.48031 6.65686 4.64238 7.10039 4.857 7.519C5.62594 9.09801 6.90188 10.3738 8.481 11.1425C8.89952 11.3574 9.34307 11.5197 9.8015 11.6255L10.007 11.6615L10.177 11.54C10.4405 11.3123 10.6798 11.0579 10.891 10.781C11.0475 10.594 11.257 10.3445 11.336 10.274C12.4064 10.767 13.4245 11.3663 14.375 12.063C14.4165 12.098 14.4455 12.125 14.465 12.1425C13.6486 13.0489 12.7133 13.8406 11.6845 14.496V14.497Z" fill="#1D1D1D"/>
                </g>
                <defs>
                <clipPath id="clip0_431_12378">
                <rect width="16" height="16" fill="white"/>
                </clipPath>
                </defs>
                </svg>
                <p>[loop_store-locator-phone]</p>
            </div>
            <div class="woocomerce-address">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M8 1C6.61553 1 5.26216 1.41054 4.11101 2.17971C2.95987 2.94888 2.06266 4.04213 1.53285 5.32122C1.00303 6.6003 0.86441 8.00776 1.13451 9.36563C1.4046 10.7235 2.07129 11.9708 3.05026 12.9497C4.02922 13.9287 5.2765 14.5954 6.63437 14.8655C7.99224 15.1356 9.3997 14.997 10.6788 14.4672C11.9579 13.9373 13.0511 13.0401 13.8203 11.889C14.5895 10.7378 15 9.38447 15 8C15 6.14348 14.2625 4.36301 12.9497 3.05025C11.637 1.7375 9.85652 1 8 1ZM8 14C6.81332 14 5.65328 13.6481 4.66658 12.9888C3.67989 12.3295 2.91085 11.3925 2.45673 10.2961C2.0026 9.19974 1.88378 7.99334 2.11529 6.82946C2.3468 5.66557 2.91825 4.59647 3.75736 3.75736C4.59648 2.91824 5.66558 2.3468 6.82946 2.11529C7.99335 1.88378 9.19975 2.0026 10.2961 2.45672C11.3925 2.91085 12.3295 3.67988 12.9888 4.66658C13.6481 5.65327 14 6.81331 14 8C14 9.5913 13.3679 11.1174 12.2426 12.2426C11.1174 13.3679 9.5913 14 8 14ZM10.355 9.645C10.4019 9.69148 10.4391 9.74678 10.4644 9.80771C10.4898 9.86864 10.5029 9.93399 10.5029 10C10.5029 10.066 10.4898 10.1314 10.4644 10.1923C10.4391 10.2532 10.4019 10.3085 10.355 10.355C10.3085 10.4019 10.2532 10.4391 10.1923 10.4644C10.1314 10.4898 10.066 10.5029 10 10.5029C9.934 10.5029 9.86864 10.4898 9.80771 10.4644C9.74679 10.4391 9.69148 10.4019 9.645 10.355L7.94 8.645C7.65862 8.36397 7.50035 7.98269 7.5 7.585V3.5C7.5 3.36739 7.55268 3.24021 7.64645 3.14645C7.74022 3.05268 7.86739 3 8 3C8.13261 3 8.25979 3.05268 8.35356 3.14645C8.44733 3.24021 8.5 3.36739 8.5 3.5V7.585C8.49962 7.6508 8.51224 7.71603 8.53712 7.77695C8.562 7.83787 8.59866 7.89328 8.645 7.94L10.355 9.645Z" fill="#1D1D1D"/>
                </svg>
                <p>[loop_store-locator-hours]</p>
            </div>
            <a href="[loop_store_locator_get_direction]" class="woocomerce-location-get-direction" target="_blank">
                GET DIRECTION
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M10.6725 0.691772H2.57657C2.46054 0.691772 2.34926 0.737866 2.26721 0.819913C2.18516 0.90196 2.13907 1.01324 2.13907 1.12927C2.13907 1.2453 2.18516 1.35658 2.26721 1.43863C2.34926 1.52068 2.46054 1.56677 2.57657 1.56677H9.81457L0.837508 10.5443C0.794113 10.5842 0.759241 10.6325 0.734988 10.6863C0.710736 10.74 0.697605 10.7982 0.696383 10.8571C0.695161 10.9161 0.705875 10.9747 0.727879 11.0294C0.749884 11.0841 0.782726 11.1338 0.82443 11.1755C0.866134 11.2172 0.91584 11.2501 0.970561 11.2721C1.02528 11.2941 1.08389 11.3048 1.14285 11.3036C1.20182 11.3024 1.25993 11.2892 1.31369 11.265C1.36745 11.2407 1.41575 11.2059 1.4557 11.1625L10.4332 2.18496V9.4234C10.4332 9.53943 10.4793 9.65071 10.5613 9.73276C10.6434 9.8148 10.7547 9.8609 10.8707 9.8609C10.9867 9.8609 11.098 9.8148 11.1801 9.73276C11.2621 9.65071 11.3082 9.53943 11.3082 9.4234V1.3279C11.308 1.15933 11.2409 0.997733 11.1218 0.878499C11.0026 0.759265 10.8411 0.69212 10.6725 0.691772Z" fill="white"/>
                </svg>
            </a>
        </div>
        <div class="opening-hours">
            [loop_store_locator_opening_hours]
        </div>
        <div class="social-share">
            [loop_store_locator_social_shares]
        </div>
    </div>
</div>',
      'loop-layout-css' => '/* Custom Card Styles */
.woocomerce-location-details {
    width: 100%;
    background-color: white;
    padding: 19px 30px 29px 29px !important;
    margin-bottom: 30px;
}

.woocomerce-location-details .woocomerce-heading-icon {
    padding-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.woocomerce-location-details .woocomerce-heading-icon svg {
    margin-top: 1px;
}

.woocomerce-address p,
.woocomerce-contact p,
.woocomerce-timming p {
    color: #1d1d1d;
    font-family: "Plus Jakarta Sans";
    font-size: 13px;
    font-weight: 500;
    line-height: 24px;
    margin: 0;
    width: calc(100% - 26px);
}

.woocomerce-address svg {
    width: 16px;
    height: 16px;
    margin-top: 7px;
}

.woocomerce-contact svg {
    width: 17px;
    height: 16px;
    margin-top: 4px;
}

.woocomerce-timming svg {
    width: 16px;
    height: 20px;
    margin-top: 4px;
}

.woocomerce-location-content {
    display: flex;
    flex-direction: column;
    gap: 19px;
    margin-bottom: 20px;
}

.woocomerce-address {
    display: flex;
    gap: 10px;
}

.woocomerce-contact {
    display: flex;
    gap: 10px;
    padding-bottom: 19px;
}

.woocomerce-timming {
    display: flex;
    gap: 10px;
}

.woocomerce-see-details button {
    font-family: "Plus Jakarta Sans";
    font-weight: 600;
    font-size: 13px;
    line-height: 16px;
    letter-spacing: 0.26px;
    background: transparent;
    border: none;
    padding: 7px 0px 3px 0px;
    color: #1d1d1d;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #1D1D1D;
}

.woocomerce-see-details button svg path {
    fill: #1d1d1d;
}

.woomen-find-store{
    position: relative;
}

.woocomerce-location-details-sidebar {
    position: absolute;
    background: #fff;
    top: 0;
    right: -1000px;
    width: 100%;
    height: 100%;
    padding: 0 40px;
    z-index: 999;
    display: flex;
    flex-direction: column;
    transition: .3s;
}

.woocomerce-location-details-sidebar.active {
    right: 0px;
}

.woocomerce-location-details-sidebar .heading {
    padding: 22px 0 23px 0;
    border-bottom: 1px solid #E6E6E6;
    display: flex;
    align-items: center;
    position: relative;
    margin: 0 0 20px 0;
    gap: 10px;
}

.woocomerce-location-details-sidebar .heading h2 {
    font-family: "Plus Jakarta Sans";
    font-weight: 600;
    font-size: 14px;
    line-height: 28px;
    text-align: center;
    color: #1D1D1D;
    width: calc(100% - 22px);
}

.woocomerce-location-back-btn {
    cursor: pointer;
}

.woocomerce-location-back-btn svg path {
    fill: #1D1D1D;
}

.store-locator-cards-animate {
    position: unset !important;
}

.woocomerce-location-details-sidebar .content-heading {
    font-family: "Plus Jakarta Sans";
    font-weight: 600;
    font-size: 14px;
    line-height: 28px;
    color: #1D1D1D;
    margin: 0 0 -4px 0;
    text-transform: uppercase;
}

.woocomerce-location-get-direction {
    background: #1d1d1d;
    border: 1px solid #1d1d1d;
    color: #fff;
    padding: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    font-family: "Plus Jakarta Sans";
    font-weight: 600;
    font-size: 13px;
    line-height: 16px;
    letter-spacing: 0.26px;
    transition: .3s;
    margin: 4px 0 0 0;
}

.woocomerce-location-get-direction svg path{
    transition: .3s;
    fill: #fff;
}

.woocomerce-location-get-direction:hover{
    background: #fff;
    color: #1d1d1d;
}

.woocomerce-location-get-direction:hover svg path{
    fill: #1d1d1d;
}

.woocomerce-location-opening-hours ul {
    padding: 0px;
    margin: 19px 0px 19px 0px;
}

.woocomerce-location-opening-hours ul li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.woocomerce-location-opening-hours p {
    font-family: "Plus Jakarta Sans";
    font-weight: 500;
    font-size: 13px;
    line-height: 24px;
    color: #1D1D1D;
}

.woocomerce-location-details-sidebar .social-share > div {
    padding: 0px !important;
    justify-content: flex-start !important;
    gap: 20px;
}

.woocomerce-location-details-sidebar .social-share > div a {
    margin: 0px !important;
    font-size: 16px;
}

@media (max-width:1024px){

    .woocomerce-location-details-sidebar {
        padding: 0px;
    }

}',
    ),
    'Shoesfootwear_Store_Style_1' => 
    array (
      'loop-layout-html' => '

<div class="woocomerce-location-details">
    <div class="woocomerce-heading-icon">
        <h1>[loop_the_title]</h1>
    </div>
    <div class="woocomerce-location-content">
        <div class="woocomerce-address">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M12.7852 1.98207C11.5071 0.703908 9.80768 0 8.00011 0C6.19255 0 4.4931 0.703908 3.21498 1.98207C1.93682 3.26026 1.23291 4.95963 1.23291 6.76717C1.23291 10.4238 4.69035 13.4652 6.54783 15.0992C6.80595 15.3262 7.02886 15.5223 7.20645 15.6882C7.42895 15.896 7.71455 16 8.00008 16C8.28567 16 8.57121 15.896 8.79374 15.6882C8.97133 15.5223 9.19424 15.3262 9.45236 15.0992C11.3098 13.4652 14.7673 10.4238 14.7673 6.76717C14.7673 4.95963 14.0634 3.26026 12.7852 1.98207ZM8.8333 14.3955C8.56952 14.6275 8.34174 14.8279 8.15392 15.0033C8.06764 15.0838 7.93252 15.0839 7.8462 15.0033C7.65842 14.8278 7.43061 14.6275 7.16683 14.3954C5.42057 12.8593 2.1701 9.99999 2.1701 6.7672C2.1701 3.55257 4.78539 0.937283 8.00005 0.937283C11.2147 0.937283 13.83 3.55257 13.83 6.7672C13.83 9.99999 10.5796 12.8593 8.8333 14.3955Z" fill="#1D1D1D"/>
            <path d="M8.00029 3.5293C6.35588 3.5293 5.01807 4.86708 5.01807 6.51149C5.01807 8.1559 6.35588 9.49368 8.00029 9.49368C9.6447 9.49368 10.9825 8.1559 10.9825 6.51149C10.9825 4.86708 9.6447 3.5293 8.00029 3.5293ZM8.00029 8.5564C6.8727 8.5564 5.95532 7.63902 5.95532 6.51146C5.95532 5.38389 6.8727 4.46652 8.00029 4.46652C9.12789 4.46652 10.0452 5.38389 10.0452 6.51146C10.0452 7.63902 9.12789 8.5564 8.00029 8.5564Z" fill="#1D1D1D"/>
            </svg>
            <p>[loop_store-locator-address]</p>
        </div>
        <div class="woocomerce-address">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="margin-top: 4px;" viewBox="0 0 16 16" fill="none">
            <g clip-path="url(#clip0_431_12378)">
            <path d="M15.0175 11.297C14.991 11.275 11.9965 9.132 11.184 9.2725C10.7935 9.3415 10.57 9.6075 10.1225 10.141C10.0505 10.227 9.877 10.4325 9.743 10.579C9.46016 10.4869 9.18429 10.3746 8.9175 10.243C7.54026 9.5725 6.4275 8.45974 5.757 7.0825C5.62543 6.81571 5.51315 6.53984 5.421 6.257C5.568 6.1225 5.774 5.949 5.862 5.875C6.3925 5.43 6.6585 5.2065 6.7275 4.8155C6.869 4.006 4.725 1.009 4.703 0.982C4.60536 0.843528 4.4782 0.728462 4.33069 0.645108C4.18317 0.561755 4.01899 0.512193 3.85 0.5C2.981 0.5 0.5 3.718 0.5 4.2605C0.5 4.292 0.5455 7.494 4.494 11.5105C8.506 15.4545 11.708 15.5 11.7395 15.5C12.282 15.5 15.5 13.019 15.5 12.15C15.4878 11.9809 15.4382 11.8167 15.3548 11.6692C15.2713 11.5217 15.1561 11.3945 15.0175 11.297ZM11.6845 14.497C11.2475 14.461 8.5605 14.1065 5.201 10.806C1.8835 7.4285 1.538 4.734 1.5035 4.3165C2.15889 3.28782 2.9504 2.35251 3.8565 1.536C3.8765 1.556 3.903 1.586 3.937 1.625C4.63191 2.57361 5.23054 3.58913 5.724 4.6565C5.56353 4.81794 5.39392 4.97002 5.216 5.112C4.9401 5.32223 4.68674 5.5605 4.46 5.823L4.3385 5.993L4.3745 6.1985C4.48031 6.65686 4.64238 7.10039 4.857 7.519C5.62594 9.09801 6.90188 10.3738 8.481 11.1425C8.89952 11.3574 9.34307 11.5197 9.8015 11.6255L10.007 11.6615L10.177 11.54C10.4405 11.3123 10.6798 11.0579 10.891 10.781C11.0475 10.594 11.257 10.3445 11.336 10.274C12.4064 10.767 13.4245 11.3663 14.375 12.063C14.4165 12.098 14.4455 12.125 14.465 12.1425C13.6486 13.0489 12.7133 13.8406 11.6845 14.496V14.497Z" fill="#1D1D1D"/>
            </g>
            <defs>
            <clipPath id="clip0_431_12378">
            <rect width="16" height="16" fill="white"/>
            </clipPath>
            </defs>
            </svg>
            <p>[loop_store-locator-phone]</p>
        </div>
        <div class="woocomerce-address">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
            <path d="M8 1C6.61553 1 5.26216 1.41054 4.11101 2.17971C2.95987 2.94888 2.06266 4.04213 1.53285 5.32122C1.00303 6.6003 0.86441 8.00776 1.13451 9.36563C1.4046 10.7235 2.07129 11.9708 3.05026 12.9497C4.02922 13.9287 5.2765 14.5954 6.63437 14.8655C7.99224 15.1356 9.3997 14.997 10.6788 14.4672C11.9579 13.9373 13.0511 13.0401 13.8203 11.889C14.5895 10.7378 15 9.38447 15 8C15 6.14348 14.2625 4.36301 12.9497 3.05025C11.637 1.7375 9.85652 1 8 1ZM8 14C6.81332 14 5.65328 13.6481 4.66658 12.9888C3.67989 12.3295 2.91085 11.3925 2.45673 10.2961C2.0026 9.19974 1.88378 7.99334 2.11529 6.82946C2.3468 5.66557 2.91825 4.59647 3.75736 3.75736C4.59648 2.91824 5.66558 2.3468 6.82946 2.11529C7.99335 1.88378 9.19975 2.0026 10.2961 2.45672C11.3925 2.91085 12.3295 3.67988 12.9888 4.66658C13.6481 5.65327 14 6.81331 14 8C14 9.5913 13.3679 11.1174 12.2426 12.2426C11.1174 13.3679 9.5913 14 8 14ZM10.355 9.645C10.4019 9.69148 10.4391 9.74678 10.4644 9.80771C10.4898 9.86864 10.5029 9.93399 10.5029 10C10.5029 10.066 10.4898 10.1314 10.4644 10.1923C10.4391 10.2532 10.4019 10.3085 10.355 10.355C10.3085 10.4019 10.2532 10.4391 10.1923 10.4644C10.1314 10.4898 10.066 10.5029 10 10.5029C9.934 10.5029 9.86864 10.4898 9.80771 10.4644C9.74679 10.4391 9.69148 10.4019 9.645 10.355L7.94 8.645C7.65862 8.36397 7.50035 7.98269 7.5 7.585V3.5C7.5 3.36739 7.55268 3.24021 7.64645 3.14645C7.74022 3.05268 7.86739 3 8 3C8.13261 3 8.25979 3.05268 8.35356 3.14645C8.44733 3.24021 8.5 3.36739 8.5 3.5V7.585C8.49962 7.6508 8.51224 7.71603 8.53712 7.77695C8.562 7.83787 8.59866 7.89328 8.645 7.94L10.355 9.645Z" fill="#1D1D1D"/>
            </svg>
            <p>[loop_store-locator-hours]</p>
        </div>
    </div>
    <div class="woocomerce-see-details">
        <button class="location-details-btn">SEE DETAILS
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                <path d="M10.6725 0.691772H2.57657C2.46054 0.691772 2.34926 0.737866 2.26721 0.819913C2.18516 0.90196 2.13907 1.01324 2.13907 1.12927C2.13907 1.2453 2.18516 1.35658 2.26721 1.43863C2.34926 1.52068 2.46054 1.56677 2.57657 1.56677H9.81457L0.837508 10.5443C0.794113 10.5842 0.759241 10.6325 0.734988 10.6863C0.710736 10.74 0.697605 10.7982 0.696383 10.8571C0.695161 10.9161 0.705875 10.9747 0.727879 11.0294C0.749884 11.0841 0.782726 11.1338 0.82443 11.1755C0.866134 11.2172 0.91584 11.2501 0.970561 11.2721C1.02528 11.2941 1.08389 11.3048 1.14285 11.3036C1.20182 11.3024 1.25993 11.2892 1.31369 11.265C1.36745 11.2407 1.41575 11.2059 1.4557 11.1625L10.4332 2.18496V9.4234C10.4332 9.53943 10.4793 9.65071 10.5613 9.73276C10.6434 9.8148 10.7547 9.8609 10.8707 9.8609C10.9867 9.8609 11.098 9.8148 11.1801 9.73276C11.2621 9.65071 11.3082 9.53943 11.3082 9.4234V1.3279C11.308 1.15933 11.2409 0.997733 11.1218 0.878499C11.0026 0.759265 10.8411 0.69212 10.6725 0.691772Z" fill="#1D1D1D"/>
            </svg>
        </button>
    </div>
    <div class="woocomerce-location-details-sidebar">
        <div class="heading">
            <span class="woocomerce-location-back-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M1.32749 0.691772H9.42343C9.53946 0.691772 9.65074 0.737866 9.73279 0.819913C9.81484 0.90196 9.86093 1.01324 9.86093 1.12927C9.86093 1.2453 9.81484 1.35658 9.73279 1.43863C9.65074 1.52068 9.53946 1.56677 9.42343 1.56677H2.18543L11.1625 10.5443C11.2059 10.5842 11.2408 10.6325 11.265 10.6863C11.2893 10.74 11.3024 10.7982 11.3036 10.8571C11.3048 10.9161 11.2941 10.9747 11.2721 11.0294C11.2501 11.0841 11.2173 11.1338 11.1756 11.1755C11.1339 11.2172 11.0842 11.2501 11.0294 11.2721C10.9747 11.2941 10.9161 11.3048 10.8571 11.3036C10.7982 11.3024 10.7401 11.2892 10.6863 11.265C10.6325 11.2407 10.5842 11.2059 10.5443 11.1625L1.5668 2.18496V9.4234C1.5668 9.53943 1.52071 9.65071 1.43866 9.73276C1.35662 9.8148 1.24534 9.8609 1.1293 9.8609C1.01327 9.8609 0.901992 9.8148 0.819944 9.73276C0.737898 9.65071 0.691804 9.53943 0.691804 9.4234V1.3279C0.692036 1.15933 0.759069 0.997733 0.878222 0.878499C0.997373 0.759265 1.15893 0.69212 1.32749 0.691772Z" fill="#1D1D1D"/>
                </svg>
            </span>
            <h2>STORE DETAILS</h2>
        </div>
        <div class="woocomerce-location-content">
            <h2 class="content-heading">[loop_the_title] STORE</h2>
            <div class="woocomerce-address">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M12.7852 1.98207C11.5071 0.703908 9.80768 0 8.00011 0C6.19255 0 4.4931 0.703908 3.21498 1.98207C1.93682 3.26026 1.23291 4.95963 1.23291 6.76717C1.23291 10.4238 4.69035 13.4652 6.54783 15.0992C6.80595 15.3262 7.02886 15.5223 7.20645 15.6882C7.42895 15.896 7.71455 16 8.00008 16C8.28567 16 8.57121 15.896 8.79374 15.6882C8.97133 15.5223 9.19424 15.3262 9.45236 15.0992C11.3098 13.4652 14.7673 10.4238 14.7673 6.76717C14.7673 4.95963 14.0634 3.26026 12.7852 1.98207ZM8.8333 14.3955C8.56952 14.6275 8.34174 14.8279 8.15392 15.0033C8.06764 15.0838 7.93252 15.0839 7.8462 15.0033C7.65842 14.8278 7.43061 14.6275 7.16683 14.3954C5.42057 12.8593 2.1701 9.99999 2.1701 6.7672C2.1701 3.55257 4.78539 0.937283 8.00005 0.937283C11.2147 0.937283 13.83 3.55257 13.83 6.7672C13.83 9.99999 10.5796 12.8593 8.8333 14.3955Z" fill="#1D1D1D"/>
                <path d="M8.00029 3.5293C6.35588 3.5293 5.01807 4.86708 5.01807 6.51149C5.01807 8.1559 6.35588 9.49368 8.00029 9.49368C9.6447 9.49368 10.9825 8.1559 10.9825 6.51149C10.9825 4.86708 9.6447 3.5293 8.00029 3.5293ZM8.00029 8.5564C6.8727 8.5564 5.95532 7.63902 5.95532 6.51146C5.95532 5.38389 6.8727 4.46652 8.00029 4.46652C9.12789 4.46652 10.0452 5.38389 10.0452 6.51146C10.0452 7.63902 9.12789 8.5564 8.00029 8.5564Z" fill="#1D1D1D"/>
                </svg>
                <p>[loop_store-locator-address]</p>
            </div>
            <div class="woocomerce-address">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" style="margin-top: 4px;" viewBox="0 0 16 16" fill="none">
                <g clip-path="url(#clip0_431_12378)">
                <path d="M15.0175 11.297C14.991 11.275 11.9965 9.132 11.184 9.2725C10.7935 9.3415 10.57 9.6075 10.1225 10.141C10.0505 10.227 9.877 10.4325 9.743 10.579C9.46016 10.4869 9.18429 10.3746 8.9175 10.243C7.54026 9.5725 6.4275 8.45974 5.757 7.0825C5.62543 6.81571 5.51315 6.53984 5.421 6.257C5.568 6.1225 5.774 5.949 5.862 5.875C6.3925 5.43 6.6585 5.2065 6.7275 4.8155C6.869 4.006 4.725 1.009 4.703 0.982C4.60536 0.843528 4.4782 0.728462 4.33069 0.645108C4.18317 0.561755 4.01899 0.512193 3.85 0.5C2.981 0.5 0.5 3.718 0.5 4.2605C0.5 4.292 0.5455 7.494 4.494 11.5105C8.506 15.4545 11.708 15.5 11.7395 15.5C12.282 15.5 15.5 13.019 15.5 12.15C15.4878 11.9809 15.4382 11.8167 15.3548 11.6692C15.2713 11.5217 15.1561 11.3945 15.0175 11.297ZM11.6845 14.497C11.2475 14.461 8.5605 14.1065 5.201 10.806C1.8835 7.4285 1.538 4.734 1.5035 4.3165C2.15889 3.28782 2.9504 2.35251 3.8565 1.536C3.8765 1.556 3.903 1.586 3.937 1.625C4.63191 2.57361 5.23054 3.58913 5.724 4.6565C5.56353 4.81794 5.39392 4.97002 5.216 5.112C4.9401 5.32223 4.68674 5.5605 4.46 5.823L4.3385 5.993L4.3745 6.1985C4.48031 6.65686 4.64238 7.10039 4.857 7.519C5.62594 9.09801 6.90188 10.3738 8.481 11.1425C8.89952 11.3574 9.34307 11.5197 9.8015 11.6255L10.007 11.6615L10.177 11.54C10.4405 11.3123 10.6798 11.0579 10.891 10.781C11.0475 10.594 11.257 10.3445 11.336 10.274C12.4064 10.767 13.4245 11.3663 14.375 12.063C14.4165 12.098 14.4455 12.125 14.465 12.1425C13.6486 13.0489 12.7133 13.8406 11.6845 14.496V14.497Z" fill="#1D1D1D"/>
                </g>
                <defs>
                <clipPath id="clip0_431_12378">
                <rect width="16" height="16" fill="white"/>
                </clipPath>
                </defs>
                </svg>
                <p>[loop_store-locator-phone]</p>
            </div>
            <div class="woocomerce-address">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                <path d="M8 1C6.61553 1 5.26216 1.41054 4.11101 2.17971C2.95987 2.94888 2.06266 4.04213 1.53285 5.32122C1.00303 6.6003 0.86441 8.00776 1.13451 9.36563C1.4046 10.7235 2.07129 11.9708 3.05026 12.9497C4.02922 13.9287 5.2765 14.5954 6.63437 14.8655C7.99224 15.1356 9.3997 14.997 10.6788 14.4672C11.9579 13.9373 13.0511 13.0401 13.8203 11.889C14.5895 10.7378 15 9.38447 15 8C15 6.14348 14.2625 4.36301 12.9497 3.05025C11.637 1.7375 9.85652 1 8 1ZM8 14C6.81332 14 5.65328 13.6481 4.66658 12.9888C3.67989 12.3295 2.91085 11.3925 2.45673 10.2961C2.0026 9.19974 1.88378 7.99334 2.11529 6.82946C2.3468 5.66557 2.91825 4.59647 3.75736 3.75736C4.59648 2.91824 5.66558 2.3468 6.82946 2.11529C7.99335 1.88378 9.19975 2.0026 10.2961 2.45672C11.3925 2.91085 12.3295 3.67988 12.9888 4.66658C13.6481 5.65327 14 6.81331 14 8C14 9.5913 13.3679 11.1174 12.2426 12.2426C11.1174 13.3679 9.5913 14 8 14ZM10.355 9.645C10.4019 9.69148 10.4391 9.74678 10.4644 9.80771C10.4898 9.86864 10.5029 9.93399 10.5029 10C10.5029 10.066 10.4898 10.1314 10.4644 10.1923C10.4391 10.2532 10.4019 10.3085 10.355 10.355C10.3085 10.4019 10.2532 10.4391 10.1923 10.4644C10.1314 10.4898 10.066 10.5029 10 10.5029C9.934 10.5029 9.86864 10.4898 9.80771 10.4644C9.74679 10.4391 9.69148 10.4019 9.645 10.355L7.94 8.645C7.65862 8.36397 7.50035 7.98269 7.5 7.585V3.5C7.5 3.36739 7.55268 3.24021 7.64645 3.14645C7.74022 3.05268 7.86739 3 8 3C8.13261 3 8.25979 3.05268 8.35356 3.14645C8.44733 3.24021 8.5 3.36739 8.5 3.5V7.585C8.49962 7.6508 8.51224 7.71603 8.53712 7.77695C8.562 7.83787 8.59866 7.89328 8.645 7.94L10.355 9.645Z" fill="#1D1D1D"/>
                </svg>
                <p>[loop_store-locator-hours]</p>
            </div>
            <a href="[loop_store_locator_get_direction]" class="woocomerce-location-get-direction" target="_blank">
                GET DIRECTION
                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none">
                    <path d="M10.6725 0.691772H2.57657C2.46054 0.691772 2.34926 0.737866 2.26721 0.819913C2.18516 0.90196 2.13907 1.01324 2.13907 1.12927C2.13907 1.2453 2.18516 1.35658 2.26721 1.43863C2.34926 1.52068 2.46054 1.56677 2.57657 1.56677H9.81457L0.837508 10.5443C0.794113 10.5842 0.759241 10.6325 0.734988 10.6863C0.710736 10.74 0.697605 10.7982 0.696383 10.8571C0.695161 10.9161 0.705875 10.9747 0.727879 11.0294C0.749884 11.0841 0.782726 11.1338 0.82443 11.1755C0.866134 11.2172 0.91584 11.2501 0.970561 11.2721C1.02528 11.2941 1.08389 11.3048 1.14285 11.3036C1.20182 11.3024 1.25993 11.2892 1.31369 11.265C1.36745 11.2407 1.41575 11.2059 1.4557 11.1625L10.4332 2.18496V9.4234C10.4332 9.53943 10.4793 9.65071 10.5613 9.73276C10.6434 9.8148 10.7547 9.8609 10.8707 9.8609C10.9867 9.8609 11.098 9.8148 11.1801 9.73276C11.2621 9.65071 11.3082 9.53943 11.3082 9.4234V1.3279C11.308 1.15933 11.2409 0.997733 11.1218 0.878499C11.0026 0.759265 10.8411 0.69212 10.6725 0.691772Z" fill="white"/>
                </svg>
            </a>
        </div>
        <div class="opening-hours">
            [loop_store_locator_opening_hours]
        </div>
        <div class="social-share">
            [loop_store_locator_social_shares]
        </div>
    </div>
</div>',
      'loop-layout-css' => '

/* Custom Card Styles */
.woocomerce-location-details {
    width: 100%;
    background-color: white;
    padding: 19px 30px 29px 29px !important;
    margin-bottom: 30px;
}

.woocomerce-location-details .woocomerce-heading-icon {
    padding-bottom: 15px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.woocomerce-location-details .woocomerce-heading-icon svg {
    margin-top: 1px;
}

.woocomerce-address p,
.woocomerce-contact p,
.woocomerce-timming p {
    color: #1d1d1d;
    font-family: "Plus Jakarta Sans";
    font-size: 13px;
    font-weight: 500;
    line-height: 24px;
    margin: 0;
    width: calc(100% - 26px);
}

.woocomerce-address svg {
    width: 16px;
    height: 16px;
    margin-top: 7px;
}

.woocomerce-contact svg {
    width: 17px;
    height: 16px;
    margin-top: 4px;
}

.woocomerce-timming svg {
    width: 16px;
    height: 20px;
    margin-top: 4px;
}

.woocomerce-location-content {
    display: flex;
    flex-direction: column;
    gap: 19px;
    margin-bottom: 20px;
}

.woocomerce-address {
    display: flex;
    gap: 10px;
}

.woocomerce-contact {
    display: flex;
    gap: 10px;
    padding-bottom: 19px;
}

.woocomerce-timming {
    display: flex;
    gap: 10px;
}

.woocomerce-see-details button {
    font-family: "Plus Jakarta Sans";
    font-weight: 600;
    font-size: 13px;
    line-height: 16px;
    letter-spacing: 0.26px;
    background: transparent;
    border: none;
    padding: 7px 0px 3px 0px;
    color: #1d1d1d;
    display: flex;
    align-items: center;
    gap: 10px;
    border-bottom: 1px solid #1D1D1D;
}

.woocomerce-see-details button svg path {
    fill: #1d1d1d;
}

.woomen-find-store{
    position: relative;
}

.woocomerce-location-details-sidebar {
    position: absolute;
    background: #fff;
    top: 0;
    right: -1000px;
    width: 100%;
    height: 100%;
    padding: 0 40px;
    z-index: 999;
    display: flex;
    flex-direction: column;
    transition: .3s;
}

.woocomerce-location-details-sidebar.active {
    right: 0px;
}

.woocomerce-location-details-sidebar .heading {
    padding: 22px 0 23px 0;
    border-bottom: 1px solid #E6E6E6;
    display: flex;
    align-items: center;
    position: relative;
    margin: 0 0 20px 0;
    gap: 10px;
}

.woocomerce-location-details-sidebar .heading h2 {
    font-family: "Plus Jakarta Sans";
    font-weight: 600;
    font-size: 14px;
    line-height: 28px;
    text-align: center;
    color: #1D1D1D;
    width: calc(100% - 22px);
}

.woocomerce-location-back-btn {
    cursor: pointer;
}

.woocomerce-location-back-btn svg path {
    fill: #1D1D1D;
}

.store-locator-cards-animate {
    position: unset !important;
}

.woocomerce-location-details-sidebar .content-heading {
    font-family: "Plus Jakarta Sans";
    font-weight: 600;
    font-size: 14px;
    line-height: 28px;
    color: #1D1D1D;
    margin: 0 0 -4px 0;
    text-transform: uppercase;
}

.woocomerce-location-get-direction {
    background: #1d1d1d;
    border: 1px solid #1d1d1d;
    color: #fff;
    padding: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    width: 100%;
    font-family: "Plus Jakarta Sans";
    font-weight: 600;
    font-size: 13px;
    line-height: 16px;
    letter-spacing: 0.26px;
    transition: .3s;
    margin: 4px 0 0 0;
}

.woocomerce-location-get-direction svg path{
    transition: .3s;
    fill: #fff;
}

.woocomerce-location-get-direction:hover{
    background: #fff;
    color: #1d1d1d;
}

.woocomerce-location-get-direction:hover svg path{
    fill: #1d1d1d;
}

.woocomerce-location-opening-hours ul {
    padding: 0px;
    margin: 19px 0px 19px 0px;
}

.woocomerce-location-opening-hours ul li {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
}

.woocomerce-location-opening-hours p {
    font-family: "Plus Jakarta Sans";
    font-weight: 500;
    font-size: 13px;
    line-height: 24px;
    color: #1D1D1D;
}

.woocomerce-location-details-sidebar .social-share > div {
    padding: 0px !important;
    justify-content: flex-start !important;
    gap: 20px;
}

.woocomerce-location-details-sidebar .social-share > div a {
    margin: 0px !important;
    font-size: 16px;
}

@media (max-width:1024px){

    .woocomerce-location-details-sidebar {
        padding: 0px;
    }

}',
    ),
  ),
);
?>