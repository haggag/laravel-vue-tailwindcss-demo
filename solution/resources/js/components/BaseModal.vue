<template>
    <div class="fixed bottom-0 inset-x-0 px-4 pb-4 sm:inset-0 sm:flex sm:items-center sm:justify-center" v-if="active"
    >
        <transition
            enter-active-class="transition ease-out duration-300"
            enter-class="transform opacity-0 "
            enter-to-class="transform opacity-100 "
            leave-active-class="transition ease-in duration-200 "
            leave-class="transform opacity-100 "
            leave-to-class="transform opacity-0 "
        >

            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
        </transition>

        <transition
            enter-active-class="transition ease-out duration-300 "
            enter-class="transform opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            enter-to-class="transform opacity-100 translate-y-0 sm:scale-100"
            leave-active-class="transition ease-in duration-200 "
            leave-class="transform opacity-100 translate-y-0 sm:scale-100"
            leave-to-class="transform opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        >
            <div aria-labelledby="modal-headline"
                 aria-modal="true"
                 class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:max-w-2xl sm:w-full"
                 role="dialog">
                <div class="px-4 py-6  sm:px-6">
                    <h3 class="text-xl leading-6 font-medium text-gray-900">
                        {{ title }}
                    </h3>
                </div>
                <slot :close="close"></slot>
            </div>
        </transition>
    </div>
</template>

<script>
export default {
    props: {
        title: {
            type: String,
            required: true
        },
    },
    data() {
        return {
            active: false,
        }
    },
    methods: {
        close() {
            this.active = false
        },
        open() {
            this.active = true
        }
    }
}
</script>
