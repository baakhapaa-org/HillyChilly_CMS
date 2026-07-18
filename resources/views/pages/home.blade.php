@extends('layouts.app')
@section('title', 'Hilly Chilly — Nepal\'s Gamified Adventure Platform')

@push('styles')
<style>
  .pkg-card { transition: transform .35s cubic-bezier(.34,1.56,.64,1), box-shadow .35s; }
  .pkg-card:hover { transform: translateY(-8px); box-shadow: 0 24px 60px rgba(76,175,117,.2); }
  .counter-num { font-size: 2.8rem; font-weight: 900; line-height: 1; }
  .hero-badge { background: rgba(245,179,66,.1); border: 1px solid rgba(245,179,66,.25); }
</style>
@endpush

@section('content')

{{-- ══════════════════════════════════════════════════════
     HERO
══════════════════════════════════════════════════════════ --}}
<section class="relative min-h-screen flex items-center overflow-hidden" style="background:#080c08;">
  <canvas id="particles" aria-hidden="true"></canvas>

  {{-- Decorative blobs --}}
  <div class="absolute top-1/4 -left-40 w-[28rem] h-[28rem] blob opacity-20 glow-green grad-green pointer-events-none"></div>
  <div class="absolute bottom-1/4 -right-40 w-80 h-80 blob opacity-15 pointer-events-none" style="background:linear-gradient(135deg,#F5B342,#e67e22)"></div>

  <div class="container mx-auto px-6 pt-28 pb-20 relative z-10">
    <div class="flex flex-col lg:flex-row items-center gap-16">

      {{-- Copy --}}
      <div class="lg:w-1/2 text-center lg:text-left" data-aos="fade-right">
        <span class="inline-block px-5 py-2 rounded-full text-xs font-bold tracking-widest uppercase mb-8 hero-badge text-[#F5B342]">
          Nepal's #1 Gamified Adventure App
        </span>
        <h1 class="text-5xl md:text-6xl lg:text-7xl font-black leading-[1.05] mb-6 tracking-tight">
          Adventure<br/>
          <span class="grad-text">Gamified.</span><br/>
          <span style="color:#F5B342;">Rewarded.</span>
        </h1>
        <p class="text-gray-300 text-xl mb-10 leading-relaxed max-w-lg mx-auto lg:mx-0">
          Trek Nepal's greatest peaks, scan QR codes at ancient temples, earn points, unlock badges — and redeem <strong class="text-white">real rewards</strong>.
        </p>

        {{-- Store buttons --}}
        <div class="flex flex-wrap gap-4 justify-center lg:justify-start mb-10" id="download">
          <a href="#" class="flex items-center gap-3 glass glass-hover rounded-2xl px-6 py-4">
            <svg class="w-8 h-8 text-white" viewBox="0 0 24 24" fill="currentColor">
              <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/>
            </svg>
            <div><div class="text-xs text-gray-400">Download on the</div><div class="font-bold text-lg text-white">App Store</div></div>
          </a>
          <a href="#" class="flex items-center gap-3 glass glass-hover rounded-2xl px-6 py-4">
            <svg class="w-8 h-8" viewBox="0 0 24 24" fill="currentColor">
              <path d="M3.18.24c-.27.14-.48.36-.62.62L13.7 11.3 16.62 8.37 4.06 1.15c-.3-.14-.61-.14-.88.09z" fill="#4285F4"/>
              <path d="M2.56 1.1C2.2 1.55 2 2.17 2 2.92v18.16c0 .75.2 1.37.56 1.83L13.7 12 2.56 1.1z" fill="#EA4335"/>
              <path d="M20.06 10.39l-3.44-1.97-3.25 3.25 3.25 3.25 3.48-1.99c.99-.57.99-2 0-2.54z" fill="#FBBC05"/>
              <path d="M3.18 23.76c.27.27.63.4.99.39.17 0 .34-.04.5-.12l12.57-7.2-2.93-2.93L3.18 23.76z" fill="#34A853"/>
            </svg>
            <div><div class="text-xs text-gray-400">Get it on</div><div class="font-bold text-lg text-white">Google Play</div></div>
          </a>
        </div>

        {{-- Social proof --}}
        <div class="flex items-center gap-4 justify-center lg:justify-start">
          <div class="flex -space-x-2">
            @for($i = 0; $i < 5; $i++)
            <div class="w-9 h-9 rounded-full border-2 border-[#080c08] grad-green flex items-center justify-center text-white text-xs font-bold">{{ ['R','A','P','B','S'][$i] }}</div>
            @endfor
          </div>
          <div class="text-sm text-gray-400"><strong class="text-white">12,000+</strong> adventurers · ⭐ 4.9 rating</div>
        </div>
      </div>

      {{-- Phone mockup --}}
      <div class="lg:w-1/2 flex justify-center" data-aos="fade-left" data-aos-delay="150">
        <div class="relative">
          <div class="absolute -inset-6 blob opacity-25 glow-green grad-green"></div>
          <div class="relative glass rounded-[3.5rem] p-3 float">
            <div class="rounded-[3rem] overflow-hidden w-72 md:w-80 bg-[#0d130d]">
              {{-- Notch --}}
              <div class="h-7 bg-[#080c08] flex items-center justify-center">
                <div class="w-28 h-4 bg-[#111] rounded-full"></div>
              </div>
              {{-- App screen --}}
              <div class="h-[580px] grad-green flex flex-col items-center justify-center p-6 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10" style="background:radial-gradient(circle at 50% 0%, #4CAF75, transparent 70%)"></div>
                <div class="text-center mb-8 relative">
                  <div class="text-5xl font-black text-white mb-1">Hilly<span class="text-yellow-400">Chilly</span></div>
                  <div class="text-green-200 text-sm">Gamified Nepal Adventures</div>
                </div>
                @php $mockPkgs = [['Everest Base Camp','14 days','NPR 85K','🏔'],['Pokhara Lakeside','5 days','NPR 35K','🏄'],['Annapurna Circuit','18 days','NPR 95K','⛰']]; @endphp
                <div class="w-full space-y-3">
                  @foreach($mockPkgs as $mp)
                  <div class="glass rounded-2xl p-4 flex items-center gap-3">
                    <div class="text-3xl">{{ $mp[3] }}</div>
                    <div>
                      <div class="text-white text-sm font-bold">{{ $mp[0] }}</div>
                      <div class="text-green-300 text-xs mt-0.5">{{ $mp[1] }} · {{ $mp[2] }}</div>
                    </div>
                  </div>
                  @endforeach
                </div>
                <div class="mt-6 w-full glass rounded-2xl p-4 text-center">
                  <div class="text-yellow-400 font-bold text-lg">⭐ 350 Points Earned!</div>
                  <div class="text-green-200 text-xs mt-1">2 more tasks to unlock next badge</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- Scroll cue --}}
  <div class="absolute bottom-8 left-1/2 -translate-x-1/2 flex flex-col items-center gap-2 text-gray-500 text-xs animate-bounce">
    <span>Scroll</span>
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
  </div>
</section>

{{-- ══════════════════════════════════════════════════════
     STATS
══════════════════════════════════════════════════════════ --}}
<section class="py-20" style="background:#0d130d;">
  <div class="container mx-auto px-6">
    @php
      $stats = [['12,000+','Adventurers','🧗'],['50+','Quest Packages','📦'],['25+','Destinations','📍'],['98%','Satisfaction','⭐']];
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach($stats as $si => $stat)
      <div class="glass rounded-2xl p-8 text-center glass-hover" data-aos="zoom-in" data-aos-delay="{{ $si * 80 }}">
        <div class="text-4xl mb-3">{{ $stat[2] }}</div>
        <div class="counter-num grad-text mb-1">{{ $stat[0] }}</div>
        <div class="text-gray-400 text-sm">{{ $stat[1] }}</div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══════════════════════════════════════════════════════
     FEATURED PACKAGES
══════════════════════════════════════════════════════════ --}}
<section class="py-28" id="packages" style="background:#080c08;">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16" data-aos="fade-up">
      <span class="text-[#F5B342] font-semibold tracking-widest uppercase text-xs">Quest Packages</span>
      <h2 class="text-4xl md:text-5xl font-black mt-3 mb-4">
        @if($featuredPackages->count() > 0)
          Featured <span class="grad-text">Adventures</span>
        @else
          Our <span class="grad-text">Adventures</span>
        @endif
      </h2>
      <p class="text-gray-400 max-w-xl mx-auto">Each package is a gamified experience — complete tasks, earn points, unlock badges.</p>
    </div>

    @php $displayPackages = $featuredPackages->count() > 0 ? $featuredPackages : $allPackages; @endphp

    @if($displayPackages->count() > 0)
    <div class="swiper pkg-swiper">
      <div class="swiper-wrapper pb-10">
        @foreach($displayPackages as $pkg)
        <div class="swiper-slide">
          <div class="pkg-card glass rounded-3xl overflow-hidden h-full">
            <div class="relative h-52 overflow-hidden">
              @if($pkg->image_url)
                <img src="{{ $pkg->image_url }}" alt="{{ $pkg->title }}" class="w-full h-full object-cover"/>
              @else
                <div class="w-full h-full grad-green flex items-center justify-center text-6xl">🏔</div>
              @endif
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
              <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold uppercase" style="background:rgba(45,122,79,.85)">{{ $pkg->category }}</span>
              <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold text-yellow-400 glass">⭐ {{ number_format($pkg->points_reward) }} pts</span>
            </div>
            <div class="p-6">
              <h3 class="text-lg font-bold mb-3 text-white">{{ $pkg->title }}</h3>
              <div class="flex items-center justify-between text-sm text-gray-400 mb-5">
                <span>⏱ {{ $pkg->duration_days }} days</span>
                <span class="text-green-400 font-bold text-base">NPR {{ number_format($pkg->price_npr) }}</span>
              </div>
              @if($pkg->location_label)
              <div class="text-gray-500 text-xs mb-4">📍 {{ $pkg->location_label }}</div>
              @endif
              <a href="#download" class="block text-center py-3 rounded-xl font-bold transition grad-green text-white hover:opacity-90 text-sm">Book This Quest →</a>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      <div class="swiper-pagination"></div>
    </div>
    @else
    {{-- Fallback hardcoded packages --}}
    <div class="swiper pkg-swiper">
      <div class="swiper-wrapper pb-10">
        @php
          $fallbackPkgs = [
            ['Everest Base Camp Explorer','14 days','NPR 85,000','500','trekking','https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600','Solukhumbu, Nepal'],
            ['Pokhara Lakeside Adventure','5 days','NPR 35,000','250','adventure','https://images.unsplash.com/photo-1544122613-3b9e6e0deb28?w=600','Pokhara, Nepal'],
            ['Annapurna Circuit Trek','18 days','NPR 95,000','700','trekking','https://images.unsplash.com/photo-1574236170878-f4b9370fab33?w=600','Gandaki, Nepal'],
            ['Chitwan Wildlife Safari','4 days','NPR 28,000','200','wildlife','https://images.unsplash.com/photo-1551364536-84e7c95a7d59?w=600','Chitwan, Nepal'],
            ['Lumbini Pilgrimage Trail','3 days','NPR 18,000','150','spiritual','https://images.unsplash.com/photo-1603831153920-4a73e36cf9d4?w=600','Lumbini, Nepal'],
            ['Kathmandu Valley Cultural','3 days','NPR 15,000','120','cultural','https://images.unsplash.com/photo-1580803834068-5ef46c9d5ef3?w=600','Kathmandu, Nepal'],
          ];
        @endphp
        @foreach($fallbackPkgs as $fp)
        <div class="swiper-slide">
          <div class="pkg-card glass rounded-3xl overflow-hidden h-full">
            <div class="relative h-52 overflow-hidden">
              <img src="{{ $fp[5] }}" alt="{{ $fp[0] }}" class="w-full h-full object-cover"/>
              <div class="absolute inset-0 bg-gradient-to-t from-black/70 to-transparent"></div>
              <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-xs font-bold uppercase" style="background:rgba(45,122,79,.85)">{{ $fp[4] }}</span>
              <span class="absolute top-4 right-4 px-3 py-1 rounded-full text-xs font-bold text-yellow-400 glass">⭐ {{ $fp[3] }} pts</span>
            </div>
            <div class="p-6">
              <h3 class="text-lg font-bold mb-3 text-white">{{ $fp[0] }}</h3>
              <div class="flex items-center justify-between text-sm text-gray-400 mb-4">
                <span>⏱ {{ $fp[1] }}</span>
                <span class="text-green-400 font-bold text-base">{{ $fp[2] }}</span>
              </div>
              <div class="text-gray-500 text-xs mb-4">📍 {{ $fp[6] }}</div>
              <a href="#download" class="block text-center py-3 rounded-xl font-bold grad-green text-white hover:opacity-90 text-sm">Book This Quest →</a>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      <div class="swiper-pagination"></div>
    </div>
    @endif
  </div>
</section>

{{-- ══════════════════════════════════════════════════════
     HOW IT WORKS
══════════════════════════════════════════════════════════ --}}
<section class="py-28" style="background:#0d130d;">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16" data-aos="fade-up">
      <span class="text-[#F5B342] font-semibold tracking-widest uppercase text-xs">Simple Process</span>
      <h2 class="text-4xl md:text-5xl font-black mt-3">How It <span class="grad-text">Works</span></h2>
    </div>
    @php
      $steps = [
        ['01','Download App','Install Hilly Chilly from App Store or Google Play in seconds.','📱'],
        ['02','Choose Quest','Browse 50+ gamified adventure packages across Nepal.','🗺'],
        ['03','Complete Tasks','GPS check-ins, QR scans, photo proofs and cultural quizzes.','✅'],
        ['04','Earn Rewards','Collect points, unlock badges, redeem prizes.','🏅'],
      ];
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
      @foreach($steps as $si => $step)
      <div class="relative" data-aos="fade-up" data-aos-delay="{{ $si * 120 }}">
        @if($si < 3)
        <div class="step-line hidden md:block"></div>
        @endif
        <div class="glass rounded-3xl p-8 text-center relative z-10 glass-hover">
          <div class="text-5xl mb-4">{{ $step[3] }}</div>
          <div class="text-6xl font-black grad-text opacity-10 absolute top-3 right-4 leading-none">{{ $step[0] }}</div>
          <h3 class="text-xl font-bold mb-3 text-white">{{ $step[1] }}</h3>
          <p class="text-gray-400 text-sm leading-relaxed">{{ $step[2] }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══════════════════════════════════════════════════════
     CHILLY AI
══════════════════════════════════════════════════════════ --}}
<section class="py-28 overflow-hidden" id="ai" style="background:#080c08;">
  <div class="container mx-auto px-6">
    <div class="flex flex-col lg:flex-row items-center gap-20">
      <div class="lg:w-1/2" data-aos="fade-right">
        <span class="text-[#F5B342] font-semibold tracking-widest uppercase text-xs">AI-Powered</span>
        <h2 class="text-4xl md:text-5xl font-black mt-3 mb-6">Meet <span class="grad-text">Chilly AI</span></h2>
        <p class="text-gray-300 text-lg mb-8 leading-relaxed">
          Just tell Chilly where you want to go — "I'm planning to visit Pokhara next week" — and it instantly recommends the perfect quest package, itinerary, and budget breakdown.
        </p>
        <ul class="space-y-5">
          @foreach(['Natural language trip planning','Real-time package recommendations','Budget & duration matching','Available 24/7 in the app'] as $feat)
          <li class="flex items-center gap-4 text-gray-300">
            <span class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 text-white grad-green">✓</span>
            {{ $feat }}
          </li>
          @endforeach
        </ul>
      </div>
      <div class="lg:w-1/2" data-aos="fade-left" data-aos-delay="150">
        <div class="glass rounded-3xl p-6 max-w-md mx-auto">
          <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/8">
            <div class="w-11 h-11 rounded-full grad-green flex items-center justify-center font-black text-xl text-white">C</div>
            <div>
              <div class="font-bold text-white">Chilly AI</div>
              <div class="text-green-400 text-xs flex items-center gap-1"><span class="w-1.5 h-1.5 bg-green-400 rounded-full animate-pulse"></span> Online</div>
            </div>
          </div>
          <div class="space-y-4">
            <div class="flex justify-end"><div class="bg-green-800/70 rounded-2xl rounded-tr-sm px-4 py-3 max-w-[80%] text-sm">I am planning to visit Pokhara next week 🏔</div></div>
            <div class="flex gap-3">
              <div class="w-8 h-8 rounded-full grad-green flex items-center justify-center text-xs font-bold flex-shrink-0 text-white">C</div>
              <div class="glass rounded-2xl rounded-tl-sm px-4 py-3 max-w-[80%] text-sm text-gray-200">Namaste! Pokhara is stunning! Based on your timeline, here are top packages for next week 👇</div>
            </div>
            <div class="ml-11 glass rounded-2xl p-4">
              <div class="flex items-center gap-3">
                <img src="https://images.unsplash.com/photo-1544122613-3b9e6e0deb28?w=80" class="w-14 h-14 rounded-xl object-cover" alt="Pokhara"/>
                <div>
                  <div class="font-bold text-sm text-white">Pokhara Lakeside Adventure</div>
                  <div class="text-green-400 text-xs mt-1">5 days · NPR 35,000 · 250 pts</div>
                  <div class="text-yellow-400 text-xs mt-0.5">📍 Pokhara, Nepal</div>
                </div>
              </div>
            </div>
            <div class="flex justify-end"><div class="bg-green-800/70 rounded-2xl rounded-tr-sm px-4 py-3 max-w-[80%] text-sm">Perfect! Book it 🎉</div></div>
          </div>
          <div class="mt-4 pt-4 border-t border-white/8">
            <div class="glass rounded-xl px-4 py-3 text-gray-500 text-sm">Ask Chilly anything...</div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ══════════════════════════════════════════════════════
     FEATURES
══════════════════════════════════════════════════════════ --}}
<section class="py-28" style="background:#0d130d;">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16" data-aos="fade-up">
      <h2 class="text-4xl md:text-5xl font-black">Why <span class="grad-text">Hilly Chilly?</span></h2>
    </div>
    @php
      $features = [
        ['🗺','GPS Quest Check-ins','Real-time location verification at mountain checkpoints, monasteries, and cultural sites.'],
        ['📷','Photo Proof Tasks','Capture memories at specific GPS coordinates to complete quest milestones.'],
        ['📲','QR Code Scanning','Unlock hidden stories, discounts, and rewards by scanning QR codes at landmarks.'],
        ['🏆','Points & Badges','Every adventure earns points redeemable for discounts, free nights, and exclusive gear.'],
        ['🌐','Offline Mode','Download quests before your trek — complete tasks without cell service in remote areas.'],
        ['🤖','AI Trip Planner','Tell Chilly AI your dream destination and get a complete Nepal itinerary in seconds.'],
      ];
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($features as $fi => $feat)
      <div class="glass rounded-3xl p-8 group glass-hover" data-aos="fade-up" data-aos-delay="{{ $fi * 70 }}">
        <div class="text-4xl mb-5 group-hover:scale-110 transition-transform duration-300 inline-block">{{ $feat[0] }}</div>
        <h3 class="text-xl font-bold mb-3 text-white">{{ $feat[1] }}</h3>
        <p class="text-gray-400 leading-relaxed text-sm">{{ $feat[2] }}</p>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══════════════════════════════════════════════════════
     TESTIMONIALS
══════════════════════════════════════════════════════════ --}}
@if($testimonials->count() > 0 || true)
<section class="py-28" style="background:#080c08;">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16" data-aos="fade-up">
      <h2 class="text-4xl font-black">Adventurers <span class="grad-text">Love Us</span></h2>
    </div>
    @php
      $displayReviews = $testimonials->count() > 0 ? $testimonials : collect([
        (object)['name'=>'Riya Sharma','package_name'=>'Pokhara Trek','content'=>'Hilly Chilly turned my vacation into a game! The QR scanning at Barahi Temple was so fun. Earned 250 points in one weekend!','rating'=>5],
        (object)['name'=>'Aakash Tamang','package_name'=>'EBC Explorer','content'=>'The GPS check-in feature is genius. Standing at Namche Bazaar and scanning — I felt like a real adventurer completing a mission.','rating'=>5],
        (object)['name'=>'Priya Rai','package_name'=>'Chitwan Safari','content'=>'The AI suggested the Chitwan wildlife package when I said I love animals. Perfect! Booked in 2 minutes.','rating'=>5],
        (object)['name'=>'Bikram KC','package_name'=>'Annapurna Circuit','content'=>'Completed 700 points! Used them for a discount on my next booking. The reward system is brilliant.','rating'=>5],
      ]);
    @endphp
    <div class="swiper testimonial-swiper">
      <div class="swiper-wrapper pb-10">
        @foreach($displayReviews as $review)
        <div class="swiper-slide">
          <div class="glass rounded-3xl p-8 h-full glass-hover">
            <div class="flex gap-0.5 text-yellow-400 mb-5 text-lg">
              @for($s = 0; $s < (int)$review->rating; $s++)★@endfor
            </div>
            <p class="text-gray-300 leading-relaxed mb-6 italic text-sm">"{{ $review->content }}"</p>
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 rounded-full grad-green flex items-center justify-center font-black text-lg text-white">{{ substr($review->name, 0, 1) }}</div>
              <div>
                <div class="font-bold text-white">{{ $review->name }}</div>
                <div class="text-gray-400 text-sm">{{ $review->package_name }}</div>
              </div>
            </div>
          </div>
        </div>
        @endforeach
      </div>
      <div class="swiper-pagination mt-4"></div>
    </div>
  </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════
     BLOGS
══════════════════════════════════════════════════════════ --}}
@if($blogs->count() > 0)
<section class="py-28" style="background:#0d130d;">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16" data-aos="fade-up">
      <h2 class="text-4xl font-black">From the <span class="grad-text">Trail</span></h2>
      <p class="text-gray-400 mt-3">Stories, tips, and adventures from our community.</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      @foreach($blogs as $blog)
      <div class="glass rounded-3xl overflow-hidden glass-hover" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
        @if($blog->image_url)
        <img src="{{ $blog->image_url }}" alt="{{ $blog->title }}" class="w-full h-44 object-cover"/>
        @else
        <div class="w-full h-44 grad-green flex items-center justify-center text-5xl">📰</div>
        @endif
        <div class="p-6">
          <span class="text-xs text-[#F5B342] font-semibold uppercase tracking-widest">{{ $blog->category }}</span>
          <h3 class="font-bold text-white mt-2 mb-3 text-lg">{{ $blog->title }}</h3>
          @if($blog->excerpt)
          <p class="text-gray-400 text-sm leading-relaxed">{{ $blog->excerpt }}</p>
          @endif
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════
     FAQ
══════════════════════════════════════════════════════════ --}}
@if($faqs->count() > 0)
<section class="py-28" style="background:#080c08;">
  <div class="container mx-auto px-6">
    <div class="text-center mb-16" data-aos="fade-up">
      <h2 class="text-4xl font-black">Frequently Asked <span class="grad-text">Questions</span></h2>
    </div>
    <div class="max-w-3xl mx-auto space-y-4">
      @foreach($faqs as $faq)
      <div class="glass rounded-2xl glass-hover" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
        <details class="group">
          <summary class="flex items-center justify-between p-6 cursor-pointer list-none">
            <span class="font-semibold text-white">{{ $faq->question }}</span>
            <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform duration-300 flex-shrink-0 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
          </summary>
          <div class="px-6 pb-6 text-gray-400 text-sm leading-relaxed border-t border-white/5 pt-4">{{ $faq->answer }}</div>
        </details>
      </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ══════════════════════════════════════════════════════
     CTA
══════════════════════════════════════════════════════════ --}}
<section class="py-28 relative overflow-hidden" data-aos="fade-up">
  <div class="absolute inset-0 grad-green opacity-95"></div>
  <div class="absolute inset-0 opacity-5" style="background-image:url('data:image/svg+xml,%3Csvg width=60 height=60 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22rgba(255,255,255,0.5)%22 fill-rule=%22evenodd%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/svg%3E');"></div>
  <div class="relative z-10 container mx-auto px-6 text-center">
    <h2 class="text-4xl md:text-6xl font-black text-white mb-6 leading-tight">Start Your Nepal<br/>Adventure <span class="text-yellow-300">Today</span></h2>
    <p class="text-green-100 text-xl mb-10 max-w-2xl mx-auto">Join 12,000+ adventurers. Download the app and earn your first <strong class="text-yellow-300">50 points</strong> just for signing up.</p>
    <div class="flex flex-wrap justify-center gap-4 mb-8">
      <a href="#" class="flex items-center gap-3 bg-black/25 backdrop-blur-sm px-8 py-4 rounded-2xl text-white font-bold hover:bg-black/40 transition border border-white/10">🍎 Download for iOS</a>
      <a href="#" class="flex items-center gap-3 bg-black/25 backdrop-blur-sm px-8 py-4 rounded-2xl text-white font-bold hover:bg-black/40 transition border border-white/10">🤖 Download for Android</a>
    </div>
    <p class="text-green-200 text-sm">Free to download · No credit card required · Works offline</p>
  </div>
</section>

@endsection

@push('scripts')
<script>
  // Particle canvas
  (function() {
    const c = document.getElementById('particles');
    if (!c) return;
    const ctx = c.getContext('2d');
    let w, h, pts = [];
    const resize = () => { w = c.width = c.offsetWidth; h = c.height = c.offsetHeight; };
    const make = () => {
      pts = Array.from({length: 55}, () => ({
        x: Math.random()*w, y: Math.random()*h,
        vx:(Math.random()-.5)*.5, vy:(Math.random()-.5)*.5,
        r: Math.random()*2+.5
      }));
    };
    const draw = () => {
      ctx.clearRect(0,0,w,h);
      pts.forEach(p => {
        p.x+=p.vx; p.y+=p.vy;
        if(p.x<0||p.x>w) p.vx*=-1; if(p.y<0||p.y>h) p.vy*=-1;
        ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
        ctx.fillStyle='rgba(76,175,117,.45)'; ctx.fill();
      });
      for(let i=0;i<pts.length;i++) for(let j=i+1;j<pts.length;j++) {
        const dx=pts[i].x-pts[j].x, dy=pts[i].y-pts[j].y, d=Math.sqrt(dx*dx+dy*dy);
        if(d<120) {
          ctx.beginPath(); ctx.moveTo(pts[i].x,pts[i].y); ctx.lineTo(pts[j].x,pts[j].y);
          ctx.strokeStyle=`rgba(76,175,117,${.15*(1-d/120)})`; ctx.lineWidth=.5; ctx.stroke();
        }
      }
      requestAnimationFrame(draw);
    };
    window.addEventListener('resize', ()=>{ resize(); make(); });
    resize(); make(); draw();
  })();

  // Package swiper
  new Swiper('.pkg-swiper', {
    slidesPerView: 1, spaceBetween: 24, grabCursor: true,
    pagination: { el: '.pkg-swiper .swiper-pagination', clickable: true },
    breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
  });

  // Testimonial swiper
  new Swiper('.testimonial-swiper', {
    slidesPerView: 1, spaceBetween: 24, loop: true, grabCursor: true,
    autoplay: { delay: 4500, disableOnInteraction: false },
    pagination: { el: '.testimonial-swiper .swiper-pagination', clickable: true },
    breakpoints: { 640: { slidesPerView: 2 }, 1024: { slidesPerView: 3 } }
  });
</script>
@endpush
