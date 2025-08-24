 <!-- trainers area starts -->
 <section class="trainers_area">
     <div class="container">
         <div class="trainers_description">
             <div class="trainers_title">
                 <h2 class="trainers_title_bangla">আমাদের প্রফেশনাল ট্রেইনারস</h2>
             </div>
             <div class="trainers_details">
                 @foreach ($teachers as $teacher)
                     <div class="trainer_details">
                         <div class="trainer_images">
                             <div class="image">
                                 <img class="rounded rounded-sm" src="{{ assetHelper(optional($teacher)->image) }}"
                                     alt="{{ $teacher->full_name }}" loading="lazy">
                             </div>
                             <div class="trainer_info">
                                 <div class="trainer_name">{{ $teacher?->full_name }}</div>
                                 <p>{!! $teacher?->designation !!}</p>
                                 <div class="trainer_link">
                                     <i class="fa-brands tw fa-square-twitter"></i>
                                     <i class="fa-brands fb fa-square-facebook"></i>
                                     <i class="fa-brands ld fa-linkedin"></i>
                                     <i class="fa-brands in fa-square-instagram"></i>
                                 </div>
                             </div>
                         </div>
                         <div class="trainer_descrip">
                             <p>
                                 {!! $teacher?->short_description !!}
                             </p>
                         </div>
                         <div class="profational_trainer_button_area mt-4">
                             <a href="{{ route('teacher.details', ['teacher_name' => str_replace(' ', '-', $teacher?->full_name), 'slug' => $teacher?->slug]) }}"
                                 class="button_all">
                                 <span class="btn_text">বিস্তারিত দেখুন</span>
                                 <span class="btn_icon"><i class="fa-solid fa-arrow-right"></i></span>
                             </a>
                         </div>
                     </div>
                 @endforeach
             </div>

         </div>
     </div>
 </section>
 <!-- /trainers area ends -->
