<template>
    <span :class="color_class">
        {{ sign }} {{ formatted_value }}<span :class="fraction_size">.{{ fraction_value }}</span>
    </span>
</template>

<script>
export default {
    props: {
        amount: {
            type: Number,
            required: true
        },
        positive_color: {
            type: String,
            required: true
        },
        negative_color: {
            type: String,
            required: true
        },
        fraction_size: {
            type: String,
            required: true
        }
    },
    computed: {
        color_class() {
            return this.amount > 0 ? this.positive_color : this.negative_color
        },
        sign() {
            return this.amount > 0 ? '+' : '-'
        },
        formatted_value() {
            return '$' + Math.abs(Math.trunc(this.amount / 100))
        },
        fraction_value() {
            return this.amount.toString().slice(-2).padStart(2, "0")
        }
    }
}
</script>
