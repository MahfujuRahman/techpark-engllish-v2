 <div class="col-xs-12 col-md-6" id="submit_message">
     <div class="right_area">
         <!-- contact_form_title start -->
         <div class="contact_form_title">
             <h2 class="title_text">Inbox Us</h2>
         </div>

         <!-- form_area start -->
         <form action="{{ route('contact_store') }}" method="POST">
             @csrf
             <input class="form_item" type="text" name="full_name" placeholder="your name *">
             <span class="help-block">
                 <strong>{{ $errors->first('full_name') }}</strong>
             </span>

             <input class="form_item" type="email" name="email" placeholder="your email *">
             <span class="help-block">
                 <strong>{{ $errors->first('email') }}</strong>
             </span>

             <input class="form_item" type="text" name="subject" placeholder="subject *">
             <span class="help-block">
                 <strong>{{ $errors->first('subject') }}</strong>
             </span>

             <textarea class="form_item" name="message" id="#" placeholder="write your message"></textarea>
             <span class="help-block">
                 <strong>{{ $errors->first('message') }}</strong>
             </span>

             <button class="form_button">Submit</button>
         </form>
         <!-- form_area end -->
     </div>
 </div>
