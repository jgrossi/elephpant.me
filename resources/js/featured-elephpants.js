document.addEventListener('alpine:init', () => {
    Alpine.data('featuredElephpants', (pool) => ({
        slots: [],
        pool,
        timers: [],
        reducedMotion: window.matchMedia('(prefers-reduced-motion: reduce)').matches,

        init() {
            this.slots = this.initialSlots();

            if (this.reducedMotion || this.pool.length <= 1) {
                return;
            }

            this.slots.forEach((_, index) => {
                const delay = 2500 + index * 1800 + Math.random() * 1500;

                this.timers.push(setTimeout(() => this.scheduleSlot(index), delay));
            });
        },

        destroy() {
            this.timers.forEach(clearTimeout);
        },

        initialSlots() {
            const shuffled = [...this.pool].sort(() => Math.random() - 0.5);

            return shuffled.slice(0, 4).map((item, index) => ({
                ...item,
                key: `${item.id}-${index}`,
                glitching: false,
                offset: index === 1 || index === 3,
            }));
        },

        scheduleSlot(index) {
            const run = () => {
                this.glitchSwap(index);

                this.timers.push(setTimeout(run, 5000 + Math.random() * 4000));
            };

            run();
        },

        glitchSwap(index) {
            this.slots[index].glitching = true;

            setTimeout(() => {
                const excludeIds = this.slots.map((slot) => slot.id);
                const next = this.pickReplacement(excludeIds);
                const offset = this.slots[index].offset;

                this.slots = this.slots.map((slot, slotIndex) => slotIndex === index
                    ? {
                        ...next,
                        key: `${next.id}-${Date.now()}`,
                        glitching: false,
                        offset,
                    }
                    : slot);
            }, 850);
        },

        pickReplacement(excludeIds) {
            const candidates = this.pool.filter((item) => ! excludeIds.includes(item.id));

            if (candidates.length === 0) {
                return this.pool[Math.floor(Math.random() * this.pool.length)];
            }

            return candidates[Math.floor(Math.random() * candidates.length)];
        },
    }));
});
