<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>@yield('title', 'Hilly Chilly — Nepal\'s Gamified Adventure Platform')</title>
  <meta name="description" content="@yield('meta_description', 'Trek Nepal\'s greatest peaks, scan QR codes, earn points and unlock badges with Hilly Chilly — Nepal\'s #1 gamified adventure app.')"/>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    * { font-family: 'Inter', system-ui, sans-serif; }
    body { background: #080c08; color: #e8f0e8; }
    :root { --green: #4CAF75; --gold: #F5B342; --dark: #080c08; }

    /* Gradients */
    .grad-text { background: linear-gradient(135deg, #4CAF75, #a3e6be); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    .grad-green { background: linear-gradient(135deg, #1a4a2e, #2d7a4f); }
    .grad-gold  { background: linear-gradient(135deg, #F5B342, #e67e22); }

    /* Glass */
    .glass { background: rgba(255,255,255,.04); backdrop-filter: blur(16px); -webkit-backdrop-filter: blur(16px); border: 1px solid rgba(255,255,255,.08); }
    .glass-hover:hover { background: rgba(255,255,255,.07); border-color: rgba(76,175,117,.3); transition: all .3s; }

    /* Glows */
    .glow-green { box-shadow: 0 0 60px rgba(76,175,117,.25); }
    .glow-gold  { box-shadow: 0 0 60px rgba(245,179,66,.2); }

    /* Blob */
    .blob { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; animation: morph 8s ease-in-out infinite; }
    @keyframes morph { 0%,100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; } 50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; } }

    /* Float */
    .float { animation: floatY 6s ease-in-out infinite; }
    @keyframes floatY { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-16px); } }

    /* Particles */
    #particles { position: absolute; inset: 0; width: 100%; height: 100%; pointer-events: none; }

    /* Swiper */
    .swiper-pagination-bullet { background: #4CAF75; opacity: .5; }
    .swiper-pagination-bullet-active { background: #F5B342; opacity: 1; }

    /* Step line */
    .step-line { position: absolute; height: 2px; background: linear-gradient(90deg, rgba(76,175,117,.5), transparent); top: 50%; transform: translateY(-50%); left: 60%; right: -24px; }

    /* Nav */
    .nav-scroll { background: rgba(8,12,8,.92); backdrop-filter: blur(20px); border-bottom: 1px solid rgba(255,255,255,.06); }

    /* CTA */
    .btn-green { background: linear-gradient(135deg, #2d7a4f, #4CAF75); color: white; font-weight: 700; border-radius: 1rem; padding: .9rem 2rem; transition: all .3s; }
    .btn-green:hover { box-shadow: 0 0 30px rgba(76,175,117,.4); transform: translateY(-2px); }
  </style>
  @stack('styles')
</head>
<body>

{{-- ── Navigation ─────────────────────────────────────────── --}}
<nav id="nav" class="fixed top-0 w-full z-50 transition-all duration-300">
  <div class="container mx-auto px-6 py-4 flex items-center justify-between">
    <a href="{{ route('home') }}" class="flex items-center gap-2">
      <div class="w-9 h-9 rounded-xl grad-green flex items-center justify-center font-black text-lg text-white">HC</div>
      <span class="font-black text-xl"><span class="text-white">Hilly</span><span class="text-[#F5B342]">Chilly</span></span>
    </a>
    <div class="hidden md:flex items-center gap-8 text-sm text-gray-300">
      <a href="{{ route('home') }}" class="hover:text-[#4CAF75] transition">Home</a>
      <a href="#packages" class="hover:text-[#4CAF75] transition">Packages</a>
      <a href="#ai" class="hover:text-[#4CAF75] transition">Chilly AI</a>
      <a href="{{ route('about') }}" class="hover:text-[#4CAF75] transition">About</a>
      <a href="{{ route('contact') }}" class="hover:text-[#4CAF75] transition">Contact</a>
    </div>
    <div class="flex items-center gap-3">
      <a href="#download" class="btn-green text-sm hidden md:block">Download App</a>
    </div>
  </div>
</nav>

@yield('content')

{{-- ── Footer ──────────────────────────────────────────────── --}}
<footer class="bg-[#050805] border-t border-white/5 py-16">
  <div class="container mx-auto px-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
      <div class="md:col-span-2">
        <div class="flex items-center gap-2 mb-4">
          <div class="w-10 h-10 rounded-xl grad-green flex items-center justify-center font-black text-xl text-white">HC</div>
          <span class="font-black text-2xl"><span class="text-white">Hilly</span><span class="text-[#F5B342]">Chilly</span></span>
        </div>
        <p class="text-gray-400 max-w-sm leading-relaxed">Nepal's premier gamified adventure platform. Trek, earn points, unlock badges — and redeem real rewards across 25+ destinations.</p>
        <div class="flex gap-4 mt-6">
          <a href="#" class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:border-white/20 transition">𝕏</a>
          <a href="#" class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:border-white/20 transition">f</a>
          <a href="#" class="w-10 h-10 glass rounded-xl flex items-center justify-center text-gray-400 hover:text-white hover:border-white/20 transition">in</a>
        </div>
      </div>
      <div>
        <h4 class="font-bold text-white mb-4">Company</h4>
        <ul class="space-y-3 text-gray-400 text-sm">
          <li><a href="{{ route('about') }}" class="hover:text-[#4CAF75] transition">About Us</a></li>
          <li><a href="{{ route('contact') }}" class="hover:text-[#4CAF75] transition">Contact</a></li>
          <li><a href="#" class="hover:text-[#4CAF75] transition">Privacy Policy</a></li>
          <li><a href="#" class="hover:text-[#4CAF75] transition">Terms of Service</a></li>
        </ul>
      </div>
      <div>
        <h4 class="font-bold text-white mb-4">App</h4>
        <ul class="space-y-3 text-gray-400 text-sm">
          <li><a href="#packages" class="hover:text-[#4CAF75] transition">Quest Packages</a></li>
          <li><a href="#ai" class="hover:text-[#4CAF75] transition">Chilly AI</a></li>
          <li><a href="#download" class="hover:text-[#4CAF75] transition">Download</a></li>
          <li><a href="#" class="hover:text-[#4CAF75] transition">App Store</a></li>
        </ul>
      </div>
    </div>
    <div class="border-t border-white/5 pt-8 flex flex-col md:flex-row items-center justify-between gap-4 text-gray-500 text-sm">
      <p>© {{ date('Y') }} Hilly Chilly. All rights reserved. Made with ♥ in Nepal.</p>
      <p class="text-xs">NPR amounts shown in Nepali Rupees</p>
    </div>
  </div>
</footer>

<script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  AOS.init({ once: true, offset: 80, easing: 'ease-out-cubic', duration: 800 });
  // Sticky nav
  window.addEventListener('scroll', () => {
    document.getElementById('nav').classList.toggle('nav-scroll', window.scrollY > 60);
  });
</script>
@stack('scripts')
</body>
</html>
