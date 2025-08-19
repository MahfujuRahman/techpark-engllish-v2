 <section class="profational_trainer_area">
     <div class="container">
         <div class="profational_trainer_area_content">
             <div class="row">
                 <!-- left_area start -->
                 <div class="col-xs-12 col-md-6">
                     <div class="left_area">
                         <div class="trainer_img">
                            <img src="{{ asset_or_default(optional($our_trainers)->image) }}" alt="trainer tech park english">
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
                                     {{-- আমাদের প্রফেশনাল ট্রেইনারস --}}
                                     {{ optional($our_trainers)->title }}

                                 </h2>
                             </div>
                             <!-- profational_trainer_area title end -->

                             <!-- profational_trainer_area sub_title start -->
                             <div class="profational_trainer_area_sub_title">
                                 <span class="sub_title sub1">
                                     {{-- আমাদের রয়েছেন প্রফেশনাল ট্রেইনারস, যারা প্রত্যেকেরই রয়েছে স্ব স্ব ক্ষেত্রে বেশ
                                        কয়েকবছর ধরে কোর্স করানোর অভিজ্ঞতা --}}
                                     {{-- কয়েকবছর ধরে কোর্স করানোর অভিজ্ঞতা --}}
                                     {{ optional($our_trainers)->description }}
                                 </span>
                                 {{-- <span class="sub_title">
                                        যাদের হাত ধরে বহু শিক্ষার্থী ফ্রিলানিং ও জব সেক্টরে সফলতার সাথে কাজ করছেন
                                    </span> --}}
                             </div>
                             <!-- profational_trainer_area sub_title end -->

                             <!-- profational_trainer_area_button start-->
                             <div class="profational_trainer_button_area">
                                 <a href="{{ route('trainer.details') }}" class="btn btn-info button_all">
                                     <span class="btn_text">বিস্তারিত দেখুন</span>
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
