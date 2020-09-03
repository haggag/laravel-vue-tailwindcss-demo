<template>
    <div :class="color_class" class="flex justify-center items-center text-white text-sm font-bold px-4 py-2 rounded"
         role="alert" v-show="active">
        <span v-html="icon"></span>
        <p class="px-2">{{ notification.message }}</p>
    </div>
</template>

<script>
import store from '../store/store'


export default {
    mounted() {
        const user = this.$store.state.user.user
        if (user.locked) {
            store.dispatch('notification/activate', {
                message: user.lock_message,
                status: 'processing', // Currently only processing message is persistent, other types are dismissed after refresh.
            })
        }
        Echo.private(`App.User.${user.id}`)
            .notification((notification) => {
                console.log(notification);
                if (notification.status === 'info') {
                    store
                        .dispatch('entry/refetchCurrentPage')
                        .then(() => {
                            // $this.$data.loaded = true
                            store.dispatch('notification/activate', {
                                message: notification.message,
                                status: notification.status,
                                persisted: notification.persisted,
                            })
                            if ("newbalance" in notification) {
                                store.dispatch('user/updateBalance', notification.newbalance)
                            }
                        })
                } else {
                    store.dispatch('notification/activate', {
                        message: notification.message,
                        status: notification.status,
                        persisted: notification.persisted,
                    })
                }

                if (notification.status !== 'processing') {
                    store.dispatch('user/unlock')
                }
            });

    },
    beforeDestroy() {
        Echo.leave('events-channel');
    },
    computed: {
        color_class() {
            const status = this.notification.status
            let color = 'orange'
            if (status === 'error') {
                color = 'red'
            } else if (status === 'info') {
                color = 'green'
            }

            return "bg-" + color + "-400"
        },
        icon() {
            const status = this.notification.status
            let icon = ''
            if (status === 'processing') {
                icon = '<svg  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"  width="20px" height="20px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid"> <circle cx="50" cy="50" fill="none" stroke="#ffffff" stroke-width="16" r="35" stroke-dasharray="164.93361431346415 56.97787143782138"> <animateTransform attributeName="transform" type="rotate" repeatCount="indefinite" dur="1s" values="0 50 50;360 50 50" keyTimes="0;1"></animateTransform> </circle> </svg>'
            }

            return icon
        },
        active() {
            return this.$store.getters['notification/active']
        },
        notification() {
            return this.$store.getters['notification/notification']
        }
    }
}
</script>
