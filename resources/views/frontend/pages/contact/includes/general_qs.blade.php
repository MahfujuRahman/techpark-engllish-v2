@push('styles')
    <style>
    .ans_area {
        display: none;
        padding: 10px;
        background: #f8f9fa;
        border-radius: 5px;
    }

    .ans_area.show {
        display: block;
    }

    .question_icon i {
        transition: transform 0.3s ease;
    }

    .rotate {
        transform: rotate(180deg);
    }
</style>
@endpush

<!-- general question area start-->
<section class="general_question_area">
    <!-- Title Start -->
    <div class="question_title">
        <div class="general_question_title">
            <h2 class="text">General Questions</h2>
        </div>
        <div class="general_question_sub_title">
            <p class="sub_text">If you have any questions, you can search for them here</p>
        </div>
    </div>
    <!-- Title End -->

    <!-- FAQ Items -->
    <div class="faq_container">
        @foreach ($faqs as $faq)
            <div class="question_and_ans_area">
                <div class="question_area">
                    <div class="question">
                        <p class="q_text">{{ $faq->title }}</p>
                    </div>
                    <div class="question_icon">
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                </div>
                <div class="ans_area">
                    <p class="a_text">{!! $faq->description !!}</p>
                </div>
            </div>
        @endforeach


    </div>
</section>

<!-- general question area end-->

@push('scripts')
    <script>
        [
            ...document.querySelectorAll(".general_question_acordion_icon"),
        ].forEach((element) => {
            element.onclick = function(e) {
                e.currentTarget.parentNode.parentNode.classList.toggle(
                    "active"
                );
                // console.log(e.currentTarget.parentNode.classList);
            };
        });

        document.addEventListener("click", (e) => {
            const question = e.target.closest(".question_area");
            if (!question) return; // If clicked outside, do nothing

            question.classList.toggle("active");
            question.nextElementSibling.classList.toggle("show");

            // Toggle icon rotation
            question.querySelector(".fa-angle-down").classList.toggle("rotate");
        });
    </script>
@endpush