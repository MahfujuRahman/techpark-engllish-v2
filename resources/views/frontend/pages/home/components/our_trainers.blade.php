 <section class="profational_trainer_area mb-5">
     <div class="container">
         <div class="profational_trainer_area_content">
             <div class="row">
                 <!-- left_area start -->
                 <div class="col-xs-12 col-md-6">
                     <div class="left_area">
                         <div class="trainer_img">
                             <img src="{{ assetHelper(optional($our_trainers)->image) }}" 
                             alt="trainer tech park english" loading="lazy">
                         </div>
                     </div>
                 </div>
                 <!-- left_area end -->

                 <!-- right_area start -->
                 <div class="col-xs-12 col-md-6">
                     <div class="right_area">
                         <div class="right_area_content">
                             <!-- profational_trainer_area title start -->
                             <div class="profational_trainer_area_title">
                                 <h2 class="area_title">
                                     {{ optional($our_trainers)->title }}
                                 </h2>
                             </div>
                             <!-- profational_trainer_area title end -->

                             <!-- profational_trainer_area sub_title start -->
                             <div class="profational_trainer_area_sub_title">
                                 <span class="sub_title sub1">
                                     {!!optional($our_trainers)->description !!}
                                 </span>
                             </div>
                             <!-- profational_trainer_area sub_title end -->

                             <!-- profational_trainer_area_button start-->
                             <div class="profational_trainer_button_area">
                                 <a href="{{ route('trainer.details') }}" class="btn btn-info button_all">
                                    <span class="btn_text">See Details</span>
                                     <span class="btn_icon"><i class="fa-solid fa-arrow-right"></i></span>
                                 </a>
                             </div>
                             <!-- professional_trainer_area_button end-->
                         </div>
                     </div>
                 </div>
                 <!-- right_area end -->

             </div>

         </div>
     </div>
 </section>
