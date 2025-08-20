 <!-- vision -->
 <section class="vision_part" id="our_vision">
     <div class="vision_part_details">
         <div class="vision_part_description">
             <div class="vision_part_title">
                 {{-- <h2 class="vision_title_bangla">আমাদের ভিশন</h2> --}}
                 <h2 class="vision_title_bangla">{{ $our_vision->title }}</h2>
             </div>
             <div class="vision_part_info">
                 {!! $our_vision->description !!}
             </div>
         </div>
         <div class="vision_part_image">
             <img class="rounded rounded-sm" src="{{ assetHelper(optional($our_vision)->image) }}"
                 alt="{{ $our_vision->title }}" loading="lazy">
         </div>
     </div>
 </section>
 <!-- /vision -->

 <style>
     .vision_part .vision_part_details .vision_part_image {
         margin-left: unset;
     }
 </style>
