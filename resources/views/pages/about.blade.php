@extends('layouts.app')
@section('title', 'About — Hilly Chilly')
@section('content')
<section class="min-h-screen pt-32 pb-20" style="background:#080c08;">
  <div class="container mx-auto px-6 max-w-4xl">
    <div class="text-center mb-16" data-aos="fade-up">
      <span class="text-[#F5B342] font-semibold tracking-widest uppercase text-xs">Our Story</span>
      <h1 class="text-5xl font-black mt-4 mb-6">About <span class="grad-text">Hilly Chilly</span></h1>
      <p class="text-gray-300 text-xl leading-relaxed">Nepal's premier gamified adventure platform — turning every trek into an achievement.</p>
    </div>
    <div class="grid md:grid-cols-2 gap-8 mb-16">
      <div class="glass rounded-3xl p-8" data-aos="fade-right">
        <div class="text-4xl mb-4">🎯</div>
        <h3 class="text-xl font-bold text-white mb-3">Our Mission</h3>
        <p class="text-gray-400 leading-relaxed">To gamify Nepal's rich adventure tourism landscape, making every trek, cultural visit, and wildlife encounter a rewarding, shareable achievement.</p>
      </div>
      <div class="glass rounded-3xl p-8" data-aos="fade-left">
        <div class="text-4xl mb-4">🌏</div>
        <h3 class="text-xl font-bold text-white mb-3">Our Vision</h3>
        <p class="text-gray-400 leading-relaxed">A Nepal where every adventurer — local or international — can document, earn, and celebrate their journey through the world's most spectacular landscapes.</p>
      </div>
    </div>
    <div class="glass rounded-3xl p-10 text-center" data-aos="zoom-in">
      <h3 class="text-2xl font-black text-white mb-4">Built in Nepal 🇳🇵</h3>
      <p class="text-gray-300 leading-relaxed max-w-2xl mx-auto">We are a Kathmandu-based team of engineers, trekkers, and adventure enthusiasts on a mission to connect the world to Nepal's breathtaking natural and cultural heritage through technology.</p>
    </div>
  </div>
</section>
@endsection
