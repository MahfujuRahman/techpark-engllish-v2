 <!-- mission -->
 <section class="mission_part" id="our_mission">
     <div class="container">
         <div class="mission_part_details">
             <div class="mission_part_image">
                 <img class="rounded rounded-sm" src="{{ assetHelper(optional($our_mission)->image) }}"
                     alt="{{ $our_mission->title }}" loading="lazy">
             </div>
             <div class="mission_part_description">
                 <div class="mission_part_title">
                     <h2 class="mission_title_bangla">{{ $our_mission->title }}</h2>
                 </div>
                 <div class="mission_part_info">
                     {!! $our_mission->description !!}
                 </div>
             </div>
         </div>
     </div>
 </section>
 <!-- /mission -->
