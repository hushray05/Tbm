@extends('layouts.public')

@section('title', 'Contact Us & Support • TBM Enterprise')

@section('content')
<div class="space-y-0">

    <!-- Hero Section -->
    <section class="relative overflow-hidden bg-gradient-to-b from-primary/5 via-transparent to-transparent py-16 md:py-24 lg:py-32">
        <div class="absolute inset-0 hero-gradient-overlay"></div>
        <div class="absolute -top-20 -right-20 w-72 h-72 rounded-full bg-primary/10 blur-3xl"></div>

        <div class="relative max-w-7xl mx-auto px-4 md:px-8 text-center">
            <span class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider bg-primary/10 text-primary border border-primary/20 mb-6 inline-block">
                <span class="material-symbols-outlined text-sm">headset_mic</span>
                {{ $page->content['hero_badge'] ?? 'Contact Support' }}
            </span>
            
            <h1 class="font-headline text-4xl md:text-5xl lg:text-6xl font-extrabold text-text-rich leading-tight text-balance mb-6">
                {{ $page->content['hero_title'] ?? "Let's talk about your business" }}
            </h1>
            
            <p class="text-secondary text-lg leading-relaxed max-w-3xl mx-auto">
                {{ $page->content['hero_text'] ?? 'Our Kampala-based team is ready to help you get started with TBM Enterprise. Reach out for sales inquiries, technical support, or partnership opportunities.' }}
            </p>
        </div>
    </section>

    <!-- Contact Form & Info Section -->
    <section class="py-16 md:py-24 lg:py-32 bg-surface">
        <div class="max-w-7xl mx-auto px-4 md:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16">
                <!-- Contact Form -->
                <div class="bg-bg-subtle border border-border-quiet rounded-2xl p-6 md:p-8 tbm-shadow space-y-6">
                    <h3 class="font-headline font-bold text-lg text-text-rich border-b border-border-quiet pb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">mail</span>
                        <span>Send Us a Message</span>
                    </h3>

                    <form action="{{ route('contact.submit') }}" method="POST" class="space-y-4">
                        @csrf

                        <div>
                            <label for="name" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}" placeholder="e.g. Sarah Jenkins" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-surface focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="email" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Email Address <span class="text-danger">*</span></label>
                                <input type="email" name="email" id="email" required value="{{ old('email') }}" placeholder="sarah@company.ug" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-surface focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                            </div>
                            <div>
                                <label for="phone" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Phone Number</label>
                                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" placeholder="+256 700 123456" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-surface focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                            </div>
                        </div>

                        <div>
                            <label for="subject" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Subject <span class="text-danger">*</span></label>
                            <input type="text" name="subject" id="subject" required value="{{ old('subject') }}" placeholder="e.g. Sales inquiry, Technical support" class="w-full h-11 px-3.5 rounded-lg border border-border-quiet bg-surface focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">
                        </div>

                        <div>
                            <label for="message" class="block text-xs font-bold text-secondary uppercase tracking-wider mb-1.5">Message <span class="text-danger">*</span></label>
                            <textarea name="message" id="message" rows="5" required placeholder="Tell us how we can help your business..." class="w-full p-3.5 rounded-lg border border-border-quiet bg-surface focus:bg-white focus:border-primary focus:ring-1 focus:ring-primary text-sm text-text-rich outline-none">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit" class="w-full bg-primary hover:bg-primary-container text-white py-3 px-4 rounded-lg font-bold text-sm shadow-sm transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-base">send</span>
                            <span>Send Message</span>
                        </button>
                    </form>
                </div>

                <!-- Contact Info & Map -->
                <div class="space-y-6">
                    <!-- Contact Details Card -->
                    <div class="bg-bg-subtle border border-border-quiet rounded-2xl p-6 tbm-shadow space-y-5">
                        <h3 class="font-headline font-bold text-lg text-text-rich border-b border-border-quiet pb-3">
                            Get in Touch
                        </h3>

                        <div class="space-y-4">
                            <!-- Address -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-primary/10 border border-primary/20 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-primary text-xl">location_on</span>
                                </div>
                                <div>
                                    <span class="font-headline font-bold text-text-rich text-sm block mb-1">Office Address</span>
                                    <p class="text-secondary text-sm leading-relaxed">
                                        {!! nl2br(e('Kamonkoli, Budaka District')) !!}
                                    </p>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-emerald-700 text-xl">call</span>
                                </div>
                                <div>
                                    <span class="font-headline font-bold text-text-rich text-sm block mb-1">Phone Number</span>
                                    <a href="tel:+256780327787" class="text-primary text-sm font-semibold hover:underline block">+256 780 327 787</a>
                                    <span class="text-secondary text-xs">Mon-Fri: 8:00 AM - 6:00 PM EAT</span>
                                </div>
                            </div>

                            <!-- Email -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-amber-700 text-xl">mail</span>
                                </div>
                                <div>
                                    <span class="font-headline font-bold text-text-rich text-sm block mb-1">Email Address</span>
                                    <a href="mailto:{{ $page->content['email'] ?? 'support@taskbusinessmanager.com' }}" class="text-primary text-sm font-semibold hover:underline block">{{ $page->content['email'] ?? 'support@taskbusinessmanager.com' }}</a>
                                </div>
                            </div>

                            <!-- WhatsApp -->
                            <div class="flex items-start gap-4">
                                <div class="w-12 h-12 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-purple-700 text-xl">chat</span>
                                </div>
                                <div>
                                    <span class="font-headline font-bold text-text-rich text-sm block mb-1">WhatsApp</span>
                                    <a href="https://wa.me/256780327787" target="_blank" class="text-primary text-sm font-semibold hover:underline block">+256 780 327 787</a>
                                    <span class="text-secondary text-xs">Quick response during business hours</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Google Map -->
                    <div class="bg-bg-subtle border border-border-quiet rounded-2xl overflow-hidden tbm-shadow">
                        <div class="p-4 border-b border-border-quiet flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary text-xl">map</span>
                            <span class="font-headline font-bold text-text-rich text-sm">Our Location</span>
                        </div>
                        <div class="relative" style="height: 300px;">
                            <iframe 
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3989.776284818917!2d32.6084!3d0.3136!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMMKwMTgnNDkuMCJOIDMywrAzNiczMC4yIkU!5e0!3m2!1sen!2sug!4v1234567890!5m2!1sen!2sug" 
                                width="100%" 
                                height="100%" 
                                style="border:0;" 
                                allowfullscreen="" 
                                loading="lazy" 
                                referrerpolicy="no-referrer-when-downgrade"
                                class="absolute inset-0">
                            </iframe>
                        </div>
                    </div>

                    <!-- Business Hours -->
                    <div class="bg-primary/5 border border-primary/20 rounded-2xl p-6 space-y-3">
                        <h3 class="font-headline font-bold text-text-rich flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">schedule</span>
                            Business Hours
                        </h3>
                        <p class="text-sm font-semibold text-text-rich">Mon-Fri: 8:00 AM - 6:00 PM EAT</p>
                        <p class="text-xs text-secondary pt-2 border-t border-primary/20">
                            All times are in East Africa Time (EAT / UTC+3)
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection