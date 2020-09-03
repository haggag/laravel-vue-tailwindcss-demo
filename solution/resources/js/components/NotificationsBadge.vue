<template>
    <a :href="url"
       aria-label="Notifications"
       class="px-3 py-1 rounded-full block hover:bg-gray-100 focus:outline-none">
        <span class="badge mb-3 bg-red-600 rounded-full px-2 py-1 text-center object-right-top text-xs text-white"
              v-if="count > 0">{{ count }}</span>
        <svg height="16" width="16" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M10 14H6c0 1.1.9 2 2 2s2-.9 2-2zm5-3h-.5c-.7-.7-1.5-1.7-1.5-3V5c0-2.8-2.2-5-5-5S3 2.2 3 5v3c0 1.3-.8 2.3-1.5 3H1c-.6 0-1 .4-1 1s.4 1 1 1h14c.6 0 1-.4 1-1s-.4-1-1-1z"
                fill="#A0A5BA"/>
        </svg>
    </a>

</template>

<script>
import store from '../store/store'

export default {
    props: {
        init_count: {
            type: Number,
            required: true
        },
        url: {
            type: String,
            required: true
        }
    },

    mounted() {
        store
            .dispatch('notification/updateUnreadCount', this.init_count)

    },
    computed: {
        count() {
            return this.$store.getters['notification/unreadCount']
        }
    }
}
</script>
