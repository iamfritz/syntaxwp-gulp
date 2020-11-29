<?php
/*
 * Template Name: Home Page
 * 
 */

get_header();

?>

<div id="main" class="main">
        <div class="hero">
          <div class="container">
            <div class="row align-center">
              <div class="col-md-12 col-lg-5">
                <div class="hero-content">
                  <h5 class="d-none d-sm-block">Trending News</h5>
                  <h2>Securely manage your Cloud data</h2>
                  <p>Best in class big data software and analytics services will render your chunks into meaningful data.</p>
                  <div class="form">
                  <form id="chimp-form" class="subscribe-form" action="assets/php/subscribe.php" method="post" accept-charset="UTF-8" enctype="application/x-www-form-urlencoded" autocomplete="off" novalidate>
                    <input class="mail" id="chimp-email" type="email" name="email" placeholder="hello@hubspot.com" autocomplete="off"><input class="submit-button" type="submit" value="Get started">
                  </form>
                  <div id="response"></div>
                </div>
                  <div class="form-note">
                    <p>14-day free trial and no credit card required.</p>
                  </div>
                </div>
              </div>
              <div class="col-md-12 col-lg-6 offset-lg-1">
                <img class="img-fluid" src="<?php echo get_template_directory_uri();?>/assets/images/hero.png" alt="Hero">
              </div>
            </div>
          </div>
        </div>


        <div id="features" class="features">
          <div class="container-m">
            <div class="row text-center">
              <div class="col-md-12">
                <div class="features-intro">
                  <h2>Welcome to BSLaravelMix, where data matters.</h2>
                  <p>We’ll Deliver the best stories and ideas on the topics you care about most straight to your
                  homepage, app, or inbox. </p>
                </div>
              </div>
              <div class="col-sm-6 col-lg-4">
                <div class="feature-list">
                  <div class="card-icon">
                    <div class="card-img">
                      <img src="<?php echo get_template_directory_uri();?>/assets/icons/p1.png" width="60" alt="Feature">
                    </div>
                  </div>
                  <div class="card-text">
                    <h3>Fluid Layout</h3>
                    <p>Deliver the best stories and ideas on the topics you care about most straight to you.</p>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-4">
                <div class="feature-list">
                  <div class="card-icon">
                    <div class="card-img">
                      <img src="<?php echo get_template_directory_uri();?>/assets/icons/p2.png" width="60" alt="Feature">
                    </div>
                  </div>
                  <div class="card-text">
                    <h3>Responsive</h3>
                    <p>Deliver the best stories and ideas on the topics you care about most straight to you.</p>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-4">
                <div class="feature-list">
                  <div class="card-icon">
                    <div class="card-img">
                      <img src="<?php echo get_template_directory_uri();?>/assets/icons/p3.png" width="60" alt="Feature">
                    </div>
                  </div>
                  <div class="card-text">
                    <h3>Hyper threading</h3>
                    <p>Deliver the best stories and ideas on the topics you care about most straight to you.</p>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-4">
                <div class="feature-list">
                  <div class="card-icon">
                    <div class="card-img">
                      <img src="<?php echo get_template_directory_uri();?>/assets/icons/p4.png" width="60" alt="Feature">
                    </div>
                  </div>
                  <div class="card-text">
                    <h3>User Friendly</h3>
                    <p>Deliver the best stories and ideas on the topics you care about most straight to you.</p>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-4">
                <div class="feature-list">
                  <div class="card-icon">
                    <div class="card-img">
                      <img src="<?php echo get_template_directory_uri();?>/assets/icons/p5.png" width="60" alt="Feature">
                    </div>
                  </div>
                  <div class="card-text">
                    <h3> AI Algorithms</h3>
                    <p>Deliver the best stories and ideas on the topics you care about most straight to you.</p>
                  </div>
                </div>
              </div>
              <div class="col-sm-6 col-lg-4">
                <div class="feature-list">
                  <div class="card-icon">
                    <div class="card-img">
                      <img src="<?php echo get_template_directory_uri();?>/assets/icons/p6.png" width="60" alt="Feature">
                    </div>
                  </div>
                  <div class="card-text">
                    <h3>Built-in Camera</h3>
                    <p>Deliver the best stories and ideas on the topics you care about most straight to you.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>


        <div id="services" class="ar-ft-single">
          <div class="container-m">
            <div class="ar-feature">
              <div class="ar-list">
                <ul>
                  <li>
                    <div class="ar-icon">
                      <img src="<?php echo get_template_directory_uri();?>/assets/icons/p1.png" width="40" alt="Icon">
                    </div>
                   <div class="ar-text">
                     <h3>Best Support</h3>
                     <p>Team hangouts and instant text messaging right from the dashboard.</p>
                   </div>
                 </li>
                 <li>
                   <div class="ar-icon">
                     <img src="<?php echo get_template_directory_uri();?>/assets/icons/p4.png" width="40" alt="Icon">
                   </div>
                   <div class="ar-text">
                     <h3>Secure Servers</h3>
                     <p>Team hangouts and instant text messaging right from the dashboard.</p>
                   </div>
                 </li>
                 <li>
                   <div class="ar-icon">
                     <img src="<?php echo get_template_directory_uri();?>/assets/icons/p2.png" width="40" alt="Icon">
                   </div>
                   <div class="ar-text">
                     <h3>Third feature</h3>
                     <p>Team hangouts and instant text messaging right from the dashboard.</p>
                   </div>
                 </li>
               </ul>
             </div>
             <div class="ar-image">
               <img class="ar-img img-fluid" src="<?php echo get_template_directory_uri();?>/assets/images/feature.png" width="420" alt="Hero Image">
            </div>
          </div>
        </div>
      </div>


      <div class="flex-split">
       <div class="f-left">
         <div class="left-content">
           <h2><span>BSLaravelMix</span> Analytics now got the next level</h2>
           <p>Just get the code and sit tight, you'll witness its power and performance in lead conversion.
              Powerful and productive technology.</p>
           <a href="#" class="btn btn-action"><span>Get BSLaravelMix</span></a>
         </div>
       </div>
       <div class="f-right">
         <div class="video-icon hidden-xs text-center">
           <a class="popup" href="https://www.youtube.com/watch?v=T8vP8dcZlb4"><i class="ion-ios-play"></i></a>
         </div>
       </div>
     </div>


   <!-- Counter Section -->
     <div class="yd-stats">
       <div class="container-s">
         <div class="row text-center">
           <div class="col-sm-12">
             <div class="intro">
               <h4>OUR RESULTS</h4>
               <h2>Outstanding annual results from our awesome Team</h2>
             </div>
           </div>
           <div class="col-6 col-sm-3">
             <div class="counter-up">
               <div class="counter-icon">
                 <img src="<?php echo get_template_directory_uri();?>/assets/icons/i5.png" alt="Icon">
               </div>
                <h3><span class="counter">47</span>%</h3>
               <div class="counter-text">
                 <h2>Higher Profits</h2>
               </div>
             </div>
           </div>
           <div class="col-6 col-sm-3">
             <div class="counter-up">
               <div class="counter-icon">
                 <img src="<?php echo get_template_directory_uri();?>/assets/icons/i4.png" alt="Icon">
               </div>
                <h3><span class="counter">33</span>%</h3>
               <div class="counter-text">
                 <h2>Lorem Ipsum</h2>
               </div>
             </div>
           </div>
           <div class="col-6 col-sm-3">
             <div class="counter-up">
               <div class="counter-icon">
                 <img src="<?php echo get_template_directory_uri();?>/assets/icons/i3.png" alt="Icon">
               </div>
                <h3><span class="counter">28</span>%</h3>
               <div class="counter-text">
                 <h2>Random Fact</h2>
               </div>
             </div>
           </div>
           <div class="col-6 col-sm-3">
             <div class="counter-up">
               <div class="counter-icon">
                 <img src="<?php echo get_template_directory_uri();?>/assets/icons/i6.png" alt="Icon">
               </div>
                <h3><span class="counter">349</span>%</h3>
               <div class="counter-text">
                 <h2>Satisfied</h2>
               </div>
             </div>
           </div>
         </div>
       </div>
     </div><!-- Counter Section Ends -->


      <div id="reviews" class="yd-reviews"><!-- BSLaravelMix Reviews -->
        <div class="container-s">
          <div class="row text-center">
            <div class="col-sm-12 col-lg-8 offset-lg-2">
              <div class="intro">
                <h1>Served our best for the awesome clients</h1>
                <p>We have  very fair pricing policy that would benefit you and us at the same time.
                  Get the free plan &amp; if you need more - pay.
                </p>
              </div>
            </div>

            <div class="col-sm-12 col-lg-6 offset-lg-3 text-center">
              <div class="review-cards owl-carousel owl-theme">
                <div class="card-single">
                  <div class="review-text">
                    <h1>"Team hangouts and instant text messaging right from the main dashboard. Deliver the best stories and ideas.
                      Get the free plan & if you need more - pay."
                    </h1>
                  </div>
                  <div class="review-attribution">
                    <div class="review-img">
                      <img class="img-fluid rounded-circle" src="<?php echo get_template_directory_uri();?>/assets/icons/review-1.png" alt="Review">
                    </div>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-ios-star-half"></i>
                    <h2> Albert Rossi</h2>
                    <h6>Stack Developer</h6>
                    <a href="#">Dropboxes Inc</a>
                  </div>
                 </div>
                 <div class="card-single">
                  <div class="review-text">
                    <h1>"We have very fair pricing policy that would benefit you and us at the same time.
                      Choose what price you're willing to pay."
                    </h1>
                  </div>
                  <div class="review-attribution">
                    <div class="review-img">
                      <img class="img-fluid rounded-circle" src="<?php echo get_template_directory_uri();?>/assets/icons/review-2.png" alt="Review">
                    </div>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-ios-star-half"></i>
                    <h2> Melissa Vanbergh</h2>
                    <h6>Vice President</h6>
                    <a href="#">Vestor Corp</a>
                  </div>
                </div>
                <div class="card-single">
                  <div class="review-text">
                    <h1>"Team hangouts and instant text messaging right from the main dashboard. Deliver the best stories and ideas.
                      Get the free plan & if you need more - pay."
                    </h1>
                  </div>
                  <div class="review-attribution">
                    <div class="review-img">
                      <img class="img-fluid rounded-circle" src="<?php echo get_template_directory_uri();?>/assets/icons/review-3.png" alt="Review">
                    </div>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-star"></i>
                    <i class="ion ion-star"></i>
                    <h2> Joshua Peterson</h2>
                    <h6>Product Developer</h6>
                    <a href="#">Betabet Inc</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- BSLaravelMix Reviews Ends -->



      <div id="pricing" class="pricing-section text-center">
        <div class="container">
          <div class="row">
            <div class="col-sm-8 offset-sm-2">
              <div class="pricing-intro">
                <h1>Our Pricing Plans.</h1>
                <p>
                  Our plans are designed to meet the requirements of both beginners <br class="hidden-xs"> and players.
                  Get the right plan that suits you.
                </p>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="table-left">
                    <h3>Limited Freemium</h3>
                    <p>Free limited features</p>
                    <div class="pricing-details">
                     <span>Free</span>
                    </div>
                    <button class="btn btn-primary btn-action" type="button">Download</button>
                  </div>
                </div>

                <div class="col-md-6">
                  <div class="table-right">
                    <h3>Subscription</h3>
                    <p>Unlimited Lifetime</p>

                    <div class="pricing-details">
                     <span>$99.99</span>
                    </div>
                    <button class="btn btn-primary btn-action btn-white" type="button">Subscribe</button>
                  </div>
                </div>
              </div>
              <p class="refund-txt">* Refund requests can be placed with in 7 days of the purchase</p>
            </div>
          </div>
        </div>
      </div>



      <div id="buy" class="cta-sm">
        <div class="container-m text-center">
          <div class="cta-content">
            <h4>WHAT ARE YOU WAITING FOR?</h4>
            <h1>Start now and turn your online business into a profitable route.</h1>
            <a class="btn-action" href="#">Get Started now</a>
          </div>
        </div>
      </div>

<?php

get_footer();