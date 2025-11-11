<div class="position-relative py-3">
    <!-- ✅ ข้อความ overlay -->
    <div class="col-12 ads-caption text-center">
        <h3 class="mb-0 text-shadow">PS HOME CARE THAILAND</h3>
        <div class="text-center">
            <h3 class="text-shadow">บริการกำจัดปลวก</h3>
            <p>
                <button class="btn btn-sm btn-pink shadow"><i class="fa-solid fa-phone"></i> โทรหาเรา</button>
                <button class="btn btn-sm btn-success shadow"><i class="fa-brands fa-line"></i> แอดเพื่อน</button>
            </p>
        </div>
        <div class="col p-2" style="height: 40px;">
            <div class="typewriter-container text-center">
                <h3>
                    <span id="typewriter-text" class="text-white text-shadow"></span>
                </h3>
            </div>
        </div>
    </div>

    <!-- ✅ Carousel -->
    <div id="carouselAds" class="carousel slide" data-bs-ride="carousel" data-bs-interval="2000">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="{{asset('ads/ads01.jpg')}}" class="d-block w-100" alt="PShome 1">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads02.jpg')}}" class="d-block w-100" alt="PShome 2">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads03.jpg')}}" class="d-block w-100" alt="PShome 3">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads04.jpg')}}" class="d-block w-100" alt="PShome 4">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads05.jpg')}}" class="d-block w-100" alt="PShome 5">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads06.jpg')}}" class="d-block w-100" alt="PShome 6">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads07.jpg')}}" class="d-block w-100" alt="PShome 7">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads08.jpg')}}" class="d-block w-100" alt="PShome 8">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads09.jpg')}}" class="d-block w-100" alt="PShome 9">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads10.jpg')}}" class="d-block w-100" alt="PShome 10">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads11.jpg')}}" class="d-block w-100" alt="PShome 11">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads12.jpg')}}" class="d-block w-100" alt="PShome 12">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads13.jpg')}}" class="d-block w-100" alt="PShome 13">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads14.jpg')}}" class="d-block w-100" alt="PShome 14">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads15.jpg')}}" class="d-block w-100" alt="PShome 15">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads16.jpg')}}" class="d-block w-100" alt="PShome 16">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads17.jpg')}}" class="d-block w-100" alt="PShome 17">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads18.jpg')}}" class="d-block w-100" alt="PShome 18">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads19.jpg')}}" class="d-block w-100" alt="PShome 19">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads20.jpg')}}" class="d-block w-100" alt="PShome 20">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads21.jpg')}}" class="d-block w-100" alt="PShome 21">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads22.jpg')}}" class="d-block w-100" alt="PShome 22">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads23.jpg')}}" class="d-block w-100" alt="PShome 23">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads24.jpg')}}" class="d-block w-100" alt="PShome 24">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads25.jpg')}}" class="d-block w-100" alt="PShome 25">
            </div>
            <div class="carousel-item">
                <img src="{{asset('ads/ads26.jpg')}}" class="d-block w-100" alt="PShome 26">
            </div>
        </div>
        <!-- ปุ่มสไลด์ -->
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselAds" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselAds" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>
</div>




@push('styles')
<style>
    #carouselAds img {
        width: 100%;
        height: 450px;
        object-fit: cover;
    }

    .ads-caption {
        position: absolute;
        bottom: 15px;
        left: 0;
        z-index: 1000;
        color: white;
        background: rgba(0, 0, 0, 0.05);
        padding: 10px 0px;
        font-size: 1rem;
    }
</style>
@endpush
@push('scripts')
<script>
    const messages = [
    "มีปัญหา เรื่องปลวก ",
    "เราคือ คำตอบ! ",
    "บริการกำจัดปลวก ",
    "โดย ทีมงานมืออาชีพ! ",
    "ระบบเหยื่อ-ระบบน้ำยา ",
    "ปลอดภัย ไร้กังวล ",
    "ปรึกษาเรา คลิ๊กเลย! ",
];

let messageIndex = 0;
let charIndex = 0;
let isDeleting = false;
const textElement = document.getElementById("typewriter-text");

function type() {
  const currentMessage = messages[messageIndex];
  const visibleText = isDeleting
    ? currentMessage.substring(0, charIndex--)
    : currentMessage.substring(0, charIndex++);

  textElement.textContent = visibleText;

  if (!isDeleting && charIndex === currentMessage.length) {
    isDeleting = true;
    setTimeout(type, 2000); // รอ 2 วินาที ก่อนลบ
  } else if (isDeleting && charIndex === 0) {
    isDeleting = false;
    messageIndex = (messageIndex + 1) % messages.length;
    setTimeout(type, 300); // รอเล็กน้อยก่อนพิมพ์ข้อความถัดไป
  } else {
    setTimeout(type, isDeleting ? 40 : 80); // ความเร็วในการพิมพ์/ลบ
  }
}

type();
</script>
@endpush