@extends('layouts.app')
@section('title', 'Contact — Hilly Chilly')
@section('content')
<section class="min-h-screen pt-32 pb-20" style="background:#080c08;">
  <div class="container mx-auto px-6 max-w-3xl">
    <div class="text-center mb-16" data-aos="fade-up">
      <span class="text-[#F5B342] font-semibold tracking-widest uppercase text-xs">Get in Touch</span>
      <h1 class="text-5xl font-black mt-4 mb-4">Contact <span class="grad-text">Us</span></h1>
      <p class="text-gray-400">Have questions? We'd love to hear from you.</p>
    </div>
    <div class="glass rounded-3xl p-10" data-aos="fade-up">
      <div class="grid md:grid-cols-3 gap-6 mb-10">
        <div class="text-center">
          <div class="text-3xl mb-3">📧</div>
          <div class="font-bold text-white text-sm mb-1">Email</div>
          <div class="text-gray-400 text-sm">hello@hillychilly.com</div>
        </div>
        <div class="text-center">
          <div class="text-3xl mb-3">📍</div>
          <div class="font-bold text-white text-sm mb-1">Location</div>
          <div class="text-gray-400 text-sm">Kathmandu, Nepal</div>
        </div>
        <div class="text-center">
          <div class="text-3xl mb-3">📱</div>
          <div class="font-bold text-white text-sm mb-1">Phone</div>
          <div class="text-gray-400 text-sm">+977 1 XXXX XXXX</div>
        </div>
      </div>
      <form class="space-y-5">
        <div class="grid md:grid-cols-2 gap-5">
          <input type="text" placeholder="Your name" class="w-full glass rounded-xl px-4 py-4 text-white placeholder-gray-500 outline-none focus:border-green-500/50 transition"/>
          <input type="email" placeholder="Your email" class="w-full glass rounded-xl px-4 py-4 text-white placeholder-gray-500 outline-none focus:border-green-500/50 transition"/>
        </div>
        <input type="text" placeholder="Subject" class="w-full glass rounded-xl px-4 py-4 text-white placeholder-gray-500 outline-none focus:border-green-500/50 transition"/>
        <textarea rows="5" placeholder="Your message..." class="w-full glass rounded-xl px-4 py-4 text-white placeholder-gray-500 outline-none focus:border-green-500/50 transition resize-none"></textarea>
        <button type="submit" class="w-full btn-green py-4 text-white font-bold rounded-xl">Send Message →</button>
      </form>
    </div>
  </div>
</section>
@endsection
