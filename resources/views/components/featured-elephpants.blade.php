@props(['pool'])

@if ($pool->isNotEmpty())
    <div
        {{ $attributes->class(['hidden sm:grid grid-cols-2 gap-4 max-w-md mx-auto lg:max-w-none']) }}
        x-data="featuredElephpants(@js($pool))"
    >
        <template x-for="slot in slots" :key="slot.key">
            <div
                class="relative aspect-square overflow-hidden rounded-2xl border border-white/20 bg-white/10 shadow-xl shadow-indigo-950/30"
                :class="slot.offset ? 'translate-y-5' : ''"
            >
                <div
                    class="hero-elephpant-frame relative size-full overflow-hidden"
                    :class="slot.glitching ? 'is-glitching' : ''"
                >
                    <img
                        :src="slot.imageUrl"
                        :alt="slot.name"
                        width="300"
                        height="300"
                        class="hero-elephpant-image size-full object-cover"
                        loading="lazy"
                        decoding="async"
                    >
                    <span class="hero-elephpant-scanlines pointer-events-none" aria-hidden="true"></span>
                    <span class="hero-elephpant-noise pointer-events-none" aria-hidden="true"></span>
                </div>
                <span
                    class="absolute bottom-3 left-3 rounded-full bg-indigo-950/75 px-2 py-0.5 text-xs font-semibold text-white"
                    x-text="slot.year"
                ></span>
            </div>
        </template>
    </div>
@endif
