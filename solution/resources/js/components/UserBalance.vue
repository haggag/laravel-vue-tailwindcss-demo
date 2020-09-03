<template>
    <div class="mt-1 max-w-2xl text-3xl leading-5 font-medium text-green-400">
        {{ wholePartOfTotalBalanceAsDollars | currency }}<span
        class="text-2xl">.{{ fractionPartOfTotalBalanceAsDollars }}</span>
    </div>
</template>

<script>

export default {
    // props: {
    //     balance: {
    //         type: Number,
    //         required: true
    //     }
    // },
    // created() {
    //     store
    //         .dispatch('entry/initBalance', this.balance)
    //
    // },
    computed: {
        // ...mapState(['user']),
        fractionPartOfTotalBalanceAsDollars() {
            return this.$store.state.user.user.total_cents.toString().slice(-2).padStart(2, "0")
        },
        wholePartOfTotalBalanceAsDollars() {
            return Math.trunc(this.$store.getters['user/totalBalanceAsDollars'])
        }
    }
}
</script>
