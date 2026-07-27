@extends('layouts.app')
@section('content')


<!-- 2. ABOUT HERO SECTION -->
<section class="about-hero-section">
    <div class="about-hero-wrapper">
        <video class="about-hero-bg" autoplay loop muted playsinline>
            <source src="{{ asset('assets/images/about_banner.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        <div class="about-hero-content">
            <h1 class="about-hero-title">FOR THOSE WHO CHOOSE<br>WHAT FEELS RIGHT.</h1>
        </div>
    </div>
</section>


<!-- 3. QUESTION SECTION -->
<section class="about-question-section">
    <h2 class="about-question-heading">IT STARTED WITH A SIMPLE QUESTION.</h2>




    <div class="about-3col-container">



        <div class="about-col about-col-dark about-col-left">

            <div class="about-col-content">
                <h3 class="about-col-title">WE NOTICED SOMETHING CHANGING.</h3>
                <p class="about-col-text">People were becoming more thoughtful about what they brought into
                    their lives. Less about having more, and more about choosing better.</p>
            </div>
        </div>
        <div class=" about-col-light">
            <div class=" about-col-light_inner">
                <h3 class="about-col-title dark-title">THAT'S THE IDEA BEHIND LA PAVONE.</h3>
                <p class="about-col-text dark-text">The things we keep coming back to are rarely the loudest.
                    They're the ones that feel right, fit naturally into our lives, and stay with us over time.
                </p>
            </div>
        </div>
        <div class="about-col about-col-dark">
            <h3 class="about-col-title">THAT'S WHAT WE BELIEVE.</h3>
            <p class="about-col-text">Quality speaks for itself. The best things don't demand attention, they
                earn a place in your everyday life.</p>
        </div>
    </div>
</section>











<!-- 4. FULL IMAGE SECTION -->
<section class="about-full-img-section">
    <img src="{{ asset('assets/images/about_img2.png') }}" alt="La Pavone Archway" class="about-full-img">
</section>




<!-- NEW PIXEL PERFECT SLIDER SECTION -->
<style>

    .new-about-bg {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    .new-about-bg img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .new-about-slider-container {
        display: flex;
        gap: 30px;
        overflow-x: auto;
        scroll-behavior: smooth;
        scrollbar-width: none;
        /* Firefox */
        -ms-overflow-style: none;
        /* IE/Edge */
        scroll-snap-type: x mandatory;
        padding: 0 4vw;
        /* Padding so cards can be centered */
        width: 100%;
        align-items: center;
    }

    .new-about-slider-container::-webkit-scrollbar {
        display: none;
        /* Chrome/Safari/Opera */
    }

    .new-about-card {
        flex: 0 0 auto;
        /*width: 650px;*/
        /*max-width: 85vw;*/
        background-color: #f1ebd9;
        padding: 100px 40px;
        text-align: center;
        scroll-snap-align: center;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
            height: 350px;
    }

    .new-about-title {
        color: #8c3b3b;
        font-family: 'Cinzel', serif;
        font-size: 22px;
        letter-spacing: 1px;
        margin-bottom: 25px;
        text-transform: uppercase;
        font-weight: 600;
    }

    .new-about-text {
        color: #1F5552;
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        line-height: 1.6;
        max-width: 480px;
        margin: 0 auto;
        text-align: justify;
    }

    .new-slider-btn {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        width: 50px;
        height: 50px;
        background-color: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        transition: all 0.3s ease;
    }

    .new-slider-btn:hover {
        background-color: #fff;
        transform: translateY(-50%) scale(1.05);
    }

    .new-slider-btn.prev {
        left: 30px;
    }

    .new-slider-btn.next {
        right: 30px;
    }

    @media (max-width: 768px) {
      .new-about-card {
    padding: 60px 30px;
    margin: 10px 0px;
}

        .new-about-title {
            font-size: 18px;
        }

        .new-about-text {
            font-size: 14px;
        }

        .new-slider-btn.prev {
            left: 10px;
        }

        .new-slider-btn.next {
            right: 10px;
        }
    }
</style>

<section class="new-about-slider-section">
    <div class="new-about-bg">
        <img src="{{ asset('assets/images/Testimonials 1.png') }}" alt="Background">
    </div>

    <!--<button class="new-slider-btn prev" id="new-slider-prev" aria-label="Previous">-->
    <!--    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1F5552" stroke-width="2"-->
    <!--        stroke-linecap="round" stroke-linejoin="round">-->
    <!--        <polyline points="15 18 9 12 15 6"></polyline>-->
    <!--    </svg>-->
    <!--</button>-->

    <div class="new-about-slider-container" id="new-about-slider">
        <div class="row">
        <div class="col-md-4">
        <div class="new-about-card">
            <h3 class="new-about-title">Minimalism.</h3>
            <p class="new-about-text">At LA PAVONE, minimalism is more than an aesthetic—it is a way of thinking. We believe every product should be purposeful, refined, and free from unnecessary excess. Every detail is designed with intention to create timeless elegance that transcends trends. Simplicity allows true craftsmanship to shine. Our creations are made to remain relevant today, tomorrow, and for years to come.</p>
        </div>
        </div>
        
        <div class="col-md-4">
        <div class="new-about-card">
            <h3 class="new-about-title">Exquisite.</h3>
            <p class="new-about-text">Exquisite reflects our relentless pursuit of exceptional craftsmanship, thoughtful design, and uncompromising quality. We obsess over the finest details, ensuring every creation delivers an elevated experience. From materials and finishes to functionality and presentation, nothing is left to chance. Excellence is not a feature—it is our standard. Every LA PAVONE product is created to embody enduring refinement.</p>
        
        </div>
        </div>
        
        
        <div class="col-md-4">
        <div class="new-about-card">
            <h3 class="new-about-title">Worth Choosing</h3>
            <p class="new-about-text">Worth Choosing is our promise to create products that genuinely deserve a place in your life. We believe luxury should be defined by quality, design, and purpose—not by an inflated price tag. Every creation is thoughtfully developed to deliver lasting value and timeless appeal. Our goal is to earn trust through consistency, authenticity, and excellence. LA PAVONE exists to be the choice people make with confidence, and never regret.</p>
        
        </div>
        </div>
        
    </div>
    </div>

    <!--<button class="new-slider-btn next" id="new-slider-next" aria-label="Next">-->
    <!--    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#1F5552" stroke-width="2"-->
    <!--        stroke-linecap="round" stroke-linejoin="round">-->
    <!--        <polyline points="9 18 15 12 9 6"></polyline>-->
    <!--    </svg>-->
    <!--</button>-->
</section>

<script>
//     document.addEventListener("DOMContentLoaded", function () {
//         const container = document.getElementById('new-about-slider');
//         const btnPrev = document.getElementById('new-slider-prev');
//         const btnNext = document.getElementById('new-slider-next');

//         if (container && btnPrev && btnNext) {
//             btnPrev.addEventListener('click', function () {
//                 const card = container.querySelector('.new-about-card');
//                 const cardWidth = card ? card.offsetWidth : 650;
//                 const gap = 30;
//                 container.scrollBy({ left: -(cardWidth + gap), behavior: 'smooth' });
//             });

//             btnNext.addEventListener('click', function () {
//                 const card = container.querySelector('.new-about-card');
//                 const cardWidth = card ? card.offsetWidth : 650;
//                 const gap = 30;
//                 container.scrollBy({ left: (cardWidth + gap), behavior: 'smooth' });
//             });
//         }
//     });
 </script>


@endsection